<?php
declare(strict_types=1);

namespace Farol360\Ancora\Controller\Admin;

use Farol360\Ancora\Controller;
use Farol360\Ancora\Model;
use Farol360\Ancora\Model\EntityFactory;
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
        $patients = $this->patientModel->getAll();

        return $this->view->render($response, 'admin/patient/index.twig', ['patients' => $patients]);
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

        $user = $this->entityFactory->createUser($data);

        // add new user
        $patient['id_user'] = $this->userModel->add($user);

        // set patient type manual;
        $patient['id_patient_type'] = 1;

        // set disease
        $patient['id_disease'] = (int) $data['id_disease'];

        $patient['tel_area_2'] = $data['tel_area_2'];

        $patient['tel_numero_2'] = $data['tel_numero_2'];

        $patient['obs_tel'] = $data['obs_tel'];

        $patient['rg'] = $data['rg'];

        $patient['sus'] = $data['sus'];
        $patient['id_status'] = (int) $data['id_status'];
        $patient['obs'] = $data['obs'];



        $patient = $this->entityFactory->createPatient($patient);


        ($id_patient = $this->patientModel->add($patient));

        // create eventLog when add patient
        if ( ($id_patient != null) || ($id_patient != false) )
        {
            $eventLog['id_patient']         = $id_patient;
            $eventLog['id_event_log_type']  = $this->eventLogTypeModel->getBySlug('create_patient')->id;
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
        $patient['id_disease'] = (int) $data['id_disease'];
        $patient['tel_area_2'] = $data['tel_area_2'];
        $patient['tel_numero_2'] = $data['tel_numero_2'];
        $patient['obs_tel'] = $data['obs_tel'];
        $patient['rg'] = $data['rg'];
        $patient['sus'] = $data['sus'];

        $patient['id_status'] = (int) $data['id_status'];

        $patient['obs'] = $data['obs'];

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
}
