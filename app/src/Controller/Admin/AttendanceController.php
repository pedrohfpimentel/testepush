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
    protected $patientModel;
    protected $productModel;
    protected $professionalModel;
    protected $remessaModel;
    protected $remessaTypeModel;
    protected $userModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;
    protected $entityFactory;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $attendanceModel,
        Model $patientModel,
        Model $productModel,
        Model $professionalModel,
        Model $remessaModel,
        Model $remessaTypeModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->attendanceModel      = $attendanceModel;
        $this->patientModel         = $patientModel;
        $this->productModel         = $productModel;
        $this->professionalModel    = $professionalModel;
        $this->remessaModel         = $remessaModel;
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
        $limit = 3;
        $offset = ($page - 1) * $limit;

       
        $attendances = $this->attendanceModel->getAll($offset, $limit);
        foreach ($attendances as $attendance) {
            $attendance->patient_name = $this->patientModel->get((int)$attendance->id_patient)->name;
            $attendance->professional_name = $this->professionalModel->get((int)$attendance->id_professional)->name;
        }
               
        $amountAttendances = $this->attendanceModel->getAmount();
        $amountPages = ceil($amountAttendances->amount / $limit);

        return $this->view->render($response, 'admin/attendance/index.twig', [
            'attendances' => $attendances,
            'page' => $page,
            'amountPages' => $amountPages
            ]);
    }

    public function add(Request $request, Response $response): Response
    {
        if (empty($request->getParsedBody())) {

            $patients       = $this->patientModel->getAll();
            $professionals  = $this->professionalModel->getAll();

            return $this->view->render($response, 'admin/attendance/add.twig', [
                'patients'      => $patients,
                'professionals' => $professionals]);
        }

        $data = $request->getParsedBody();

        $attendance = $this->entityFactory->createAttendance($data);

        $id_attendance = $this->attendanceModel->add($attendance);

        //var_dump($attendance);
        //die;

        // create eventLog when add attendance
        if ( ($id_attendance != null) || ($id_attendance != false) )
        {
            $eventLog['id_patient']         = $attendance->id_patient;
            $eventLog['id_professional']    = $attendance->id_professional;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('attendance')->id;
            $eventLog['description'] = $attendance->description;

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
        $id = intval($args['id']);
        $patient = $this->patientModel->get($id);
        if (!$patient) {
            $this->flash->addMessage('danger', 'Paciente não encontrado.');
            return $this->httpRedirect($request, $response, '/admin/patients');
        }

        $diseases = $this->diseaseModel->getAll();

        return $this->view->render($response, 'admin/patient/edit.twig', ['patient' => $patient, 'diseases' => $diseases]);
    }


    //download
    public function export(Request $request, Response $response)
    {
        $export = new Spreadsheet();
        //$export->addColumn(new DateColumn('Data Inicial'));
        //$export->addColumn(new DateColumn('Data Final'));
        $export->addColumn(new DateColumn('Data do Atendimento'));
        $export->addColumn(new TextColumn('Hora'));
        $export->addColumn(new TextColumn('Paciente'));
        $export->addColumn(new TextColumn('Profissional'));
        $export->addColumn(new TextColumn('Observações'));
        //$attendances = $this->attendanceModel->getAll();
        $attendances = $this->attendanceModel->getAttendancesDownload();
         
        //var_dump($attendances);
        //die;
     
        foreach ($attendances as $attendance) {
            $export->addRow([
               // $attendance->attendance_start,
               // $attendance->attendance_finish,
                $attendance->attendance_day,
                $attendance->attendance_hour,
                $attendance->patient_name,
                $attendance->professional_name,
                $attendance->description,                
            ]);
           

        }
        $writer = new OdsWriter();
        $writer->includeColumnHeaders = true;
     
        $export->download($writer, 'Atendimentos-' . time());
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

        $data = $request->getParsedBody();

        $patient['id'] = (int) $data['id'];
        $patient['id_user'] = (int) $data['id_user'];
        $patient['id_patient_type'] = 1;
        $patient['id_disease'] = $data['id_disease'];

        $patient = $this->entityFactory->createPatient($patient);

        $user = $data;
        $user['id'] = $data['id_user'];

        $user = $this->entityFactory->createUser($user);


        $patient_return = $this->patientModel->update($patient);
        $user_return = $this->userModel->update($user);

        // if it's all ok with updates, create event log
        if ( (($patient_return != null) || ($patient_return != false)) && ($user_return != null) || ($user_return != false)  ) {

            $eventLog['id_patient']         = $patient->id;
            $eventLog['id_event_log_type']  = $this->eventLogTypeModel->getBySlug('edit_patient')->id;
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

    public function view(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $attendance = $this->attendanceModel->get($id);

        $attendance->name_patient = $this->patientModel->get((int)$attendance->id_patient)->name;
        $attendance->name_professional = $this->professionalModel->get((int)$attendance->id_professional)->name;


        if (!$attendance) {
            $this->flash->addMessage('danger', 'Atendimento não encontrado.');
            return $this->httpRedirect($request, $response, '/admin/attendances');
        }

        return $this->view->render($response, 'admin/attendance/view.twig', ['attendance' => $attendance]);
    }
}
