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
use Mpdf\Mpdf;
use Slim\Flash\Messages as FlashMessages;
use Slim\Views\Twig as View;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class PatientController extends Controller
{

    protected $patientModel;
    protected $diseaseModel;
    protected $patientStatusModel;
    protected $userModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $patientModel,
        Model $diseaseModel,
        Model $patientStatusModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->patientModel         = $patientModel;
        $this->diseaseModel         = $diseaseModel;
        $this->patientStatusModel   = $patientStatusModel;
        $this->userModel            = $userModel;
        $this->eventLogModel        = $eventLogModel;
        $this->eventLogTypeModel    = $eventLogTypeModel;
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {

        $params = $request->getQueryParams();

        if (!empty($params['page'])) {
            $page = intval($params['page']);
        } else {
            $page = 1;
        }
        $limit = 20;
        $offset = ($page - 1) * $limit;

       
        $patients = $this->patientModel->getAll($offset, $limit);
        $patient_status = $this->patientStatusModel->getAll();

        $amountPatients = $this->patientModel->getAmount();
        $amountPages = ceil($amountPatients->amount / $limit);

        $today = date('Y-m-d');

        return $this->view->render($response, 'admin/patient/index.twig', [
            'patients' => $patients,
            'patient_status' => $patient_status,
            'page' => $page,
            'amountPages' => $amountPages,
            'today' => $today
            ]);

        
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

        $data['tel_area'] = (int) $data['tel_area'];
        $data['tel_numero'] = (int) $data['tel_numero'];
        $data['end_numero'] = (int) $data['end_numero'];
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
        //var_dump($patients_start);
        //die;
        $patients_finish =  $params['patients_finish'];

        if ($patients_status == 0) {

            $patients = $this->patientModel->getAllByDate($patients_start, $patients_finish);

        } else {
            $patients = $this->patientModel->getAllByStatus($patients_status, $patients_start, $patients_finish);
        }


      $html = "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <p style=' '>Fundação Waldyr Becker de Apoio ao Paciente com Câncer.</p>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Relatório de Pacientes Cadastrados</h3>
                <p> <strong>Data relatório:</strong>  " . date("d-m-Y") . " </p>
            
            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table>
            
                <tr>
                    <th style='width: 20%; text-align:left;'>Nome</th>
                    <th style='width: 10%; text-align:left;'>Entrada</th>
                    <th style='width: 10%; text-align:left;'>Nascimento</th>
                    <th style='width: 10%; text-align:left;'>Telefone</th>
                    <th style='width: 10%; text-align:left;'>Situacao</th>
                </tr>
        ";
        foreach ($patients as $patient) {
           // var_dump($patient->name);
            //die;
            
            if ($patient->nascimento != "") {
                $patient->nascimento = date('d/m/Y', strtotime($patient->nascimento));
            }
            
            $html .= "
                <tr>
                <td style='width: 20%;'>$patient->name</td>
                <td style='width: 10%;'>" . date('d/m/Y', strtotime($patient->visitDate)) . "</td>
                <td style='width: 10%;'>$patient->nascimento</td>
                
                <td style='width: 10%;'>($patient->tel_area) $patient->tel_numero</td>
                <td style='width: 10%;'>$patient->status_name</td>
                
                </tr>";
        }
    
    $html .= "</table> </div>";
    try {
        $mpdf = new \Mpdf\Mpdf();
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

    public function export_history(Request $request, Response $response) {
        
        $id = (int)$request->getQueryParams()['id'];
        //var_dump($id);
        //die;
        $patient = $this->patientModel->get($id);
        $event_logs = $this->eventLogModel->getByPatient($id);
        $html = "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Registro do Paciente</h3>
                <p> <strong>Paciente:</strong> $patient->name </p>
                <p> <strong>Data relatório:</strong>  " . date("d-m-Y") . " </p>
            
            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table>
            
            <tr>
                <th style='width: 33%; text-align:left;'>Data / Hora</th>
                <th style='width: 33%; text-align:left;'>Tipo</th>
                <th style='width: 33%; text-align:left;'>Descrição</th>
                
            </tr>
        ";
        foreach ($event_logs as $event_log) {
            //var_dump($event_log);
           // die;
            $event_log->date = date("d/m/Y h:m:s", strtotime($event_log->date));
            $html .="
            <tr>
                <td style='width: 33%; text-align:left;'>$event_log->date</td>
                <td style='width: 33%; text-align:left;'>$event_log->event_log_types_name</td>
                <td style='width: 33%; text-align:left;'>$event_log->description</td>
            
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

    public function history (Request $request, Response $response, array $args)
    {
        $id = intval($args['id']);
        $patient = $this->patientModel->get($id);

        $event_logs = $this->eventLogModel->getByPatient($id);
        foreach ($event_logs as $event_log) {
            //var_dump($event_log);
           // die;
            $event_log->date = date("d/m/Y h:m", strtotime($event_log->date));
        }
        //$today = date('d-m-Y');
        //var_dump($event_logs);
        //die;

        return $this->view->render($response, 'admin/patient/history.twig', [

            'patient' => $patient,
            'event_logs' => $event_logs]);
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

        $patient = $this->entityFactory->createPatient($patient);

        $user = $data;
        $user['id'] = $data['id_user'];

        $user = $this->entityFactory->createUser($user);


        $patient_return = $this->patientModel->update($patient);
        $user_return = $this->userModel->update($user);
        var_dump($user->name);
        die;
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
