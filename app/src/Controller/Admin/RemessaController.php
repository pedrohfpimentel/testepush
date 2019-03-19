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

class RemessaController extends Controller
{

    protected $remessaModel;
    protected $remessaTypeModel;
    protected $productsModel;
    protected $productsTypeModel;
    protected $supplierModel;
    protected $userModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;
    protected $produtoRemessaModel;
    protected $patientModel;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $remessaModel,
        Model $remessaTypeModel,
        Model $productsModel,
        Model $productsTypeModel,
        Model $supplierModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        Model $produtoRemessaModel,
        Model $patientModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->remessaModel         = $remessaModel;
        $this->remessaTypeModel     = $remessaTypeModel;
        $this->productsModel        = $productsModel;
        $this->productsTypeModel    = $productsTypeModel;
        $this->supplierModel        = $supplierModel;
        $this->userModel            = $userModel;
        $this->eventLogModel        = $eventLogModel;
        $this->eventLogTypeModel    = $eventLogTypeModel;
        $this->produtoRemessaModel  = $produtoRemessaModel;
        $this->patientModel         = $patientModel;
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {
      $this->remessaModel->deleteByRemessaType();

      // get products to autocomplete
      $products = $this->productsModel->getAll();
      $suppliers = $this->supplierModel->getAll();

      // remessa types
      $remessaTypes = $this->remessaTypeModel->getAll();

          $temp['suppliers'] = 0;
          $temp['quantity'] = 0;
          $temp['cost'] = 0;
          $temp['remessa_type'] = 99;

         
          //var_dump($temp);
          //die;
      $params = $request->getQueryParams();

        if (!empty($params['page'])) {
            $page = intval($params['page']);
        } else {
            $page = 1;
        }
        $limit = 20;
        $offset = ($page - 1) * $limit;


      $remessa = $this->remessaModel->getAllByType([1,2,3,6], $offset, $limit);

     // $remessa_type = $this->remessaTypeModel->getAll();
      $remessa_type[] = $this->remessaTypeModel->get(1);
      $remessa_type[] = $this->remessaTypeModel->get(2);
      $remessa_type[] = $this->remessaTypeModel->get(3);
      $remessa_type[] = $this->remessaTypeModel->get(6);
      //var_dump($remessa_type);
      //die;
      foreach ($remessa as $remessas) {
       

          if (($remessas->suppliers != NULL ) ||  ($remessas->suppliers != 0 ))
          {

              $remessas->suppliers_name = $this->supplierModel->get((int)$remessas->suppliers)->name;

          }
            
            $remType = (int)$remessas->remessa_type;
            if ($remType == 6) {
              $remessas->patient_name = $this->patientModel->get((int) $remessas->patient_id)->name; 
            
            }
            $remessas->remessa_type_name = $this->remessaTypeModel->get((int)$remessas->remessa_type)->name;
            
           if ($remessas->date == '0000-00-00 00:00:00') {
            
              $remessas->date = date("d/m/Y", strtotime($remessas->created_at));

            } else if ($remessas->date == NULL) {
            
              $remessas->date = date("d/m/Y", strtotime($remessas->created_at));

            }  

            else {
              $remessas->date = date("d/m/Y", strtotime($remessas->date));
          
            }
          
             

        
       // $date = substr($remessas->date, 0, 10);
       // $date = strtotime($date);
       // $remessas->date = date('d/m/Y', $date);
             
        }

      

      $amountRemessas = $this->remessaModel->getAmount();
        $amountPages = ceil($amountRemessas->amount / $limit);

        $today = date('Y-m-d');
        

        
      // var_dump($remessa);
      //die;
     
      return $this->view->render($response, 'admin/remessa/index.twig',[
        //'date' => $date,
        'suppliers' => $suppliers,
        'remessa' => $remessa,
        'remessa_type' => $remessa_type,
        'page' => $page,
        'amountPages' => $amountPages,
        'today' => $today
      ]);
       
    }

