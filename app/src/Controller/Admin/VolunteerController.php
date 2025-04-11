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



class VolunteerController extends Controller
{

    protected $volunteerModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;
    protected $userModel;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $volunteerModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->volunteerModel = $volunteerModel;
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
        } else {
            $page = 1;
        }
        if (!empty($params['search'])) {
            $search = (int)$params['search'];
        } else {
            $search = 1;

        }
        $search_name =  $params['search_name'] ? $params['search_name'] : '';

        $limit = 20;
        $offset = ($page - 1) * $limit;
        $volunteers = $this->volunteerModel->getAllIndex((int)$search, $search_name, $offset, $limit);
        // var_dump($volunteers);die;
        $amountVolunteers = $this->volunteerModel->getAmount((int)$search, $search_name);
        $amountPages = ceil($amountVolunteers->amount / $limit);

        return $this->view->render($response, 'admin/volunteer/index.twig', [
            'volunteers' => $volunteers,
            'page' => $page,
            'search' => $search,
            'search_name' => $search_name,
            'amountPages' => $amountPages
            ]);
    }

    public function add(Request $request, Response $response): Response
    {
        if (empty($request->getParsedBody())) {
            return $this->view->render($response, 'admin/volunteer/add.twig', []);
        }

        $data = $request->getParsedBody();
        // var_dump($data);die;
        $data['name'] = $data['name'];
        $data['email'] = $data['email'] ? $data['email'] : null;
        $data['cpf'] =  $data['cpf'];
        $data['tel_area'] = $data['tel_area'] ? $data['tel_area'] : null;
        $data['tel_numero'] = $data['tel_numero'] ? $data['tel_numero'] : null;
        $data['end_cep'] =  $data['end_cep'];
        $data['end_rua'] = $data['end_rua'];
        $data['end_numero'] =  (int)$data['end_numero'] ? (int)$data['end_numero'] : null;
        $data['end_complemento'] = $data['end_complemento'];
        $data['end_bairro'] = $data['end_bairro'];
        $data['end_cidade'] = $data['end_cidade'];
        $data['end_estado'] = $data['end_estado'];
        $data['obs'] = $data['obs'];
        $data['status'] = $data['status'];
        $data['nascimento'] = $data['nascimento'] ? $data['nascimento'] : date('Y-m-d');
        if($data['email']) {
            if ($this->volunteerModel->getByEmail($data['email']) != false) {
                // var_dump($data['email']);die;
                $this->flash->addMessage('success', 'O email já existe. por favor cadastre um email único.');
                return $this->httpRedirect($request, $response, '/admin/voluntarios/add');
            }
        }
        $volunteer = $this->entityFactory->createVolunteer($data);
        $id_volunteer = $this->volunteerModel->add($volunteer);

        if ( ($id_volunteer != null) || ($id_volunteer != false) ) {
            $eventLog['id_volunteer']    = $id_volunteer;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('create_volunteer')->id;
            $eventLog['description'] = 'Voluntário ' . $volunteer->name .' cadastrado';

            $eventLog = $this->entityFactory->createEventLog($eventLog);

            $this->eventLogModel->add($eventLog);

           $this->flash->addMessage('success', 'Voluntário adicionado com sucesso.');
            return $this->httpRedirect($request, $response, '/admin/voluntarios');
        }


    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $volunteer = $this->volunteerModel->get($id);

        //$volunteer = $this->entityFactory->createvolunteer($volunteer);

        if (isset($volunteer)) {
            $this->userModel->delete((int) $volunteer->id_user);
            $this->volunteerModel->delete((int) $volunteer->id);
        }

        $this->flash->addMessage('success', 'Voluntário removido com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/voluntarios');
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $volunteer = $this->volunteerModel->get($id);
        if (!$volunteer) {
            $this->flash->addMessage('danger', 'Voluntário não encontrado.');
            return $this->httpRedirect($request, $response, '/admin/voluntarios');
        }

            return $this->view->render($response, 'admin/volunteer/edit.twig', ['volunteer' => $volunteer]);
    }

    public function history(Request $request, Response $response, array $args): Response {
        $id = intval($args['id']);
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : 0;
        $volunteer = $this->volunteerModel->get($id);

        $event_logs = $this->eventLogModel->getByVolunteer((int)$id,(int)$search);
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

        return $this->view->render($response, 'admin/volunteer/history.twig', ['volunteer' => $volunteer,'event_logs' => $event_logs, 'search' => $search]);
    }

    //download
    public function export(Request $request, Response $response)
     {
        $params = $request->getQueryParams();

        if (!empty($params['page'])) {
            $page = intval($params['page']);
        } else {
            $page = 1;
        }
        if (!empty($params['search'])) {
            $search = (int)$params['search'];
        } else {
            $search = 0;

        }
        $search_name =  $params['search_name'] ? $params['search_name'] : '';
        $volunteers = $this->volunteerModel->getAllIndex((int)$search, $search_name);
            $dir = getcwd();
      $html = "
      <div style='width: 24%; float:left;'>
        <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
      </div>
      <div style='width: 75%;'>
        <p style=' '>Fundação Waldyr Becker de Apoio ao Paciente com Câncer.</p>
        <h3 style='margin-top: 2px; margin-bottom: 2px;'>Relatório de Voluntários Cadastrados</h3>
        <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>
      </div>
      <hr>
      <div style='width:100%; margin-top: 10px;'>
      <table>
            <tr>
                <th style='width: 75%; text-align:left;'>Nome</th>
                <th style='width: 25%; text-align:left;'>Telefone</th>
            </tr>
        ";
        /* foreach ($volunteers as $volunteer) {
            //var_dump($volunteer);
            //die;
            if ($volunteer_status  == 2) {
                 $volunteers = $this->volunteerModel->getAll();

            $volunteer = $this->entityFactory->createvolunteer($volunteer);
        }
            //var_dump( $volunteer);
            //die;*/
        foreach ($volunteers as $volunteer) {
            //var_dump( $volunteer);
            //$volunteer = $this->entityFactory->createVolunteer($volunteer);
            $html .= "
            <tr>
            <td style='width: 75%; text-align:left;'>$volunteer->name</td>";
            if($volunteer->tel_numero != null) {
                $html .= "<td style='width:  25%; text-align:left;'>($volunteer->tel_area) $volunteer->tel_numero</td>";
            } else {
                $html .= "<td style='width:  25%; text-align:left;'>(--) ---------</td>";
            }
            $html .= "</tr>";
        }
        //die;

        $html .= "</table> </div>";
    try {
        $mpdf = new \Mpdf\Mpdf();
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
        $history_start =   $params['volunteer_start'];
        if ($history_start == "") {
          $history_start = "2000-01-01";
        }
        $history_finish =  $params['volunteer_finish'];
        if ($history_finish == "") {
            $history_finish = date("Y-m-d",strtotime("+1 day"));
            //var_dump($history_finish);die;
        }
        $volunteer = $this->volunteerModel->get($id);
        $event_logs = $this->eventLogModel->getByVolunteerNamePatient((int)$id, $history_start, $history_finish,  (int)$search);
        $patients = $this->patientModel->getAll();
        //var_dump($params);
        //die;

        $html = "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Registro do Voluntário</h3>
                <p> <strong>Voluntário:</strong> $volunteer->name </p>
                <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>

            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table>

            <tr>
                <th style='width: 33%; text-align:left;'>Data</th>
                <th style='width: 33%; text-align:left;'>Tipo</th>
                <th style='width: 33%; text-align:left;'>Descrição</th>
                <th style='width: 30%; text-align:left;'>Paciente</th>

            </tr>
        ";
        foreach ($event_logs as $event_log) {
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
                <td style='width: 20%; text-align:left;'>$event_log->date</td>
                <td style='width: 30%; text-align:left;'>$event_log->event_log_types_name</td>
                <td style='width: 50%; text-align:left;'>$event_log->description</td>
                <td style='width: 30%; text-align:left;'>$event_log->patient_name</td>

            </tr> ";

        }
        $html .= "</table> </div>";
        try {
            $mpdf = new \Mpdf\Mpdf();
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

        $volunteer_start =   $params['volunteer_start'];
        if ($volunteer_start == '') {
            $volunteer_start = "2000-01-01";
        }

        $volunteer_finish =  $params['volunteer_finish'];
        if ($volunteer_finish == "") {
            $volunteer_finish = date("Y-m-d",strtotime("+1 day"));
        }
        $patients = $this->patientModel->getAll();
        $attendances = $this->attendanceModel->getAllByDate($volunteer_start, $volunteer_finish, $search);
        //var_dump($attendances);
        //die;
        $volunteer = $this->volunteerModel->get($id);
        $event_logs = $this->eventLogModel->getByVolunteer((int)$id, (int)$search);
        //var_dump($event_logs);die;
        //var_dump($attendance);
         //  die;

        $html = "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Registro de Atendimentos do Voluntário</h3>
                <p> <strong>Voluntário:</strong> $volunteer->name </p>
                <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>

            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table>

            <tr>
                <th style='width: 100px; text-align:left;'>Data / Hora</th>
                <th style='width: 30%; text-align:left;'>Paciente</th>
                <th style='width: 50%; text-align:left;'>Observações</th>

            </tr>
        ";
        foreach ($attendances as $attendance) {
            //var_dump($attendance);
            if ($attendance->id_volunteer == (int) $id) {
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
        try {
            $mpdf = new \Mpdf\Mpdf();
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
        $volunteer['id'] = (int) $data['id'];
        $volunteer['name'] = $data['name'];
        $volunteer['email'] = $data['email'] ? $data['email'] : null;
        $volunteer['nascimento'] = $data['nascimento'] ? $data['nascimento'] : date('Y-m-d');
        $volunteer['cpf'] =  $data['cpf'];
        $volunteer['tel_area'] = $data['tel_area'] ? $data['tel_area'] : null;
        $volunteer['tel_numero'] = $data['tel_numero'] ? $data['tel_numero'] : null;
        $volunteer['end_cep'] =  $data['end_cep'];
        $volunteer['end_rua'] = $data['end_rua'];
        $volunteer['end_numero'] =  (int)$data['end_numero'] ? (int)$data['end_numero'] : null;
        $volunteer['end_complemento'] = $data['end_complemento'];
        $volunteer['end_bairro'] = $data['end_bairro'];
        $volunteer['end_cidade'] = $data['end_cidade'];
        $volunteer['end_estado'] = $data['end_estado'];
        $volunteer['obs'] = $data['obs'];
        $volunteer['status'] = $data['status'];
        // var_dump($volunteer);die;
        
        $volunteer = $this->entityFactory->createVolunteer($volunteer);
        $volunteer_return = $this->volunteerModel->update($volunteer);
        //var_dump($volunteer);
        //die;
        // if it's all ok with updates, create event log
        if ( $volunteer_return != false) {

            $eventLog['id_volunteer']         = $volunteer->id;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('edit_volunteer')->id;
            $eventLog['description'] = 'Voluntário ' . $volunteer->name .' atualizado';

            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);

            $this->flash->addMessage('success', 'Voluntário atualizado com sucesso.');
            return $this->httpRedirect($request, $response, '/admin/voluntarios');
        }



    }

    public function verifyUserByEmail (Request $request, Response $response) {
        $data = $request->getParsedBody();

        $return = $this->volunteerModel->getByEmail((string)$data['email']);
        // var_dump($return);die;
        if ($return == false) {
            return "false";
        } else {
            return "true";
        }


    }
}
