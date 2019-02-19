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

class RemessaSaidaController extends Controller
{
    protected $remessaModel;
    protected $remessaSaidaModel;
    protected $remessaTypeModel;
    protected $productsModel;
    protected $productsTypeModel;
    protected $userModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $remessaModel,
        Model $remessaSaidaModel,
        Model $remessaTypeModel,
        Model $productsModel,
        Model $productsTypeModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->remessaModel         = $remessaModel;
        $this->remessaSaidaModel         = $remessaSaidaModel;
        $this->remessaTypeModel     = $remessaTypeModel;
        $this->productsModel        = $productsModel;
        $this->productsTypeModel    = $productsTypeModel;
        $this->userModel            = $userModel;
        $this->eventLogModel        = $eventLogModel;
        $this->eventLogTypeModel    = $eventLogTypeModel;
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
   {
      $this->remessaModel->deleteByRemessaType();

      // get products to autocomplete
      $products = $this->productsModel->getAll();
      

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


      $remessa = $this->remessaModel->getAllByType([4,5], $offset, $limit);

      //$remessa_type = $this->remessaTypeModel->getAll();
      $remessa_type[] = $this->remessaTypeModel->get(4);
      $remessa_type[] = $this->remessaTypeModel->get(5);
      
      //$product_name = $this->productsModel->getAll();
      //var_dump($product_name);
      //die;
      foreach ($remessa as $remessas) {
       // var_dump($remessas);
        //die;
            //var_dump($remessas);
            //die;
            // $remessas->product_name = $this->productsModel->get((int)$remessas->id_product)->name;
           
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
        

        
       //var_dump($remessa);
      //die;
     
      return $this->view->render($response, 'admin/remessa_saida/index.twig',[
        //'date' => $date,
        'remessa' => $remessa,
        'remessa_type' => $remessa_type,
        'page' => $page,
        'amountPages' => $amountPages,
        'today' => $today
      ]);
       
    }

    public function sobre(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'admin/remessa_saida/sobre.twig', ['version' => $this->version]);
    }

    public function add(Request $request, Response $response): Response
    {

        if (empty($request->getParsedBody())) {
          // get products to autocomplete
          $products = $this->productsModel->getAll();
         

          $this->remessaModel->deleteByRemessaType();

          // remessa types
          //$remessaTypes = [];
          $remessaTypes[] = $this->remessaTypeModel->get(4);
          $remessaTypes[] = $this->remessaTypeModel->get(5);

          
          $temp['id_products'] = 0;
          $temp['quantity'] = 0;
          $temp['cost'] = 0;
          $temp['remessa_type'] = 99;

          $temp = $this->entityFactory->createRemessa($temp);
          
          $id_remessa = $this->remessaModel->add($temp);

          
          //$remessaTypes = array_push($remessaTypes, $this->remessaTypeModel->get(1));
         // $remessaTypes = array_push($remessaTypes, $this->remessaTypeModel->get(2));
          return $this->view->render($response, 'admin/remessa_saida/add.twig',
          [
            'products' => $products,
            'remessaTypes' => $remessaTypes,
            'id_remessa' => $id_remessa,
          ]);
        }

      $remessa = $request->getParsedBody();

       
      $remessa['remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['id_remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['quantity'] = (int) $remessa['quantity'];
      $remessa['id'] = (int) $remessa['remessa_id'];
     
     

      $remessa = $this->entityFactory->createRemessa($remessa);
      
      $idRemessa = $this->remessaModel->update($remessa);


      // aqui trabalhar eventlog
        if ( ($idRemessa != null) || ($idRemessa != false) ) {
         
            $eventLog['id_remessa'] = $idRemessa;

            if ($remessa->remessa_type == 1){
               $eventlog['id_remessa_type'] = (int) $eventlog['id_remessa_type'];

               $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_doacao')->id;
               $eventLog['description'] = 'Remessa ' . $remessa->name .' cadastrado(a)';
               $eventLog['id_products'] = $remessa->id_product;
               $eventLog = $this->entityFactory->createEventLog($eventLog);
               $this->eventLogModel->add($eventLog);
          } elseif 
                 ($remessa->remessa_type == 2){
                 $eventlog['id_remessa_type'] = (int) $eventlog['id_remessa_type'];

                 $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_compra')->id;
                 $eventLog['description'] = 'Remessa ' . $remessa->name .' cadastrado(a)';
                 $eventLog['id_products'] = $remessa->id_product;
                 $eventLog = $this->entityFactory->createEventLog($eventLog);
                 $this->eventLogModel->add($eventLog);
          }
        }
        
      $this->flash->addMessage('success', 'Remessa adicionada com sucesso.');
      return $this->httpRedirect($request, $response, '/admin/remessa_saida');

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


}