    public function sobre(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'admin/remessa/sobre.twig', ['version' => $this->version]);
    }

    public function add(Request $request, Response $response): Response
    {

        if (empty($request->getParsedBody())) {
          // get products to autocomplete
          $products = $this->productsModel->getAll();
          $suppliers = $this->supplierModel->getAll();

          $this->remessaModel->deleteByRemessaType();

          // remessa types
          //$remessaTypes = [];
          $remessaTypes[] = $this->remessaTypeModel->get(1);
          $remessaTypes[] = $this->remessaTypeModel->get(2);
          $remessaTypes[] = $this->remessaTypeModel->get(6);

          //var_dump($remessaTypes);
          //die;
          $temp['id_products'] = 0;
          $temp['quantity'] = 0;
          $temp['cost'] = 0;
          $temp['remessa_type'] = 99;

          $temp = $this->entityFactory->createRemessa($temp);
          
          $id_remessa = $this->remessaModel->add($temp);

          $patients = $this->patientModel->getAll();
          //$remessaTypes = array_push($remessaTypes, $this->remessaTypeModel->get(1));
         // $remessaTypes = array_push($remessaTypes, $this->remessaTypeModel->get(2));
          return $this->view->render($response, 'admin/remessa/add.twig',
          [
            'products' => $products,
            'suppliers' => $suppliers,
            'remessaTypes' => $remessaTypes,
            'id_remessa' => $id_remessa,
            'patients' => $patients
          ]);
        }

      $remessa = $request->getParsedBody();
       
      $remessa['suppliers'] =  (int) $remessa['suppliers'];
      $remessa['remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['id_remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['quantity'] = (int) $remessa['quantity'];
      $remessa['cost'] = (float) $remessa['cost'];
      $remessa['id'] = (int) $remessa['remessa_id'];
      $remessa['patient_id'] = (int) $remessa['patient_id'];

      $products_remessa = $this->produtoRemessaModel->getAllByRemessa($remessa['id']);

      $lista_produtos = $this->produtoRemessaModel->getAllByRemessaId($remessa['id']);

      $lista_produtos =  json_encode($lista_produtos);
       //var_dump($remessa);
     //die;
      
      

      if (count($products_remessa) < 1 ) {
        $this->flash->addMessage('danger', 'Não é permitido remessa sem produtos.');
        return $this->httpRedirect($request, $response, '/admin/remessa/add');
      }

      $remessa = $this->entityFactory->createRemessa($remessa);

      if ( ($remessa->remessa_type == 1) || ($remessa->remessa_type == 2)) {
        $eventLog['id_remessa'] = $remessa->id;
        $eventLog['suppliers'] =  $remessa->suppliers;
        $idRemessa = $this->remessaModel->update($remessa);
        
      } else if ($remessa->remessa_type == 6) {
          
        $remessa->suppliers =  NULL;
        $eventLog['id_remessa'] = $remessa->id;
        $eventLog['suppliers'] =  $remessa->suppliers;
        $eventLog['id_patient'] = $remessa->patient_id;
          
        $idRemessa = $this->remessaModel->updatePatient($remessa);
      }
      


      // aqui trabalhar eventlog
        if ( ($idRemessa != null) || ($idRemessa != false) ) {
         
          $eventLog['product_list'] = $lista_produtos;

          //var_dump($eventLog);
          if ($remessa->remessa_type == 1){
              $remessa_type = $this->remessaTypeModel->get(1);
              $eventLog['id_remessa_type'] = (int) $eventLog['id_remessa_type'];
             
              $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_doacao')->id;
              $eventLog['description'] = 'Remessa cadastrada';
              //$eventLog['id_products'] = $remessa->id_product;
              
              //var_dump($remessa_type);

              $eventLog = $this->entityFactory->createEventLog($eventLog);
              //var_dump($eventLog);
              //die;
              $this->eventLogModel->add($eventLog);
                //die;
                
          } elseif 
                 ($remessa->remessa_type == 2){
                  $remessa_type = $this->remessaTypeModel->get(2);
                 $eventLog['id_remessa_type'] = (int) $eventLog['id_remessa_type'];

                 $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_compra')->id;
                 $eventLog['description'] = 'Remessa cadastrada';
                 //$eventLog['id_products'] = $remessa->id_product;
                 $eventLog['supplier'] = $remessa->suppliers;
                 $eventLog = $this->entityFactory->createEventLog($eventLog);
                 $this->eventLogModel->add($eventLog);
          } elseif 
                 ($remessa->remessa_type == 6){
                   $remessa_type = $this->remessaTypeModel->get(6);

        $eventLog['id_remessa_type'] = (int) $eventLog['id_remessa_type'];

        $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('devolution')->id;
        $eventLog['description'] = 'Devolução cadastrada';
        
        
        $eventLog = $this->entityFactory->createEventLog($eventLog);
        $this->eventLogModel->add($eventLog);
          }

        }
        
      $this->flash->addMessage('success', 'Remessa adicionada com sucesso.');
      return $this->httpRedirect($request, $response, '/admin/remessa');
    
    }


    //download
    public function export(Request $request, Response $response)
    {

        $params = $request->getQueryParams();

        $remessa_type =  (int)$params['remessa_type'];

        $remessa_start =   $params['remessa_start'];
        if ($remessa_start == "") {
            $remessa_start = "2000-01-01";
        }
        //var_dump($params);
        //die;   
        $remessa_finish =  $params['remessa_finish'];
      
        if ($remessa_type == 0) {
        
            $remessa = $this->remessaModel->getAllByDate($remessa_start, $remessa_finish);
              
            foreach ($remessa as $remessas) {
            //$remessas->suppliers_name = $this->supplierModel->get((int)$remessas->suppliers)->name;
            $remessas->date = date("d/m/Y", strtotime($remessas->date));
            $remessas->remessa_type_name = $this->remessaTypeModel->get((int)$remessas->remessa_type)->name;
            
            //var_dump($remessas);
            //die;
          }
        } else {
            $remessa = $this->remessaModel->getAllByStatus($remessa_type, $remessa_start, $remessa_finish);
            foreach ($remessa as $remessas) {
            $remessas->date = date("d/m/Y", strtotime($remessas->date));
            $remessas->remessa_type_name = $this->remessaTypeModel->get((int)$remessas->remessa_type)->name; 
          }
           // var_dump($remessa);
            //die;
        }

      $html = "
           <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <p style=' '>Fundação Waldyr Becker de Apoio ao Paciente com Câncer.</p>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Relatório de Entrada Cadastrados</h3>
                <p> <strong>Data relatório:</strong>  " . date("d-m-Y") . " </p>
            
            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table>
            
                <tr>
                    <th style='width: 30%; text-align:left;'>Fornecedor/ Paciente</th>
                    <th style='width: 30%; text-align:left;'>Tipo de Entrada</th>
                    <th style='width: 20%; text-align:left;'>Data</th>
                </tr>
        ";  

        
            foreach ($remessa as $remessas) {   

            if ($remessas->remessa_type == 6) { 
              $remessas->patient_name = $this->patientModel->get((int) $remessas->patient_id)->name;
              //var_dump($remessas);
              //die;
            $html .= "
             <tr>
                <td style='width: 30%;'>$remessas->patient_name</td>
                <td style='width: 30%;'>$remessas->remessa_type_name</td>
                <td style='width: 20%;'>$remessas->date</td>
                </tr>
               ";
             }

             else if (($remessas->remessa_type == 1) || ($remessas->remessa_type == 2) || ($remessas->remessa_type == 3)) { 
              $remessas->suppliers_name = $this->supplierModel->get((int)$remessas->suppliers)->name;
              //var_dump($remessas);
              //die;
            $html .= "
             <tr>
                <td style='width: 30%;'>$remessas->suppliers_name</td>
                <td style='width: 30%;'>$remessas->remessa_type_name</td>
                <td style='width: 20%;'>$remessas->date</td>
                </tr>
               ";
             }
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

    public function consulta_produto(Request $request, Response $response): Response
    {
      $idProduct = $request->getQueryParams()['id'];
      $product = $this->productsModel->get((int) $idProduct);
      if ($product) {
        return $response->withJson((array)$product, 200);
      }

      return $response->withJson('erro', 400);

    }

    public function consulta_suppliers(Request $request, Response $response): Response
    {
      $idSuppliers = $request->getQueryParams()['id'];
      $suppliers = $this->supplierModel->get((int) $idSuppliers);
      if ($suppliers) {
        return $response->withJson((array)$suppliers, 200);
      }

      return $response->withJson('erro', 400);

    }


}
