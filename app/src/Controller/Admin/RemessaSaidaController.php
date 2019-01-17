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

    protected $remessaSaidaModel;
    protected $remessaSaidaTypeModel;
    protected $productsModel;
    protected $productsTypeModel;
    protected $userModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $remessaSaidaModel,
        Model $remessaSaidaTypeModel,
        Model $productsModel,
        Model $productsTypeModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->remessaSaidaModel         = $remessaSaidaModel;
        $this->remessaSaidaTypeModel     = $remessaSaidaTypeModel;
        $this->productsModel        = $productsModel;
        $this->productsTypeModel    = $productsTypeModel;
        $this->userModel            = $userModel;
        $this->eventLogModel        = $eventLogModel;
        $this->eventLogTypeModel    = $eventLogTypeModel;
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {
      // get products to autocomplete
      $products = $this->productsModel->getAll();
      
      // remessa types
      $remessaSaidaTypes = $this->remessaSaidaTypeModel->getAll();
     
      return $this->view->render($response, 'admin/remessa_saida/index.twig',
      [
        'products' => $products,
        'remessaSaidaTypes' => $remessaSaidaTypes
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
         
          // remessa types

          //$remessaTypes = [];
          $remessaSaidaTypes[] = $this->remessaSaidaTypeModel->get(1);

          $remessaSaidaTypes[] = $this->remessaSaidaTypeModel->get(2);
          //$remessaTypes = array_push($remessaTypes, $this->remessaTypeModel->get(1));

         // $remessaTypes = array_push($remessaTypes, $this->remessaTypeModel->get(2));

          return $this->view->render($response, 'admin/remessa_saida/index.twig',
          [
            'products' => $products,
            'remessaSaidaTypes' => $remessaSaidaTypes
          ]);
        }

        


      $remessa_saida = $request->getParsedBody();

     // var_dump($remessa);
      //die;

      $remessa_saida['id_product'] = (int) substr($remessa_saida['id_product'], 0, strpos($remessa_saida['id_product'], ' '));
      $remessa_saida['remessa_type'] = (int) $remessa_saida['id_remessa_type'];
      $remessa_saida['id_remessa_type'] = (int) $remessa_saida['id_remessa_type'];
      $remessa_saida['quantity'] = (int) $remessa_saida['quantity'];
      $remessa_saida['cost'] =  $remessa_saida['cost'];
     

      $remessa_saida = $this->entityFactory->createRemessaSaida($remessa_saida);
     
      $idRemessa = $this->remessaSaidaModel->add($remessa_saida);
    

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
