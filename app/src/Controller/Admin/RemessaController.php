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


      $remessa = $this->remessaModel->getAll($offset, $limit);
      $remessa_type = $this->remessaTypeModel->getAll();
      //var_dump($remessa_type);
      //die;
      foreach ($remessa as $remessas) {
         
            $remessas->products_name = $this->productsModel->get((int)$remessas->id_product)->name;


            $remessas->remessa_type_name = $this->remessaTypeModel->get((int)$remessas->remessa_type)->name;

             
        }

     

      $amountRemessas = $this->remessaModel->getAmount();
        $amountPages = ceil($amountRemessas->amount / $limit);

        $today = date('Y-m-d');

       //var_dump($remessa);
      //die;
     
      return $this->view->render($response, 'admin/remessa/index.twig',[
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
          return $this->view->render($response, 'admin/remessa/add.twig');
        }

      $remessa = $request->getParsedBody();
        
      $remessa = $this->entityFactory->createRemessa($request->getParsedBody());


      $this->remessaModel->add($remessa);



        $this->flash->addMessage('success', 'Remessa adicionada com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/remessa'); 

     // var_dump($remessa);
      //die;

      $remessa['id_product'] = (int) substr($remessa['id_product'], 0, strpos($remessa['id_product'], ' '));
      $remessa['remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['id_remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['quantity'] = (int) $remessa['quantity'];
      $remessa['cost'] =  $remessa['cost'];
     

      $remessa = $this->entityFactory->createRemessa($remessa);
     
      $idRemessa = $this->remessaModel->add($remessa);
    

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
      return $this->httpRedirect($request, $response, '/admin/products');
    
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
