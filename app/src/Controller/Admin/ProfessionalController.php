<?php
declare(strict_types=1);

namespace Farol360\Ancora\Controller\Admin;

use Farol360\Ancora\Controller;
use Farol360\Ancora\Model;
use Farol360\Ancora\Model\EntityFactory;
use Fusonic\SpreadsheetExport\Spreadsheet;
use Fusonic\SpreadsheetExport\ColumnTypes\DateColumn;
use Fusonic\SpreadsheetExport\ColumnTypes\NumericColumn;
use Fusonic\SpreadsheetExport\ColumnTypes\TextColumn;
use Fusonic\SpreadsheetExport\Writers\OdsWriter;
use Slim\Flash\Messages as FlashMessages;
use Slim\Views\Twig as View;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



class ProfessionalController extends Controller
{

    protected $professionalModel;
    protected $professionalTypeModel;
    protected $patientModel;
    protected $attendanceModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;
    protected $userModel;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $professionalModel,
        Model $professionalTypeModel,
        Model $patientModel,
        Model $attendanceModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->professionalModel = $professionalModel;
        $this->professionalTypeModel = $professionalTypeModel;
        $this->patientModel         = $patientModel;
        $this->attendanceModel      = $attendanceModel;
        $this->userModel    = $userModel;
        $this->eventLogModel = $eventLogModel;
        $this->eventLogTypeModel = $eventLogTypeModel;
        $this->entityFactory = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {

        $params = $request->getQueryParams();

        if (!empty($params['page'])) {
            $page = intval($params['page']);

            if (!empty($params['search'])) {
            $search = (int)$params['search'];


            if ($search  == 3) {
                $limit = 20;
                $offset = ($page - 1) * $limit;


                $professionals = $this->professionalModel->getAll($offset, $limit);

                $professional_types = $this->professionalTypeModel->getAll();

                $amountProfessionals = $this->professionalModel->getAmount();
                $amountPages = ceil($amountProfessionals->amount / $limit);

                return $this->view->render($response, 'admin/professional/index.twig', [
                    'professionals' => $professionals,
                    'professional_types' => $professional_types,
                    'page' => $page,
                    'amountPages' => $amountPages,
                    'search' => $search
                    ]);
            }

            if ($search  == 1) {
                $limit = 20;
                $offset = ($page - 1) * $limit;


                $professionals = $this->professionalModel->getAllByStatus2($search, $offset, $limit);
                //$professionals2 = $this->professionalModel->getAllByStatus($search, $offset, $limit);
                //var_dump($professionals);die;
                //foreach ($professionals as $professional) {
            //$professional->status = $this->professionalModel->get((int)$professional->id)->status;


                //if ($professional->status == 0 OR 1) {
                $professional_types = $this->professionalTypeModel->getAll();

                $amountProfessionals = $this->professionalModel->getAmountStatus($search);
                $amountPages = ceil($amountProfessionals->amount / $limit);
                //var_dump($professionals);die;
                return $this->view->render($response, 'admin/professional/index.twig', [
                    'professionals' => $professionals,
                    //'professionals2' => $professionals2,
                    'professional_types' => $professional_types,
                    'page' => $page,
                    'amountPages' => $amountPages,
                    'search' => $search
                    ]);

            }

            if ($search  == 2) {
                $limit = 20;
                $offset = ($page - 1) * $limit;


                $professionals = $this->professionalModel->getAllByStatus($search, $offset, $limit);

                $professional_types = $this->professionalTypeModel->getAll();

                $amountProfessionals = $this->professionalModel->getAmountStatus($search);
                $amountPages = ceil($amountProfessionals->amount / $limit);

                return $this->view->render($response, 'admin/professional/index.twig', [
                    'professionals' => $professionals,
                    'professional_types' => $professional_types,
                    'page' => $page,
                    'amountPages' => $amountPages,
                    'search' => $search
                    ]);
                }
            }
        } else {
            $page = 1;
        }
        $limit = 20;
        $offset = ($page - 1) * $limit;


        $professionals = $this->professionalModel->getAllIndex($offset, $limit);

        $professional_types = $this->professionalTypeModel->getAll();

        $amountProfessionals = $this->professionalModel->getAmountStatus1();
        $amountPages = ceil($amountProfessionals->amount / $limit);

        return $this->view->render($response, 'admin/professional/index.twig', [
            'professionals' => $professionals,
            'professional_types' => $professional_types,
            'page' => $page,
            'amountPages' => $amountPages
            ]);
    }

