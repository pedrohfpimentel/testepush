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
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {


      // get products to autocomplete
      $products = $this->productsModel->getAll();
      $suppliers = $this->supplierModel->getAll();

      // remessa types
      $remessaTypes = $this->remessaTypeModel->getAll();

      $params = $request->getQueryParams();

        if (!empty($params['page'])) {
            $page = intval($params['page']);
        } else {
            $page = 1;
        }
        $limit = 20;
        $offset = ($page - 1) * $limit;


      $remessa = $this->remessaModel->getAll($offset, $limit);
      $remessa_type = $this->remessaTypeModel->getAll();
      //var_dump($remessa_type);
      //die;
      foreach ($remessa as $remessas) {
         
            $remessas->products_name = $this->productsModel->get((int)$remessas->id_product)->name;

           // $remessas->suppliers_name = $this->supplierModel->get((int)$remessas->suppliers)->id;

            $remessas->remessa_type_name = $this->remessaTypeModel->get((int)$remessas->remessa_type)->name;

        
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
        'products' => $products,
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

      $this->remessaModel->deleteByRemessaType();

        if (empty($request->getParsedBody())) {
          // get products to autocomplete
          $products = $this->productsModel->getAll();
          $suppliers = $this->supplierModel->getAll();
          // remessa types
          //$remessaTypes = [];
          $remessaTypes[] = $this->remessaTypeModel->get(1);
          $remessaTypes[] = $this->remessaTypeModel->get(2);

          
          $temp['id_products'] = 0;
          $temp['quantity'] = 0;
          $temp['cost'] = 0;
          $temp['remessa_type'] = 99;

          $temp = $this->entityFactory->createRemessa($temp);
          
          $id_remessa = $this->remessaModel->add($temp);


          //$remessaTypes = array_push($remessaTypes, $this->remessaTypeModel->get(1));
         // $remessaTypes = array_push($remessaTypes, $this->remessaTypeModel->get(2));
          return $this->view->render($response, 'admin/remessa/add.twig',
          [
            'products' => $products,
            'suppliers' => $suppliers,
            'remessaTypes' => $remessaTypes,
            'id_remessa' => $id_remessa,
          ]);
        }

      $remessa = $request->getParsedBody();
        //var_dump($remessa);
        //die;
     // $remessa = $this->entityFactory->createRemessa($request->getParsedBody());

      
      //$this->remessaModel->add($remessa);



       // $this->flash->addMessage('success', 'Remessa adicionada com sucesso.');
       // return $this->httpRedirect($request, $response, '/admin/remessa'); 

      

      $remessa['id_product'] = (int) substr($remessa['id_product'], 0, strpos($remessa['id_product'], ' '));
       $remessa['suppliers'] = (int) $remessa['suppliers'];
      $remessa['remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['id_remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['quantity'] = (int) $remessa['quantity'];
      $remessa['cost'] = (float) $remessa['cost'];
     

      $remessa = $this->entityFactory->createRemessa($remessa);
     
      $idRemessa = $this->remessaModel->add($remessa);
    //var_dump($remessa);
//die;

      // aqui trabalhar eventlog
        if ( ($idRemessa != null) || ($idRemessa != false) ) {
         
            $eventLog['id_remessa'] = $idRemessa;

            if ($remessa->remessa_type == 1){
               $eventlog['id_remessa_type'] = (int) $eventlog['id_remessa_type'];

               $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_doacao')->id;
               $eventLog['description'] = 'Remessa ' . $remessa->name .' cadastrado(a)';
               $eventLog['id_products'] = $remessa->id_product;
               $eventlog['supplier'] = $remessa->suppliers;
               $eventLog = $this->entityFactory->createEventLog($eventLog);
               $this->eventLogModel->add($eventLog);
          } elseif 
                 ($remessa->remessa_type == 2){
                 $eventlog['id_remessa_type'] = (int) $eventlog['id_remessa_type'];

                 $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_compra')->id;
                 $eventLog['description'] = 'Remessa ' . $remessa->name .' cadastrado(a)';
                 $eventLog['id_products'] = $remessa->id_product;
                 $eventLog['supplier'] = $remessa->suppliers;
                 $eventLog = $this->entityFactory->createEventLog($eventLog);
                 $this->eventLogModel->add($eventLog);
          }
        }
        
      $this->flash->addMessage('success', 'Remessa adicionada com sucesso.');
      return $this->httpRedirect($request, $response, '/admin/products');
    
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

        } else {
            $remessa = $this->remessaModel->getAllByType($remessa_type, $remessa_start, $remessa_finish);
        }


      $html = "
           
        ";
        foreach ($remessa as $remessas) {
           // var_dump($remessa->name);
            //die;
            
            
            
            $html .= "
               ";
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
