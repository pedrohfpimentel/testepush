<?php
declare(strict_types=1);

namespace Farol360\Ancora\Controller\Admin;

use Farol360\Ancora\Controller;
use Farol360\Ancora\Model;
use Farol360\Ancora\User;
use Farol360\Ancora\Model\EntityFactory;
use Shuchkin\SimpleXLSXGen;
use Fusonic\SpreadsheetExport\Spreadsheet;
use Fusonic\SpreadsheetExport\ColumnTypes\DateColumn;
use Fusonic\SpreadsheetExport\ColumnTypes\NumericColumn;
use Fusonic\SpreadsheetExport\ColumnTypes\TextColumn;
use Fusonic\SpreadsheetExport\Writers\CsvWriter;
use Fusonic\SpreadsheetExport\Writers\OdsWriter;
use Mpdf\Mpdf;
use Slim\Flash\Messages as FlashMessages;
use Slim\Views\Twig as View;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class PatientController extends Controller
{
    protected $attendanceModel;
    protected $patientModel;
    protected $diseaseModel;
    protected $patientStatusModel;
    protected $professionalModel;
    protected $remessaModel;
    protected $produtoRemessaModel;
    protected $productsModel;
    protected $userModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $attendanceModel,
        Model $patientModel,
        Model $diseaseModel,
        Model $patientStatusModel,
        Model $professionalModel,
        Model $remessaModel,
        Model $produtoRemessaModel,
        Model $productsModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->attendanceModel      = $attendanceModel;
        $this->patientModel         = $patientModel;
        $this->diseaseModel         = $diseaseModel;
        $this->patientStatusModel   = $patientStatusModel;
        $this->professionalModel    = $professionalModel;
        $this->remessaModel         = $remessaModel;
        $this->produtoRemessaModel  = $produtoRemessaModel;
        $this->productsModel        = $productsModel;
        $this->userModel            = $userModel;
        $this->eventLogModel        = $eventLogModel;
        $this->eventLogTypeModel    = $eventLogTypeModel;
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $page = !empty($params['page']) ? $params['page'] : 1;
        $status = !empty($params['patients_status']) ? $params['patients_status'] : 0;
        $order = !empty($params['ordem']) ? $params['ordem'] : 1;
        $search = !empty($params['search']) ? htmlspecialchars($params['search']) : '';
        $start = !empty($params['patients_start']) ? $params['patients_start'] : "2000-01-01";
        $finish = !empty($params['patients_finish']) ? $params['patients_finish'] : date("Y-m-d", strtotime("+ 1 day"));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $patients = $this->patientModel->getPatientsByName($start, $finish, $search, $status, $order, $offset, $limit);
        // var_dump($patients);die;
        $amountPatients = $this->patientModel->getAmountName($start, $finish, $search, $status);
        $patient_status = $this->patientStatusModel->getAll();
        $amountPages = ceil($amountPatients->amount / $limit);
        $today = date('Y-m-d');
        return $this->view->render($response, 'admin/patient/index.twig', [
            'patients' => $patients,
            'patient_status' => $patient_status,
            'page' => $page,
            'amountPages' => $amountPages,
            'status_param' => $status,
            'today' => $today,
            'start' => $start,
            'finish' => $finish,
            'order' => $order,
            'search' => $search,
            'timestamp' => time(),
            ]);
    }


    /*
        return:
        data.codigo = 1 - parametro cpf não foi informado.
        data.codigo = 2 - cpf é inválido.
        data.codigo = 3 - usuário encotrado.
        data.codigo = 4 - usuário encotrado.
    */
    public function validate_cpf(Request $request, Response $response): Response
    {
        $cpf = isset($request->getQueryParams()['cpf']) ? $request->getQueryParams()['cpf'] : null;

        // query param cpf deve ser preenchido
        if (($cpf === null) || ($cpf == '') ) {
            $data['codigo'] = 1;
            $data['mensagem'] = "Parametro CPF não informado.";
            return $response->withJson($data);
        }

        // cpf NÃO É válido
        if (!User::validaCPF($cpf)) {
            $data['codigo'] = 2;
            $data['mensagem'] = "O Cpf não é válido.";
            return $response->withJson($data);
        }

        // limpa a mascara do input
        $cpf = preg_replace("/[^0-9]/", "", $cpf);

        // buscar usuário
        $user = $this->patientModel->getByCpf($cpf);

        // verifica se foi encontrado usuário
        if ($user !== false) {
            $data['codigo'] = 3;
            $data['mensagem'] = "O usuário já existe.";
            return $response->withJson($data);
        }

        // retorna que está liberado
        $data['codigo'] = 4;
        $data['mensagem'] = "Usuário liberado para cadastro.";
        return $response->withJson($data);
    }


    public function add(Request $request, Response $response): Response
    {
        if (empty($request->getParsedBody())) {
            $diseases = $this->diseaseModel->getAll();
            $patient_status = $this->patientStatusModel->getAll();
            return $this->view->render($response, 'admin/patient/add.twig', ['diseases' => $diseases, 'patient_status' => $patient_status]);
        }

        //var_dump($request->getParsedBody());
        //die;

        // get the body and parse it to an array
        $data = $request->getParsedBody();

        // set manual data. change this on the future.
        $data['password'] = '1234';
        $data['role_id'] = 5;


        // verify email
        if ($this->patientModel->getByEmail($data['email']) != false) {
            $this->flash->addMessage('success', 'O email já existe. por favor cadastre um email único.');
            return $this->httpRedirect($request, $response, '/admin/patients/add');
        }

        // verify cpf null or empty
        if (!isset($data['cpf']) || $data['cpf'] == '') {
            $this->flash->addMessage('warning', 'O cpf deve ser informado.');
            return $this->httpRedirect($request, $response, '/admin/patients/add');
        }

        // verify cpf valido
        if (!User::validaCPF($data['cpf'])) {
            $this->flash->addMessage('warning', 'O cpf é inválido.');
            return $this->httpRedirect($request, $response, '/admin/patients/add');
        }

        $user = $this->patientModel->getByCpf($data['cpf']);

        if ($user !== false ) {
            $this->flash->addMessage('warning', 'O cpf já existe.');
            return $this->httpRedirect($request, $response, '/admin/patients/add');
        }

        $data['tel_area'] = (int) $data['tel_area'];
        $data['tel_numero'] = (int) $data['tel_numero'];
        $data['end_numero'] = (int) $data['end_numero'];
        $data['password'] = '1234';
        $user = $this->entityFactory->createUser($data);

        // add new user
        $patient['id_user'] = $this->userModel->add($user);

        // set patient type manual;
        $patient['id_patient_type'] = 1;

        // set disease
        $patient['id_disease'] = (int) $data['id_disease'];

        $patient['tel_area_2'] = (int) $data['tel_area_2'];

        $patient['tel_numero_2'] = (int) $data['tel_numero_2'];

        $patient['obs_tel'] = $data['obs_tel'];

        $patient['rg'] = $data['rg'];

        $patient['sus'] = $data['sus'];
        $patient['id_status'] = (int) $data['id_status'];
        $patient['obs'] = $data['obs'];
        $patient['cancer_type'] = $data['cancer_type'];
        $patient['discovery_time'] = $data['discovery_time'];
        $patient['discovery_how'] = $data['discovery_how'];
        $patient['treatment_time'] = $data['treatment_time'];
        $patient['treatment_where'] = $data['treatment_where'];
        $patient['doctor_name'] = $data['doctor_name'];
        $patient['fundation_need'] = $data['fundation_need'];
        $patient['visitDate'] = $data['visitDate'];
        $patient['registration_date'] = $data['registration_date'];

        // var_dump($patient);die;


        $patient = $this->entityFactory->createPatient($patient);


        ($id_patient = $this->patientModel->add($patient));

        // create eventLog when add patient
        if ( ($id_patient != null) || ($id_patient != false) )
        {
            $eventLog['id_patient']         = $id_patient;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('create_patient')->id;
            $eventLog['description'] = 'Paciente ' . $user->name .' cadastrado';

            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);

            $this->flash->addMessage('success', 'Paciente adicionado com sucesso.');
            return $this->httpRedirect($request, $response, '/admin/patients');
        }


    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);

        // busca paciente no banco
        $patient = $this->patientModel->get($id);

        // se existir, deleta
        if (isset($patient)) {

            $this->userModel->delete((int) $patient->id_user);
            $this->patientModel->delete((int) $patient->patient_id);
        }


        $this->flash->addMessage('success', 'Paciente removido com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/patients');
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $patient = $this->patientModel->get($id);

        if (!$patient) {
            $this->flash->addMessage('danger', 'Paciente não encontrado.');
            return $this->httpRedirect($request, $response, '/admin/patients');
        }

        $diseases = $this->diseaseModel->getAll();
        $patient_status = $this->patientStatusModel->getAll();
        return $this->view->render($response, 'admin/patient/edit.twig', ['patient' => $patient, 'diseases' => $diseases, 'patient_status' => $patient_status]);
    }


    //download
    public function export(Request $request, Response $response)
    {

        $params = $request->getQueryParams();
        $patients_status =  (int)$params['patients_status'];
        $patients_start =   $params['patients_start'];
        if ($patients_start == "") {
            $patients_start = "2000-01-01";
        }
        $patients_finish =  $params['patients_finish'];
        $search = !empty($params['search']) ? htmlspecialchars($params['search']) : '';
        $order = !empty($params['ordem']) ? $params['ordem'] : 1;
        
        $amountPatients = $this->patientModel->getAmountName($patients_start, $patients_finish, '', $patients_status);
        $amountAttendance = $this->attendanceModel->getAmountByDate($patients_start, $patients_finish, '');

        $patients = $this->patientModel->getPatientsByName($patients_start, $patients_finish, $search, $patients_status, $order);
        // if ($patients_status == 0) {
        //     $patients = $this->patientModel->getAllByDate($patients_start, $patients_finish);
        // } else {
        //     $patients = $this->patientModel->getAllByStatus($patients_status, $patients_start, $patients_finish);
        // }

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
                        <th style='width: 45%; text-align:left; '>Nome</th>
                        <th style='width: 10%; text-align:left;'>Entrada</th>
                        <th style='width: 10%; text-align:left;'>Nascimento</th>
                        <th style='width: 15%; text-align:left;'>Telefone</th>
                        <th style='width: 10%; text-align:left;'>Situação</th>
                        <th style='width: 10%; text-align:left;'>Total de Atendimentos</th>
                    </tr>
        ";
        foreach ($patients as $patient) {
            // var_dump($patient->id);die;
            $attendance = $this->attendanceModel->getAmountByPatient((int)$patient->id, $patients_start, $patients_finish);
            if ($patient->nascimento != "") {
                $patient->nascimento = date('d/m/Y', strtotime($patient->nascimento));
            }
            $patient->data_visita = $patient->visitDate ? date('d/m/Y', strtotime($patient->visitDate)) : '-----';

            $html .= "
                <tr>
                    <td style='width: 45%;'>$patient->name</td>
                    <td style='width: 10%;'>$patient->data_visita</td>
                    <td style='width: 10%;'>$patient->nascimento</td>

                    <td style='width: 15%;'>($patient->tel_area) $patient->tel_numero</td>
                    <td style='width: 10%;'>$patient->status_name</td>
                    <td style='width: 10%;'>$attendance->amount</td>

                </tr>";
        }
        
    $html .= "</table> </div>";
    $html.="
        <table align='right' style='width:30%;background-color:#f2f2f2; padding-top: 15px'>

            <tr style=' '>
            <th style='width: 20%; text-align:left;'>Total de Atendimentos</th>
            <th style='width: 30%; text-align:right;'>$amountAttendance->amount </th>
            </tr>
            <tr style=' '>
            <th style='width: 20%; text-align:left;'>Total de Pacientes</th>
            <th style='width: 30%; text-align:right;'>$amountPatients->amount </th>
            </tr>
            
        </table>";
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
        header('Content-Type: application/pdf');
        $mpdf->Output( );
    } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
        // Process the exception, log, print etc.
        echo $e->getMessage();
    }
        die;
    }

    public function export_xlsx(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $patients_status =  (int)$params['patients_status'];
        $patients_start =   $params['patients_start'];
        if ($patients_start == "") {
            $patients_start = "2000-01-01";
        }
        $patients_finish =  $params['patients_finish'];
        if ($patients_finish == "") {
            $patients_finish = date('Y-m-d');
        }
        $search = !empty($params['search']) ? htmlspecialchars($params['search']) : '';
        $order = !empty($params['ordem']) ? $params['ordem'] : 1;
        // var_dump($patients_finish);die;
        $amountPatients = $this->patientModel->getAmountName($patients_start, $patients_finish, '', $patients_status);
        $amountAttendance = $this->attendanceModel->getAmountByDate($patients_start, $patients_finish, '');

        $patients = $this->patientModel->getPatientsByName($patients_start, $patients_finish, $search, $patients_status, $order);
          
        $data = [
            ['Nome', 'Data de Entrada', 'Data de Nascimento', 'Telefone', 'Situação' , 'Total de Atendimentos do Paciente']
        ];

        foreach ($patients as $key => $patient) {
            $attendance = $this->attendanceModel->getAmountByPatient((int)$patient->id, $patients_start, $patients_finish);
            // var_dump($patient->tel_area);die;
            // $key;
            $array_push = [
            $patient->name,
            $patient->visitDate,
            $patient->nascimento,
            $patient->tel_area.'-'.$patient->tel_numero,
            $patient->status_name,
            $attendance->amount,
            ];
        
            array_push($data, $array_push);
        }
        
        array_push($data, [],['Total Geral de Atendimentos: ' . $amountAttendance->amount ,'Total de Pacientes' .  $amountPatients->amount]);
        
        $xlsx = SimpleXLSXGen::fromArray( $data );
        $xlsx->downloadAs('Pacientes-' . time() .'.xlsx');
        
    }

    public function export_history(Request $request, Response $response) {

        $id = (int)$request->getQueryParams()['id'];
        $params = $request->getQueryParams();
        //var_dump($params);die;
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : 0;

        $history_start =   $params['history_start'];
        if ($history_start == "") {
          $history_start = "2000-01-01";
        }
        $history_finish =  $params['history_finish'];
        if ($history_finish == "") {
            $history_finish = date("Y-m-d",strtotime("+1 day"));
            //var_dump($history_finish);die;
        }
        //var_dump($params);
        //die;
        $patient = $this->patientModel->get($id);
        $event_logs = $this->eventLogModel->getByPatientExport((int)$id, $history_start, $history_finish, (int)$search);
        //var_dump($event_logs);
        //die;
        $products_remessa = $this->produtoRemessaModel->getAll();
        $professionals = $this->professionalModel->getAll();

        $html = "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Registro do Paciente</h3>
                <p> <strong>Paciente:</strong> $patient->name </p>
                <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>

            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table>

            <tr>
                <th style='width: 20%; text-align:left;'>Data Remessa/Atendimento</th>
                <th style='width: 20%; text-align:center;'>Data de Cadastro</th>
                <th style='width: 25%; text-align:left;'>Tipo</th>
                <th style='width: 25%; text-align:left;'>Descrição</th>
                <th style='width: 30%; text-align:right;'>Produto/Profissional</th>

            </tr>
        ";
        foreach ($event_logs as $event_log) {
            $event_log->product_name = "";
            $event_log->attendance_date = "";
            if (($event_log->id_remessa) != NULL) {
                $remessa = $event_log->id_remessa;
                $remessa_atual = $this->remessaModel->get((int)$event_log->id_remessa);
                if ($remessa_atual != null) {
                    $event_log->data_remessa = date("d/m/Y", strtotime($remessa_atual->date));
                }
                $products_remessa = $this->produtoRemessaModel->getAllByRemessa((int)$remessa);
                //var_dump($products_remessa);
                foreach ($products_remessa as $product_remessa) {
                    $name_product = $product_remessa->name_product;
                    $event_log->product_name = $event_log->product_name . $name_product . "; ";
                    //var_dump($event_log);
                    //var_dump($event_log->product_name);
                }
                //die;
            }
            if (($event_log->id_professional) != NULL) {
                $professional_id = $event_log->id_professional;
                $professional = $this->professionalModel->get((int)$professional_id);
                $professional_name = $professional->name;
                $event_log->professional_name = $professional_name;
                $attendance_atual = $this->attendanceModel->get((int)$event_log->id_attendance);
                if ($attendance_atual != false) {
                    $event_log->attendance_date = date("d/m/Y", strtotime($attendance_atual->attendance_day));
                    //var_dump($event_log->attendance_date);die;
                }
            }
            // var_dump($event_log);die;
            $event_log->date = date("d/m/Y", strtotime($event_log->date));
            $html .="
            <tr> ";
            if (($event_log->id_remessa) != NULL) {
                $html .=" <td style='width: 33%; text-align:center;'>$event_log->data_remessa</td>
            </tr> ";
            } else if (($event_log->id_professional) != NULL) {
                $html .=" <td style='width: 33%; text-align:center;'>$event_log->attendance_date</td>
            </tr> ";
            } else {
                $html .=" <td style='width: 33%; text-align:center;'>- - - -</td>
            </tr> ";
            }
            $html .="
                <td style='width: 33%; text-align:center;'>$event_log->date</td>
                <td style='width: 33%; text-align:left;'>$event_log->event_log_types_name</td>
                <td style='width: 33%; text-align:left;'>$event_log->description</td>";
            if (($event_log->id_remessa) != NULL) {
                $html .=" <td style='width: 33%; text-align:right;'>$event_log->product_name</td>
            </tr> ";
            }
            else if (($event_log->id_professional) != NULL) {
                $html .=" <td style='width: 33%; text-align:right;'>$event_log->professional_name</td>
            </tr> ";
            }
            else {
                $html .=" <td style='width: 33%; text-align:right;'>- - - -
            </tr> ";
            }
        }
        $html .= "</table> </div>";
        try {
            $mpdf = new \Mpdf\Mpdf([
                // 'orientation' => 'L',
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
        $params = $request->getQueryParams();
        //var_dump($params);die;
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : 0;

        $history_start =   $params['history_start'];
        if ($history_start == "") {
          $history_start = "2000-01-01";
        }
        $history_finish =  $params['history_finish'];
        if ($history_finish == "") {
            $history_finish = date("Y-m-d",strtotime("+1 day"));
        }

        $professionals = $this->professionalModel->getAll();
        $attendances = $this->attendanceModel->getAllByDate($history_start, $history_finish, $search);
        $patient = $this->patientModel->get($id);
        $event_logs = $this->eventLogModel->getByPatient((int)$id, (int)$search);

        $html = "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Registro de Atendimentos do Paciente</h3>
                <p> <strong>Paciente:</strong> $patient->name </p>
                <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>

            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table>

            <tr>
                <th style='width: 20%; text-align:left;'>Data de Atendimento</th>
                <th style='width: 25%; text-align:left;'>Profissional</th>
                <th style='width: 25%; text-align:left;'>Descrição</th>

            </tr>
        ";

        foreach ($attendances as $attendance) {
            //var_dump($attendance);die;
            if ($attendance->id_patient == ((int) $id)) {
              $attendance->name_professional =  $this->professionalModel->get((int)$attendance->id_professional)->name;

             if ($attendance->attendance_day != "") {
                $attendance->attendance_day = date('d/m/Y', strtotime($attendance->attendance_day));
            }
            //var_dump($attendances);die;
            $html .="
            <tr>
                <td style='width: 100px; text-align:left;'>$attendance->attendance_day</td>
                <td style='width: 30%; text-align:left;'>$attendance->name_professional</td>
                <td style='width: 30%; text-align:left;'>$attendance->description</td>

            </tr> ";
            }
        }

        $html .= "</table> </div>";
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

    public function history (Request $request, Response $response, array $args)
    {
        $id = intval($args['id']);
        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : 0;
        $patient = $this->patientModel->get($id);
        $event_logs = $this->eventLogModel->getByPatient($id,(int)$search);
        $products_remessa = $this->produtoRemessaModel->getAll();
        $name_product = "";
        $professional_name = "";
        foreach ($event_logs as $event_log) {
            $event_log->product_name = "";
            if (($event_log->id_remessa) != NULL) {
                $remessa_atual = $this->remessaModel->get((int)$event_log->id_remessa);
                if ($remessa_atual != null) {
                    $event_log->data_remessa = date("d/m/Y", strtotime($remessa_atual->date));
                }
                $remessa = $event_log->id_remessa;
                $products_remessa = $this->produtoRemessaModel->getAllByRemessa((int)$remessa);
                //var_dump($products_remessa);
                foreach ($products_remessa as $product_remessa) {
                    $name_product = $product_remessa->name_product;
                    $event_log->product_name = $event_log->product_name . $name_product . "; ";
                    //var_dump($event_log);
                    //var_dump($event_log->product_name);
                }
                //die;
            }
            if (($event_log->id_professional) != NULL) {
                $professional_id = $event_log->id_professional;
                $professional = $this->professionalModel->get((int)$professional_id);
                $professional_name = $professional->name;
                $event_log->professional_name = $professional_name;
                $attendance_atual = $this->attendanceModel->get((int)$event_log->id_attendance);
                if ($attendance_atual != false) {
                    $event_log->attendance_date = $attendance_atual->attendance_day;
                }
            }
            $event_log->date = date("d/m/Y", strtotime($event_log->date));
        }
        //var_dump($attendance_atual->attendance_day);
        //var_dump($event_log->product_name);
        //die;
        return $this->view->render($response, 'admin/patient/history.twig', [
            'patient' => $patient,
            'products_remessa' => $products_remessa,
            'name_product' => $name_product,
            'professional_name' => $professional_name,
            'event_logs' => $event_logs,
            'search' => $search
        ]);
    }
    public function update(Request $request, Response $response): Response
    {

        $data = $request->getParsedBody();

        $patient['id'] = (int) $data['id'];
        $patient['id_user'] = (int) $data['id_user'];
        $patient['id_patient_type'] = 1;
        $patient['id_disease'] = (int) $data['id_disease'];
        $patient['tel_area_2'] = $data['tel_area_2'];
        $patient['tel_numero_2'] = $data['tel_numero_2'];
        $patient['obs_tel'] = $data['obs_tel'];
        $patient['rg'] = $data['rg'];
        $patient['sus'] = $data['sus'];

        $patient['id_status'] = (int) $data['id_status'];

        $patient['obs'] = $data['obs'];
        $patient['cancer_type'] = $data['cancer_type'];
        $patient['discovery_time'] = $data['discovery_time'];
        $patient['discovery_how'] = $data['discovery_how'];
        $patient['treatment_time'] = $data['treatment_time'];
        $patient['treatment_where'] = $data['treatment_where'];
        $patient['doctor_name'] = $data['doctor_name'];
        $patient['fundation_need'] = $data['fundation_need'];
        $patient['visitDate'] = $data['visitDate'];
        $patient['registration_date'] = $data['registration_date'];

        $patient = $this->entityFactory->createPatient($patient);

        $user = $data;
        $user['id'] = $data['id_user'];

        $user = $this->entityFactory->createUser($user);


        $patient_return = $this->patientModel->update($patient);
        $user_return = $this->userModel->update($user);
        //var_dump($user->name);
        //die;
        // if it's all ok with updates, create event log
        if ( (($patient_return != null) || ($patient_return != false)) && ($user_return != null) || ($user_return != false)  ) {

            $eventLog['id_patient']         = $patient->id;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('edit_patient')->id;
            $eventLog['description'] = 'Paciente ' . $user->name .' atualizado';

            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);

            $this->flash->addMessage('success', 'Paciente atualizado com sucesso.');
            return $this->httpRedirect($request, $response, '/admin/patients');
        }


    }

    public function verifyUserByEmail (Request $request, Response $response) {
        $data = $request->getParsedBody();


        $return = $this->patientModel->getByEmail((string)$data['email']);

        if ($return == false) {
            return "false";
        } else {
            return "true";
        }


    }
}