    public function add(Request $request, Response $response): Response
    {
        if (empty($request->getParsedBody())) {
            $professional_types = $this->professionalTypeModel->getAll();
            return $this->view->render($response, 'admin/professional/add.twig', ['professional_types' => $professional_types]);
        }

        $data = $request->getParsedBody();
        //var_dump($data);die;
        $data['password'] = 'Ancora1337';
        $data['role_id'] = 6;
        $data['end_numero'] = (int) $data['end_numero'];
        $data['professional_status'] = (int)$data['professional_status'];


        if ($this->professionalModel->getByEmail($data['email']) != false) {
            $this->flash->addMessage('success', 'O email já existe. por favor cadastre um email único.');
            return $this->httpRedirect($request, $response, '/admin/professionals/add');
        }

        $user = $this->entityFactory->createUser($data);



        $professional['id_user'] = $this->userModel->add($user);
        $professional['id_professional_type'] = $data['id_professional_type'];
        $professional['professional_status'] = $data['professional_status'];



        $professional = $this->entityFactory->createProfessional($professional);

        $id_professional = $this->professionalModel->add($professional);

        if ( ($id_professional != null) || ($id_professional != false) ) {
            $eventLog['id_professional']    = $id_professional;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('create_professional')->id;
            $eventLog['description'] = 'Profissional ' . $user->name .' cadastrado';

            $eventLog = $this->entityFactory->createEventLog($eventLog);

            $this->eventLogModel->add($eventLog);

           $this->flash->addMessage('success', 'Profissional adicionado com sucesso.');
            return $this->httpRedirect($request, $response, '/admin/professionals');
        }


    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $professional = $this->professionalModel->get($id);

        //$professional = $this->entityFactory->createProfessional($professional);

        if (isset($professional)) {
            $this->userModel->delete((int) $professional->id_user);
            $this->professionalModel->delete((int) $professional->id);
        }

        $this->flash->addMessage('success', 'Profissional removido com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/professionals');
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $professional = $this->professionalModel->get($id);
        if (!$professional) {
            $this->flash->addMessage('danger', 'Profissional não encontrado.');
            return $this->httpRedirect($request, $response, '/admin/professionals');
        }

        $professional_types = $this->professionalTypeModel->getAll();
            return $this->view->render($response, 'admin/professional/edit.twig', ['professional' => $professional, 'professional_types' => $professional_types,]);
    }

    public function history(Request $request, Response $response, array $args): Response {
        $id = intval($args['id']);
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : 0;
        $professional = $this->professionalModel->get($id);

        $event_logs = $this->eventLogModel->getByProfessional((int)$id,(int)$search);
        ///var_dump($event_logs);
        //die;
        foreach ($event_logs as $event_log) {
            if (($event_log->id_patient)!= null) {
                $patient = $this->patientModel->get((int)$event_log->id_patient);
                $event_log->patient_name = $patient->name;
                //var_dump($patient->name);die;
            } else {
                $event_log->patient_name = "- - - -";
            }
           //var_dump($event_log);

            $event_log->date = date("d/m/Y h:m", strtotime($event_log->date));
        }

        return $this->view->render($response, 'admin/professional/history.twig', ['professional' => $professional,'event_logs' => $event_logs, 'search' => $search]);
    }

    //download
    public function export(Request $request, Response $response)
     {
        /*$params = $request->getQueryParams();

        $professional_status =  (int)$params['professional_status'];
        //var_dump($params);
        //die;
            if ($professional_status  == 1) {
                 $professionals = $this->professionalModel->getAll();

                // var_dump($professionals);
                // die;
            } else {
            $professionals = $this->professionalModel->getAllByStatus($professional_status);
        }
            //var_dump($professionals);
            //die;*/
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : 0;
        $professionals = $this->professionalModel->getAll();
        $dir = getcwd();
        $html .= "
            <style>
                table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;
                }

                th, td {
                text-align: left;
                padding: 5px;
                line-height: 100%;
                }

                tr:nth-child(even) {
                background-color: #f2f2f2;
                }
            </style>
        ";

        $html .= "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <p style=' '>Fundação Waldyr Becker de Apoio ao Paciente com Câncer.</p>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Relatório de Pacientes Cadastrados</h3>
                <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>

            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
                <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                    
                <tr style='border-style:solid; border-width:1px; border-color:gray;'>
                    <th style='width: 30%; text-align:left;'>Nome</th>
                    <th style='width: 25%; text-align:left;'>Email</th>


