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


class AttendanceController extends Controller
{

    protected $attendanceModel;
    protected $attendanceStatusModel;
    protected $patientModel;
    protected $productModel;
    protected $professionalModel;
    protected $remessaModel;
    protected $remessaStatusModel;
    protected $remessaTypeModel;
    protected $userModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;
    protected $entityFactory;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $attendanceModel,
        Model $attendanceStatusModel,
        Model $patientModel,
        Model $productModel,
        Model $professionalModel,
        Model $remessaModel,
        Model $remessaStatusModel,
        Model $remessaTypeModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->attendanceModel      = $attendanceModel;
        $this->attendanceStatusModel = $attendanceStatusModel;
        $this->patientModel         = $patientModel;
        $this->productModel         = $productModel;
        $this->professionalModel    = $professionalModel;
        $this->remessaModel         = $remessaModel;
        $this->remessaStatusModel   = $remessaStatusModel;
        $this->remessaTypeModel     = $remessaTypeModel;
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


        $attendances = $this->attendanceModel->getAll($offset, $limit);
        foreach ($attendances as $attendance) {
            $attendance->patient_name = $this->patientModel->get((int)$attendance->id_patient)->name;
            $attendance->professional_name = $this->professionalModel->get((int)$attendance->id_professional)->name;

            $attendance->attendance_day = date("d/m/Y", strtotime($attendance->attendance_day));
        }

        $amountAttendances = $this->attendanceModel->getAmount();
        $amountPages = ceil($amountAttendances->amount / $limit);

         $today = date('Y-m-d');

         //var_dump($attendance);
         //die;

        return $this->view->render($response, 'admin/attendance/index.twig', [

            'attendances' => $attendances,
            'page' => $page,
            'amountPages' => $amountPages,
            'today' => $today
            ]);
    }

    public function add(Request $request, Response $response): Response
    {



        if (empty($request->getParsedBody())) {

            // $patients       = $this->patientModel->getAll();

            $patients = $this->patientModel->getPatientsByName("2000-01-01", date("Y-m-d", strtotime("+ 1 day")), '', 1, 2);
            // var_dump($patients);die;
            $professionals  = $this->professionalModel->getAll();
            $status = $this->attendanceStatusModel->getAll();
            // var_dump($professionals);
            return $this->view->render($response, 'admin/attendance/add.twig', [
                'patients'      => $patients,
                'professionals' => $professionals,
                'status' => $status
            ]);
        }

        $data = $request->getParsedBody();

        $attendance = $this->entityFactory->createAttendance($data);

        $id_attendance = $this->attendanceModel->add($attendance);
        $attendance_status = $this->attendanceStatusModel->get((int)$attendance->status);
        // create eventLog when add attendance
        if ( ($id_attendance != null) || ($id_attendance != false) )
        {
            $eventLog['id_attendance']      = (int)$id_attendance;
            $eventLog['id_patient']         = $attendance->id_patient;
            $eventLog['id_professional']    = $attendance->id_professional;
            $eventLog['status']         = $attendance->status;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('attendance')->id;
            $eventLog['description'] = 'Atendimento criado com status: ' . $attendance_status->name;

            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);

            $this->flash->addMessage('success', 'Atendimento adicionado com sucesso.');
            return $this->httpRedirect($request, $response, '/admin/attendances');
        }


    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $patient = $this->patientModel->get($id);

        if (isset($patient)) {
            $this->userModel->delete((int) $patient->id_user);
            $this->patientModel->delete((int) $patient->id);
        }


        $this->flash->addMessage('success', 'Paciente removido com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/patients');
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
       // $attendance_status = $this->attendanceStatusModel->getAll();
        //$patients = $this->patientModel->getAll();
         //var_dump($patients);
        // die;

        //{
        //$id = intval($args['id']);
        //$attendance = $this->attendanceModel->get($id);
        //var_dump($attendance);
        //die;
        //$id_patient = $attendance->id_patient;
        //$id_professional = $attendance->id_professional;
        //$attendance->name_patient = $this->patientModel->get((int)$attendance->id_patient)->name;
        //$attendance->name_professional = $this->professionalModel->get((int)$attendance->id_professional)->name;
        //$attendance->attendance_day = date("d/m/Y", strtotime($attendance->attendance_day));
        //var_dump($attendance);
        //die;
        //if (!$attendance) {
          //  $this->flash->addMessage('danger', 'Atendimento não encontrado.');
            //return $this->httpRedirect($request, $response, '/admin/attendances');
