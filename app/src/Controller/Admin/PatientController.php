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
use Farol360\Ancora\Model\ModelException;
use Farol360\Ancora\CustomLogger;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class PatientController extends Controller
{
    protected $attendanceModel;
    protected $patientModel;
    protected $patientFileModel;
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
        Model $patientFileModel,
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
        $this->patientFileModel     = $patientFileModel;
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
        foreach($patients as $patient) {
            if($patient->doc_ficha != NULL){
                $patient->doc_ficha = json_decode($patient->doc_ficha, true);
                // $patient->doc_ficha = str_replace('"', "", $patient->doc_ficha);
            }
          }
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
        $mascara_return = $this->mascara($cpf,'###.###.###-##');

        // buscar usuário
        $user = $this->patientModel->getByCpf($mascara_return);
        
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
            return $this->view->render($response, 'admin/patient/add.twig', 
            [
                'diseases' => $diseases,
                'patient_status' => $patient_status
            ]);
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
        // if (!isset($data['cpf']) || $data['cpf'] == '') {
        //     $this->flash->addMessage('warning', 'O cpf deve ser informado.');
        //     return $this->httpRedirect($request, $response, '/admin/patients/add');
        // }

        if($data['cpf'] != "") {
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
            
        }
        
        
        

        $data['tel_area'] = (int) $data['tel_area'];
        $data['tel_numero'] = (int) $data['tel_numero'];
        $data['end_numero'] = (int) $data['end_numero'];
        $data['password'] = '1234';

        try {
            $this->patientModel->beginTransaction();
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
            $files = $request->getUploadedFiles();
            // if has file in img_featured key
            foreach($files as $file_index => $file) {
            
                if (strpos($file_index, "doc") !== false) {
                    if (!empty( $file)) {
                        $doc = $file;
                        //if has no error on upload
                        if ($doc->getError() === UPLOAD_ERR_OK) {
                            //verify allowed extensions
                            $filename = $doc->getClientFilename();
                            $allowedExtensions = [
                                'doc',
                                'docx',
                                'pdf',
                                'DOC',
                                'DOCX',
                                'PDF',
                                'jpg',
                                'jpeg',
                                'png',
                                'JPG',
                                'JPEG',
                                'PNG'
                            ];
                            // if not allowed extension
                            if (!in_array(pathinfo($filename,PATHINFO_EXTENSION), $allowedExtensions)) {
                                //inform error msg
                                // $this->flash->addMessage('danger', "Documento em formato inválido.");
                                throw new ModelException($file, "Documento em formato inválido.");
                                //redirect to this url
                                // return $this->httpRedirect($request, $response, '/admin/patients/add');
                            }
                            //verify size
                            if ($doc->getSize() > 26000000) {
                            //inform error msg
                                // $this->flash->addMessage('danger', "Arquivo muito grande (max 25Mb).");
                                throw new ModelException($file, "Arquivo muito grande (max 25Mb).");
                                //redirect to this url
                                // return $this->httpRedirect($request, $response, '/admin/patients/add');
                            }
                            // --------
                            // if pass by all verificators..
                            // --------
                            // cabulous function
                            $filename = sprintf(
                                '%s.%s',
                                uniqid(),
                                pathinfo($doc->getClientFilename(), PATHINFO_EXTENSION)
                            );
                            // path to usr img
                            $path = 'upload/';
                            if (!file_exists($path)) {
                            mkdir($path);
                            }
                            // move img to path
                            $doc->moveTo($path . $filename);
                            // var_dump($path . $filename); die;
                            // update path in db
                            
                            $data['doc_ficha'] =  $path . $filename;
                            
                        }
                    }
                }
            }

            // $patient['doc_ficha'] = json_encode($data['doc_ficha']);
            $patient['doc_ficha'] = $data['doc_ficha'];
            $patient = $this->entityFactory->createPatient($patient);
            $id_patient = $this->patientModel->add($patient);
            
            if($id_patient == false){

                throw new ModelException('', "Erro no cadastro de paciente. COD:0002.");
            }
            // create eventLog when add patient
            if ( ($id_patient != null) || ($id_patient != false) )
            {
                $eventLog['id_patient']         = $id_patient;
                $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('create_patient')->id;
                $eventLog['description'] = 'Paciente ' . $user->name .' cadastrado';

                $eventLog = $this->entityFactory->createEventLog($eventLog);
                $this->eventLogModel->add($eventLog);

                // $this->flash->addMessage('success', 'Paciente adicionado com sucesso.');
                // return $this->httpRedirect($request, $response, '/admin/patients');
            }

            $this->patientModel->commit();
            $this->flash->addMessage('success', 'Paciente cadastrado com sucesso.');
            // return $this->httpRedirect($request, $response, '/admin/patients');
        } catch (ModelException $e) {
            $this->patientModel->rollback();
            CustomLogger::ModelErrorLog($e->getMessage(), $e->getdata());
            $this->flash->addMessage('danger', $e->getMessage() . ' Se o problema persistir contate um administrador.');
            // return $this->httpRedirect($request, $response, "/admin/remessa");
        }
        // $this->flash->addMessage('success', 'Paciente adicionado com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/patients');


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

    public function export_ficha(Request $request, Response $response, array $args)
    {

        $patient = $this->patientModel->get((int)$args['id']);
        //var_dump($patient);die;
        $html .= "
            <style>

                table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;
                padding: 0px;
                }

                tr {
                border-collapse: collapse;
                }

                th, td, {
                border-collapse: collapse;
                text-align: left;
                line-height: 1.2;
                border: 1px solid #cdd0d4;
                padding: 2px;
                text-align: center;
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
                
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; padding:5px 0px; background-color: #f5f5f5'>Nome</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; padding:5px 0px; background-color: #f5f5f5'>E-mail</td>
                    </tr>
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; height: 30px; padding: 0px'>$patient->name</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; height: 30px; padding: 0px'>$patient->email</td>
                    </tr>
                    
                </table> 
            </div>  
        ";

        $html .= "
           
            <div style='width:100%; margin-top: 10px;'>
                <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 25%; padding:5px 0px; background-color: #f5f5f5'>Data de Nascimento</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 25%; padding:5px 0px; background-color: #f5f5f5'>CPF</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 25%; padding:5px 0px; background-color: #f5f5f5'>RG</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 25%; padding:5px 0px; background-color: #f5f5f5'>Cartão SUS</td>
                    </tr>
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 25%; height: 30px; padding: 0px'>$patient->nascimento</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 25%; height: 30px; padding: 0px'>$patient->cpf</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 25%; height: 30px; padding: 0px'>$patient->rg</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 25%; height: 30px; padding: 0px'>$patient->sus</td>
                    </tr>
                    
                </table> 
            </div>  
        ";

        
        
    $html .= "<div style='width:100%; margin-top: 10px;'>
                <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 30%; padding:5px 0px; background-color: #f5f5f5'>Rua</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; padding:5px 0px; background-color: #f5f5f5'>Nº</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 20%; padding:5px 0px; background-color: #f5f5f5'>Bairro</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 20%; padding:5px 0px; background-color: #f5f5f5'>Cidade</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; padding:5px 0px; background-color: #f5f5f5'>Estado</td>
                    </tr>
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 30%; height: 30px; padding: 0px'>$patient->end_rua</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; height: 30px; padding: 0px'>$patient->end_numero</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 20%; height: 30px; padding: 0px'>$patient->end_bairro</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 20%; height: 30px; padding: 0px'>$patient->end_cidade</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; height: 30px; padding: 0px'>$patient->end_estado</td>
                    </tr>
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; padding:5px 0px; background-color: #f5f5f5'>CEP</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 90%; padding:5px 0px; background-color: #f5f5f5;' colspan=4>Complemento</td>
                    </tr>
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; height: 30px; padding: 0px'>$patient->end_cep</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 90%; height: 30px; padding: 0px 10 px' colspan=4>$patient->end_complemento</td>
                    </tr>
                </table> 
            </div>  ";

            $html .= "<div style='width:100%; margin-top: 10px;'>
                <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; padding:5px 0px; background-color: #f5f5f5'>DDD</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 15%; padding:5px 0px; background-color: #f5f5f5'>Telefone</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; padding:5px 0px; background-color: #f5f5f5'>DDD 2</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 15%; padding:5px 0px; background-color: #f5f5f5'>Telefone 2</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; padding:5px 0px; background-color: #f5f5f5'>Observações Tel</td>
                    </tr>
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; height: 30px; padding: 0px'>$patient->tel_area</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 15%; height: 30px; padding: 0px'>$patient->tel_numero</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; height: 30px; padding: 0px'>$patient->tel_area_2</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 15%; height: 30px; padding: 0px'>$patient->tel_numero_2</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; height: 30px; padding: 0px'>$patient->obs_tel</td>
                    </tr>
                    
                </table> 
            </div>  ";

            $html .= "<div style='width:100%; margin-top: 10px;'>
                <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 25%; padding:5px 0px; background-color: #f5f5f5'>Situação</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 75%; padding:5px 0px; background-color: #f5f5f5'>CID</td>
                    </tr>
                    <tr>";
                    if($patient->id_status == 1){
                        $html .= "
                            <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; height: 30px; padding: 0px'>Ativo</td>
                        ";
                    }
                    
                    elseif($patient->id_status == 2){
                        $html .= "
                            <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; height: 30px; padding: 0px'>Óbito</td>
                        ";
                    }
                    
                    elseif($patient->id_status == 3){
                        $html .= "
                            <td  style='border: 1px solid #cdd0d4; text-align: center; width: 10%; height: 30px; padding: 0px'>Afastado</td>
                        ";
                    }
                    $html .= "
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 40%; height: 30px; padding: 0px'>$patient->disease_cid_version - $patient->disease_cid_code - $patient->disease_name</td>
                        
                    </tr>
                    
                </table> 
            </div>  ";

            $html .= "<div style='width:100%; margin-top: 10px;'>
                <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 100%; padding:5px 0px; background-color: #f5f5f5'>Observações</td>
                    </tr>
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 100%; height: 30px; padding: 0px'>$patient->obs</td>
                    </tr>
                    
                </table> 
            </div>  ";

            $html .= "<div style='width:100%; margin-top: 10px;'>
                <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; padding:5px 0px; background-color: #f5f5f5'>Tipo de Câncer</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; padding:5px 0px; background-color: #f5f5f5'>Há Quanto Tempo Descobriu?</td>
                    </tr>
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; height: 30px; padding: 0px'>$patient->cancer_type</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; height: 30px; padding: 0px'>$patient->discovery_time</td>
                    </tr>
                    
                </table> 
            </div>  ";

            $html .= "<div style='width:100%; margin-top: 10px;'>
                <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; padding:5px 0px; background-color: #f5f5f5'>Como Descobriu?</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; padding:5px 0px; background-color: #f5f5f5'>Quando começou o tratamento?</td>
                    </tr>
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; height: 30px; padding: 0px'>$patient->discovery_how</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; height: 30px; padding: 0px'>$patient->treatment_time</td>
                    </tr>
                    
                </table> 
            </div>  ";

            $html .= "<div style='width:100%; margin-top: 10px;'>
                <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; padding:5px 0px; background-color: #f5f5f5'>Onde se trata?</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; padding:5px 0px; background-color: #f5f5f5'>Qual o médico?</td>
                    </tr>
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; height: 30px; padding: 0px'>$patient->treatment_where</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 50%; height: 30px; padding: 0px'>$patient->doctor_name</td>
                    </tr>
                    
                </table> 
            </div>  ";

            $html .= "<div style='width:100%; margin-top: 10px;'>
                <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 60%; padding:5px 0px; background-color: #f5f5f5'>Qual apoio necessita da funcação?</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 20%; padding:5px 0px; background-color: #f5f5f5'>Data no Comparecimento na Fundação</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 20%; padding:5px 0px; background-color: #f5f5f5'>Data de cadastro</td>
                    </tr>
                    <tr>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 60%; height: 30px; padding: 0px'>$patient->fundation_need</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 20%; height: 30px; padding: 0px'>$patient->visitDate</td>
                        <td  style='border: 1px solid #cdd0d4; text-align: center; width: 20%; height: 30px; padding: 0px'>$patient->registration_date</td>
                    </tr>
                    
                </table> 
            </div>  ";

    try {
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'P',
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

        $old_patient = $this->patientModel->get((int) $data['id']);
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
        $patient['doc_ficha'] = $old_patient->doc_ficha;
        
        // $old_patient->doc_ficha = json_decode($old_patient->doc_ficha, true);
        
        if($data['doc_old'] == "1") {
            $files = $request->getUploadedFiles();
            // if files are empty means size == 0
            foreach($files as $file_index => $file) {
                if (strpos($file_index, "doc") !== false) {
                    if (!empty( $file)) {
                        $doc = $file;
                        //if has no error on upload
                        if ($doc->getError() === UPLOAD_ERR_OK) {
                            //verify allowed extensions
                            $filename = $doc->getClientFilename();
                            $allowedExtensions = [
                                'doc',
                                'docx',
                                'pdf',
                                'DOC',
                                'DOCX',
                                'PDF',
                                'jpg',
                                'jpeg',
                                'png',
                                'JPG',
                                'JPEG',
                                'PNG'
                            ];
                            // if not allowed extension
                            if (!in_array(pathinfo($filename,PATHINFO_EXTENSION), $allowedExtensions)) {
                            //inform error msg
                            $this->patientModel->rollback();
                            $this->flash->addMessage('danger', "Documento em formato inválido.");
                            //redirect to this url
                            return $this->httpRedirect($request, $response, '/admin/patients/add');
                            }
                            //verify size
                            if ($doc->getSize() > 26000000) {
                            //inform error msg
                            $this->patientModel->rollback();
                            $this->flash->addMessage('danger', "Arquivo muito grande (max 25Mb).");
                            //redirect to this url
                            return $this->httpRedirect($request, $response, '/admin/patients/add');
                            }
                            // --------
                            // if pass by all verificators..
                            // --------
                            // cabulous function
                            $filename = sprintf(
                                '%s.%s',
                                uniqid(),
                                pathinfo($doc->getClientFilename(), PATHINFO_EXTENSION)
                            );
                            // path to usr img
                            $path = 'upload/';
                            if (!file_exists($path)) {
                            mkdir($path);
                            }
                            // move img to path
                            $doc->moveTo($path . $filename);
                            // var_dump($path . $filename); die;
                            // update path in db
                            
                            $patient['doc_ficha'] =  $path . $filename;
                            
                        }
                    }
                }
                    
            }
        }
        
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

    public function docs_index(Request $request, Response $response, array $args): Response
    {
        $patient = $this->patientModel->get((int)$args['id']);
        $files = $this->patientFileModel->getAllByPatient((int)$args['id']);
        
        if (!empty($patient->doc_ficha)) {
            array_unshift($files, [
                'id' => 0,
                'name' => 'doc_ficha',
                'url_file' => $patient->doc_ficha
            ]);
        }

        return $this->view->render($response, 'admin/patient_file/index.twig', [
            'files' => $files,
            'patient_id' => $args['id']
            ]);
    }

    public function docs_add(Request $request, Response $response): Response
    {
        // get the body and parse it to an array
        $data = $request->getParsedBody();

        try {
            $this->patientFileModel->beginTransaction();
            
            // var_dump($patient);die;
            $files = $request->getUploadedFiles();
            // if has file in img_featured key
            foreach($files as $file_index => $file) {
            
                if (strpos($file_index, "doc") !== false) {
                    if (!empty( $file)) {
                        $doc = $file;
                        //if has no error on upload
                        if ($doc->getError() === UPLOAD_ERR_OK) {
                            //verify allowed extensions
                            $filename = $doc->getClientFilename();
                            $allowedExtensions = [
                                'doc',
                                'docx',
                                'pdf',
                                'DOC',
                                'DOCX',
                                'PDF',
                                'jpg',
                                'jpeg',
                                'png',
                                'JPG',
                                'JPEG',
                                'PNG'
                            ];
                            // if not allowed extension
                            if (!in_array(pathinfo($filename,PATHINFO_EXTENSION), $allowedExtensions)) {
                                //inform error msg
                                // $this->flash->addMessage('danger', "Documento em formato inválido.");
                                throw new ModelException($file, "Documento em formato inválido.");
                                //redirect to this url
                                // return $this->httpRedirect($request, $response, '/admin/patients/add');
                            }
                            //verify size
                            if ($doc->getSize() > 26000000) {
                            //inform error msg
                                // $this->flash->addMessage('danger', "Arquivo muito grande (max 25Mb).");
                                throw new ModelException($file, "Arquivo muito grande (max 25Mb).");
                                //redirect to this url
                                // return $this->httpRedirect($request, $response, '/admin/patients/add');
                            }
                            // --------
                            // if pass by all verificators..
                            // --------
                            // cabulous function
                            $filename = sprintf(
                                '%s.%s',
                                uniqid(),
                                pathinfo($doc->getClientFilename(), PATHINFO_EXTENSION)
                            );
                            // path to usr img
                            $path = 'upload/';
                            if (!file_exists($path)) {
                            mkdir($path);
                            }
                            // move img to path
                            $doc->moveTo($path . $filename);
                            // var_dump($path . $filename); die;
                            // update path in db
                            
                            $data['doc_ficha'] =  $path . $filename;
                            
                        }
                    }
                }
            }

            // $patient['doc_ficha'] = json_encode($data['doc_ficha']);
            $patient_file['url_file'] = $data['doc_ficha'];
            $patient_file['id_patient'] = $data['patient_id'];
            $patient_file['name'] = $data['name'];
            $patient_file_obj = $this->entityFactory->createPatientFile($patient_file);
            $id_patient_file = $this->patientFileModel->add($patient_file_obj);
            
            if($id_patient_file == false){

                throw new ModelException('', "Erro no cadastro de paciente. COD:0002.");
            }
            // create eventLog when add patient
            if ( ($id_patient != null) || ($id_patient != false) )
            {
                $eventLog['id_patient_file']         = $id_patient_file;
                $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('edit_patient')->id;
                $eventLog['description'] = 'Arquivo ' . $patient_file_obj->name .' cadastrado';

                $eventLog = $this->entityFactory->createEventLog($eventLog);
                $this->eventLogModel->add($eventLog);
            }

            $this->patientModel->commit();
            $this->flash->addMessage('success', 'Arquivo cadastrado com sucesso.');
            // return $this->httpRedirect($request, $response, '/admin/patients');
        } catch (ModelException $e) {
            $this->patientModel->rollback();
            CustomLogger::ModelErrorLog($e->getMessage(), $e->getdata());
            $this->flash->addMessage('danger', $e->getMessage() . ' Se o problema persistir contate um administrador.');
            // return $this->httpRedirect($request, $response, "/admin/remessa");
        }
        // $this->flash->addMessage('success', 'Paciente adicionado com sucesso.');
        return $this->httpRedirect($request, $response, "/admin/patients/docs/".$data['patient_id']);


    }

    public function docs_remove(Request $request, Response $response, array $args): Response
    {
        $id_patient = intval($args['id']);
        $id_file = intval($args['id_doc']);
        $file = $this->patientFileModel->get($id_file);
        $patient = $this->patientModel->get((int)$id_patient);
        if($id_file === 0) {
            if (isset($patient->doc_ficha)) {
                unlink($patient->doc_ficha);
                $this->patientModel->deleteDoc((int) $patient->patient_id);
            } else {
                $this->flash->addMessage('danger', 'Erro ao remover arquivo, tente novamente.');
                return $this->httpRedirect($request, $response, '/admin/patients/docs/'.$id_patient);
            }
        } else {
            if (isset($file)) {
                unlink($file->url_file);
                $this->patientFileModel->delete((int) $file->id);
            } else {
                $this->flash->addMessage('danger', 'Erro ao remover arquivo, tente novamente.');
                return $this->httpRedirect($request, $response, '/admin/patients/docs/'.$id_patient);
            }
        }

        $this->flash->addMessage('success', 'Arquivo removido com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/patients/docs/'.$id_patient);
    }

    public function docs_export(Request $request, Response $response, array $args): Response
    {
        $id_patient = intval($args['id']);
        $id_file = intval($args['id_doc']);
        $patient = $this->patientModel->get((int)$args['id']);
        if($id_file === 0) {
            if (isset($patient->doc_ficha)) {
                $mime = mime_content_type($patient->doc_ficha);
                header('Content-Type: ' . $mime);
                header('Content-Disposition: inline; filename="' . basename($patient->doc_ficha) . '"');
                header('Content-Length: ' . filesize($patient->doc_ficha));
                readfile($patient->doc_ficha);
                exit;
            } else {
                $this->flash->addMessage('danger', 'Erro ao remover arquivo, tente novamente.');
                return $this->httpRedirect($request, $response, '/admin/patients/docs/'.$id_patient);
            }
        }

        $file = $this->patientFileModel->get($id_file);

        if (isset($file)) {
            $mime = mime_content_type($file->url_file);
            header('Content-Type: ' . $mime);
            header('Content-Disposition: inline; filename="' . basename($file->url_file) . '"');
            header('Content-Length: ' . filesize($file->url_file));
            readfile($file->url_file);
            exit;
        } else {
            $this->flash->addMessage('danger', 'Erro ao remover arquivo, tente novamente.');
            return $this->httpRedirect($request, $response, '/admin/patients/docs/'.$id_patient);
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

    function mascara($valor, $formato) {
        $retorno = '';
        $posicao_valor = 0;
        for($i = 0; $i<=strlen($formato)-1; $i++) {
            if($formato[$i] == '#') {
                if(isset($valor[$posicao_valor])) {
                    $retorno .= $valor[$posicao_valor++];
                }
            } else {
                $retorno .= $formato[$i];
            }
        }
        return $retorno;
    }
}
