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
    protected $eventLogModel;
    protected $eventLogTypeModel;
    protected $userModel;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $professionalModel,
        Model $professionalTypeModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->professionalModel = $professionalModel;
        $this->professionalTypeModel = $professionalTypeModel;
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
        $limit = 3;
        $offset = ($page - 1) * $limit;

       
        $professionals = $this->professionalModel->getAll($offset, $limit);
        $professional_types = $this->professionalTypeModel->getAll();
               
        $amountProfessionals = $this->professionalModel->getAmount();
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

        $data['password'] = 'Ancora1337';
        $data['role_id'] = 6;

        if ($this->professionalModel->getByEmail($data['email']) != false) {
            $this->flash->addMessage('success', 'O email já existe. por favor cadastre um email único.');
            return $this->httpRedirect($request, $response, '/admin/professionals/add');
        }

        $user = $this->entityFactory->createUser($data);

        $professional['id_user'] = $this->userModel->add($user);
        $professional['id_professional_type'] = $data['id_professional_type'];

        $professional = $this->entityFactory->createProfessional($professional);


        $id_professional = $this->professionalModel->add($professional);

        if ( ($id_professional != null) || ($id_professional != false) ) {
            $eventLog['id_professional']    = $id_professional;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('create_professional')->id;
            $eventLog['description'] = 'Profissional ' . $user->name .' cadastrado';

            $eventLog = $this->entityFactory->createEventLog($eventLog);

            $this->eventLogModel->add($eventLog);

           $this->flash->addMessage('success', 'Paciente adicionado com sucesso.');
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
            return $this->view->render($response, 'admin/professional/edit.twig', ['professional' => $professional, 'professional_types' => $professional_types]);
    }

    public function history(Request $request, Response $response, array $args): Response {
        $id = intval($args['id']);
        $professional = $this->professionalModel->get($id);

        $event_logs = $this->eventLogModel->getByProfessional($id);

        return $this->view->render($response, 'admin/professional/history.twig', ['professional' => $professional,'event_logs' => $event_logs]);
    }

    //download
    public function export(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        //var_dump($params);
        //die;

        $export = new Spreadsheet();
        $export->addColumn(new TextColumn('Nome'));
        $export->addColumn(new TextColumn('Email'));
        $export->addColumn(new DateColumn('Data de nascimento'));
        $export->addColumn(new TextColumn('CPF'));
        $export->addColumn(new NumericColumn('DDD'));
        $export->addColumn(new NumericColumn('Telefone'));
        $export->addColumn(new TextColumn('CEP'));
        $export->addColumn(new TextColumn('Rua'));
        $export->addColumn(new TextColumn('Numero'));
        $export->addColumn(new TextColumn('Complemento'));
        $export->addColumn(new TextColumn('Bairro'));        
        $export->addColumn(new TextColumn('Cidade'));
        $export->addColumn(new TextColumn('Estado'));
        $export->addColumn(new TextColumn('Categoria'));
        $professionals = $this->professionalModel->getProfessionalsDownload($params['professionals_start'], $params[ 'professionals_finish']);
       // var_dump($professionals);
       // die;
        foreach ($professionals as $professional) {
            //var_dump($professional);
            //die;
            $export->addRow([
                $professional['name'],
                
                $professional['email'],
                $professional['nascimento'],
                $professional['cpf'],
                $professional['tel_area'],
                $professional['tel_numero'],
                $professional['end_cep'],
                $professional['end_rua'],
                $professional['end_numero'],                
                $professional['end_complemento'],
                $professional['end_bairro'],
                $professional['end_cidade'],
                $professional['end_estado'],
                $professional['id_professional_type'],
                
            ]);//var_dump($professional);
            //die;
        }
        $writer = new OdsWriter();
        $writer->includeColumnHeaders = true;
     
        $export->download($writer, 'Profissionais-' . time());
    }

    public function update(Request $request, Response $response): Response
    {

        $data = $request->getParsedBody();

        $professional['id'] = (int) $data['id'];
        $professional['id_user'] = (int) $data['id_user'];
        $professional['id_professional_type'] = (int) $data['id_professional_type'];
        $professional = $this->entityFactory->createprofessional($professional);

        $user = $data;
        $user['id'] = (int) $data['id_user'];

        $user_old = (array) $this->userModel->get($user['id']);
        $user_new = $user_old =  $user;
        $user_new = $this->entityFactory->createUser($user_new);

        $professional_return = $this->professionalModel->update($professional);
        $user_return = $this->userModel->update($user_new);

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