                    <th style='width: 10%; text-align:left;'>DDD</th>
                    <th style='width: 10%; text-align:left;'>Telefone</th>
                    <th style='width: 10%; text-align:left;'>CEP</th>
                    <th style='width: 15%; text-align:left;'>Categoria</th>
                </tr>
        ";
        /* foreach ($professionals as $professional) {
            //var_dump($professional);
            //die;
            if ($professional_status  == 2) {
                 $professionals = $this->professionalModel->getAll();

            $professional = $this->entityFactory->createProfessional($professional);
        }
            //var_dump( $professional);
            //die;*/
        foreach ($professionals as $professional) {
            //var_dump( $professional);
            // $professional = $this->entityFactory->createProfessional($professional);
            $html .= "
            <tr>
                <td style='width: 30%; text-align:left;'>$professional->name</td>
                <td style='width: 25%; text-align:left;'>$professional->email</td>
                <td style='width: 10%; text-align:left;'>$professional->tel_area</td>
                <td style='width: 10%; text-align:left;'>$professional->tel_numero</td>
                <td style='width: 10%; text-align:left;'>$professional->end_cep</td>
                <td style='width: 15%; text-align:left;'>$professional->professional_type_name</td>
            </tr>";
        }
        //die;

        $html .= "</table> </div>";
    try {
        $mpdf = new \Mpdf\Mpdf([
            // 'orientation' => 'L',
            'default_font_size' => 9,
            'default_font' => 'arial',
            'tempDir' => __DIR__ . '/custom/temp/dir/path'
        ]);
        $mpdf->showImageErrors = true;
        $mpdf->WriteHTML($html);
        // Other code
        header('Content-Type: application/pdf');
        $mpdf->Output( );
    } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
        // Process the exception, log, print etc.
        echo $e->getMessage();
    }
        die;
    }


    public function export_history(Request $request, Response $response) {
        $id = (int)$request->getQueryParams()['id'];
        $params = $request->getQueryParams();
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : 0;
        $history_start =   $params['professional_start'];
        if ($history_start == "") {
          $history_start = "2000-01-01";
        }
        $history_finish =  $params['professional_finish'];
        if ($history_finish == "") {
            $history_finish = date("Y-m-d",strtotime("+1 day"));
            //var_dump($history_finish);die;
        }
        $professional = $this->professionalModel->get($id);
        $event_logs = $this->eventLogModel->getByProfessionalNamePatient((int)$id, $history_start, $history_finish,  (int)$search);
        $patients = $this->patientModel->getAll();
        // var_dump($params);die;

        $html .= "
            <style>
                table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;
                }

                th, td {
                text-align: left;
                padding: 5px;
                line-height: 100%;
                }

                tr:nth-child(even) {
                background-color: #f2f2f2;
                }
            </style>
        ";

        $html .= "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Registro do Profissional</h3>
                <p> <strong>Profissional:</strong> $professional->name </p>
                <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>

            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table>

            <tr>
                <th style='width: 10%; text-align:left;'>Data</th>
                <th style='width: 20%; text-align:left;'>Tipo</th>
                <th style='width: 40%; text-align:left;'>Descrição</th>
                <th style='width: 30%; text-align:left;'>Paciente</th>

            </tr>
        ";
        $total = 0;
        foreach ($event_logs as $event_log) {
            $total = $total+1;
            if (($event_log->id_patient != null)) {
                $patient = $this->patientModel->get((int)$event_log->id_patient);
                $event_log->patient_name = $patient->name;
            } else {
                $event_log->patient_name = " - - - - ";
            }
            //var_dump($event_log);
            //die;
            //$event_log = $this->entityFactory->createEventLog($event_log);
            $event_log->date = date("d/m/Y", strtotime($event_log->date));
            $html .="
            <tr>
                <td style='width: 10%; text-align:left;'>$event_log->date</td>
                <td style='width: 20%; text-align:left;'>$event_log->event_log_types_name</td>
                <td style='width: 40%; text-align:left;'>$event_log->description</td>
                <td style='width: 30%; text-align:left;'>$event_log->patient_name</td>

            </tr> ";

        }
        $html .= "</table> </div>";
        $html .="
            <div>
                <table>
                    <tr>
                    
                        <td style='width: 30%; text-align:right; font-weight: bold; font-size: 16px'>Total: $total</td>

                    </tr> 
                </table>
            </div>";
        try {
            $mpdf = new \Mpdf\Mpdf([
                'orientation' => 'L',
                'default_font_size' => 9,
                'default_font' => 'arial',
                'tempDir' => __DIR__ . '/custom/temp/dir/path'
            ]);
            $mpdf->setFooter('{PAGENO}');
            $mpdf->WriteHTML($html);
            // Other code
            //header('Content-Type: application/pdf');
            $mpdf->Output( );

        } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
            // Process the exception, log, print etc.
            echo $e->getMessage();
        }

        return  $response->withHeader('Content-Type', 'application/pdf');
    }


    public function export_history_attendance(Request $request, Response $response) {

        $id = (int)$request->getQueryParams()['id'];
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : 0;

        $params = $request->getQueryParams();

        $professional_start =   $params['professional_start'];
        if ($professional_start == '') {
            $professional_start = "2000-01-01";
        }

        $professional_finish =  $params['professional_finish'];
        if ($professional_finish == "") {
            $professional_finish = date("Y-m-d",strtotime("+1 day"));
        }
        $patients = $this->patientModel->getAll();
        $attendances = $this->attendanceModel->getAllByDate($professional_start, $professional_finish, $search);
        //var_dump($attendances);
        //die;
        $professional = $this->professionalModel->get($id);
        $event_logs = $this->eventLogModel->getByProfessional((int)$id, (int)$search);
        //var_dump($event_logs);die;
        //var_dump($attendance);
         //  die;

         $html .= "
            <style>
                table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;
                }

                th, td {
                text-align: left;
                padding: 5px;
                line-height: 100%;
                }

                tr:nth-child(even) {
                background-color: #f2f2f2;
                }
            </style>
        ";

        $html .= "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Registro de Atendimentos do Profissional</h3>
                <p> <strong>Profissional:</strong> $professional->name </p>
                <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>

            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table>

            <tr>
                <th style='width: 10%; text-align:left;'>Data / Hora</th>
                <th style='width: 30%; text-align:left;'>Paciente</th>
                <th style='width: 60%; text-align:left;'>Observações</th>

            </tr>
        ";

        $total = 0;
        foreach ($attendances as $attendance) {
            //var_dump($attendance);
            if ($attendance->id_professional == (int) $id) {
                $total = $total+1;
              $attendance->name_patient = $this->patientModel->get((int)$attendance->id_patient)->name;

             if ($attendance->attendance_day != "") {
                $attendance->attendance_day = date('d/m/Y', strtotime($attendance->attendance_day));
            }
            $html .="
            <tr>
                <td style='width: 100px; text-align:left;'>$attendance->attendance_day</td>
                <td style='width: 30%; text-align:left;'>$attendance->name_patient</td>
                <td style='width: 30%; text-align:left;'>$attendance->description</td>

            </tr> ";
            }


    }
        
        $html .= "</table> </div>";
        $html .="
            <div>
                <table>
                    <tr>
                    
                        <td style='width: 30%; text-align:right; font-weight: bold; font-size: 16px'>Total: $total</td>

                    </tr> 
                </table>
            </div>";
        try {
            $mpdf = new \Mpdf\Mpdf([
                'orientation' => 'L',
                'default_font_size' => 9,
                'default_font' => 'arial',
                'tempDir' => __DIR__ . '/custom/temp/dir/path'
            ]);
            $mpdf->setFooter('{PAGENO}');
            $mpdf->WriteHTML($html);
            // Other code
            //header('Content-Type: application/pdf');
            $mpdf->Output( );

        } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
            // Process the exception, log, print etc.
            echo $e->getMessage();
        }

        return  $response->withHeader('Content-Type', 'application/pdf');
    }



    public function update(Request $request, Response $response): Response
    {

        $data = $request->getParsedBody();
        $data['end_numero'] = (int) $data['end_numero'];
        $data['professional_status'] = (int) $data['professional_status'];

        $professional['id'] = (int) $data['id'];
        $professional['id_user'] = (int) $data['id_user'];
        $professional['id_professional_type'] = (int) $data['id_professional_type'];
        $professional['professional_status'] = (int) $data['professional_status'];
        //var_dump($professional);
        //die;
        $professional = $this->entityFactory->createprofessional($professional);
        //var_dump($professional);
        //die;
        $user = $data;
        $user['id'] = (int) $data['id_user'];

        $user_old = (array) $this->userModel->get($user['id']);
        $user_new = $user_old =  $user;
        $user_new = $this->entityFactory->createUser($user_new);

        $professional_return = $this->professionalModel->update($professional);
        $user_return = $this->userModel->update($user_new);
        //var_dump($professional);
        //die;
        // if it's all ok with updates, create event log
        if ( (($professional_return != null) || ($professional_return != false)) && ($user_return != null) || ($user_return != false)  ) {

            $eventLog['id_professional']         = $professional->id;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('edit_professional')->id;
            $eventLog['description'] = 'Profissional ' . $user->name .' atualizado';

            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);

            $this->flash->addMessage('success', 'Professional atualizado com sucesso.');
            return $this->httpRedirect($request, $response, '/admin/professionals');
        }



    }

    public function verifyUserByEmail (Request $request, Response $response) {
        $data = $request->getParsedBody();

        $return = $this->professionalModel->getByEmail((string)$data['email']);

        if ($return == false) {
            return "false";
        } else {
            return "true";
        }


    }
}