//}


         //$attendance_status = $this->attendanceStatusModel->getAll();
           // return $this->view->render($response, 'admin/attendance/view.twig', ['attendance' => $attendance, 'attendance_status' => $attendance_status, 'id_patient' => $id_patient, 'id_professional' => $id_professional,]);

    //}
    }

    //download
    public function export(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $attendance_start =   $params['attendance_start'];
        if (($attendance_start == '') || ($attendance_start == NULL)) {
            $attendance_start = "2000-01-01";
        }
        $attendance_finish =  $params['attendance_finish'];
        // var_dump($attendance_finish);die;
        if (($attendance_finish == NULL) || ($attendance_finish == '')) {
            $attendance_finish = date("Y-m-d",strtotime("+1 day"));
        }
       // if ($patients_status == 0) {
        $attendances = $this->attendanceModel->getAllByDate($attendance_start, $attendance_finish, 1);
        $professionals = $this->professionalModel->getAll();
        $patients = $this->patientModel->getAll();
        foreach ($attendances as $attendance) {
            foreach($professionals as $professional) {
                if ($professional->id == $attendance->id_professional) {
                    $attendance->name_professional = $professional->name;
                }
            }
            foreach ($patients as $patient) {
                if ($patient->id == $attendance->id_patient) {
                    $attendance->name_patient = $patient->name;
                }
            }
        }
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
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Relatório de Atendimentos</h3>
                <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>
            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            
            <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                    
                <tr style='border-style:solid; border-width:1px; border-color:gray;'>
                    <th style='width: 10%;'>Data do Atendimento</th>
                    <th style='width: 10%;'>Hora</th>
                    <th style='width: 25%;'>Paciente</th>
                    <th style='width: 25%;'>Profissional</th>
                    <th style='width: 30%;'>Observações</th>

                </tr>
        ";
        //var_dump( $attendances);
         //   die;
         foreach ($attendances as $attendance) {

            if ($attendance->attendance_day != "") {
                $attendance->attendance_day = date('d/m/Y', strtotime($attendance->attendance_day));
            }

            $html .= "
            <tr>
                <td style='width: 15%;'>$attendance->attendance_day</td>
                <td style='width: 15%;'>$attendance->attendance_hour</td>
                <td style='width: 20%;'>$attendance->name_patient</td>
                <td style='width: 20%;'>$attendance->name_professional</td>
                <td style='width: 30%;'>$attendance->description</td>
            </tr>";
        }

        $html .= "</table> </div>";
        //var_dump($html);
        //die;
    try {
        $mpdf = new \Mpdf\Mpdf([
            // 'orientation' => 'L',
            'default_font_size' => 9,
            'default_font' => 'arial',
            'tempDir' => __DIR__ . '/custom/temp/dir/path'
        ]);
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

    //download
    public function export_data(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $attendance_start =   $params['attendance_start'];
        if ($attendance_start == '') {
            $attendance_start = "2000-01-01";
        }
        $attendance_finish =  $params['attendance_finish'];
       // if ($patients_status == 0) {
        $attendances = $this->attendanceModel->getAllByDate($attendance_start, $attendance_finish, 1);
        $professionals = $this->professionalModel->getAll();
        $patients = $this->patientModel->getAll();
        foreach ($attendances as $attendance) {
            foreach($professionals as $professional) {
                if ($professional['id'] == $attendance->id_professional) {
                    $attendance->name_professional = $professional['name'];
                }
            }
            foreach ($patients as $patient) {
                if ($patient->id == $attendance->id_patient) {
                    $attendance->name_patient = $patient->name;
                }
            }
        }
      $html = "
      <div style='width: 24%; float:left;'>
        <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
      </div>
      <div style='width: 75%;'>
        <p style=' '>Fundação Waldyr Becker de Apoio ao Paciente com Câncer.</p>
        <h3 style='margin-top: 2px; margin-bottom: 2px;'>Relatório de Atendimentos</h3>
        <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>
      </div>
      <hr>
      <div style='width:100%; margin-top: 10px;'>
      <table>

            <tr>
                <th style='width: 10%;'>Data do Atendimento</th>
                <th style='width: 10%;'>Hora</th>
                <th style='width: 25%;'>Paciente</th>
                <th style='width: 25%;'>Profissional</th>
                <th style='width: 30%;'>Observações</th>

            </tr>
        ";
        //var_dump( $attendances);
         //   die;
         foreach ($attendances as $attendance) {

            if ($attendance->attendance_day != "") {
                $attendance->attendance_day = date('d/m/Y', strtotime($attendance->attendance_day));
            }

            $html .= "
            <tr>
            <td style='width: 15%;'>$attendance->attendance_day</td>
            <td style='width: 15%;'>$attendance->attendance_hour</td>
            <td style='width: 20%;'>$attendance->name_patient</td>
            <td style='width: 20%;'>$attendance->name_professional</td>
            <td style='width: 30%;'>$attendance->description</td>
            </tr>";
        }

        $html .= "</table> </div>";
        //var_dump($html);
        //die;
    try {
        $mpdf = new \Mpdf\Mpdf();
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

    public function history (Request $request, Response $response, array $args)
    {
        $id = intval($args['id']);
        $patient = $this->patientModel->get($id);


        $event_logs = $this->eventLogModel->getByPatient($id);

        return $this->view->render($response, 'admin/patient/history.twig', ['patient' => $patient,
            'event_logs' => $event_logs]);
    }

    public function update(Request $request, Response $response): Response
    {
        $patients       = $this->patientModel->getAll();
        $professionals  = $this->professionalModel->getAll();
        $status = $this->attendanceStatusModel->getAll();
        $data = $request->getParsedBody();
        //var_dump($data);
        $attendance = $request->getParsedBody();
        $attendance['id'] = (int) $data['id'];
        $attendance['id_patient'] = (int) $data['id_patient'];
        $attendance['id_professional'] = (int) $data['id_professional'];
        $attendance['status'] = (int) $data['status'];
        $attendance['attendance_day'] = $data['attendance_day'];
        $attendance['attendance_hour'] = $data['attendance_hour'];
        $attendance['description'] = $data['description'];
        //var_dump($attendance);
        //die;
        $attendance = $this->entityFactory->createAttendance($attendance);
        //var_dump($attendance);
        //die;
        $attendance_return = $this->attendanceModel->update($attendance);
        //var_dump($attendance);
        //die;
        // if it's all ok with updates, create event log
        if  (($attendance_return != null) || ($attendance_return != false)) {
            $eventLog['id_attendance'] = (int) $data['id'];
            $eventLog['id_patient'] = (int) $data['id_patient'];
            $eventLog['id_professional'] = (int) $data['id_professional'];
            $eventLog['id_attendance']         = (int) $attendance->id;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('attendance_edit')->id;
            $eventLog['description'] = 'Atendimento atualizado';
            //var_dump($eventLog);
            //die;
            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);

            $this->flash->addMessage('success', 'Atendimento atualizado com sucesso.');
            return $this->httpRedirect($request, $response, '/admin/attendances');
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

    public function view(Request $request, Response $response, array $args): Response
    {


        // $patients       = $this->patientModel->getAll();

        $patients = $this->patientModel->getPatientsByName("2000-01-01", date("Y-m-d", strtotime("+ 1 day")), '', 1, 2);
        $professionals  = $this->professionalModel->getAll();
        $status = $this->attendanceStatusModel->getAll();

        $id = intval($args['id']);
        $attendance = $this->attendanceModel->get($id);
        //var_dump($attendance);
        //die;
        $id_patient = $attendance->id_patient;
        $id_professional = $attendance->id_professional;
        $attendance->name_patient = $this->patientModel->get((int)$attendance->id_patient)->name;
        $attendance->name_professional = $this->professionalModel->get((int)$attendance->id_professional)->name;
       // $attendance->attendance_day = date("d/m/Y", strtotime($attendance->attendance_day));
        //$attendance->attendance_hour = date("h:m", strtotime($attendance->attendance_hour));
        //var_dump($attendance);
        //die;
        if (!$attendance) {
            $this->flash->addMessage('danger', 'Atendimento não encontrado.');
            return $this->httpRedirect($request, $response, '/admin/attendances');
}


         $attendance_status = $this->attendanceStatusModel->getAll();
            return $this->view->render($response, 'admin/attendance/view.twig', [
                'attendance' => $attendance,
                'attendance_status' => $attendance_status,
                'id_patient' => $id_patient,
                'id_professional' => $id_professional,
                'patients' => $patients,
                'professionals' => $professionals,
            ]);

    }

}
