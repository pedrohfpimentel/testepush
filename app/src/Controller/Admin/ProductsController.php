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


class ProductsController extends Controller
{

    protected $productsModel;
    protected $productsTypeModel;
    protected $userModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;

    public function __construct( 
        View $view, 
        FlashMessages $flash,
        Model $productsModel,
        Model $productsTypeModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->productsModel        = $productsModel;
        $this->productsTypeModel    = $productsTypeModel;
        $this->userModel            = $userModel;
        $this->eventLogModel        = $eventLogModel;
        $this->eventLogTypeModel    = $eventLogTypeModel;
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {
        $products = $this->productsModel->getAll();

        return $this->view->render($response, 'admin/products/index.twig', ['products' => $products]);
    }
    
    public function add(Request $request, Response $response): Response
    {
        if (empty($request->getParsedBody())) {
            $products_type = $this->productsTypeModel->getAll(); 
            return $this->view->render($response, 'admin/products/add.twig', ['products_type' => $products_type]);    
        }

        $products = $request->getParsedBody();

        $products['category'] = (int) $products['id_products_type'];

        $products = $this->entityFactory->createProducts($products);
        
        $idProduct = $this->productsModel->add($products);

        // aqui trabalhar eventlog
        if ( ($idProduct != null) || ($idProduct != false) ) {
            $eventLog['id_products'] = $idProduct;
            $eventLog['id_event_log_type']  = $this->eventLogTypeModel->getBySlug('create_products')->id;
            $eventLog['description'] = 'Produto ' . $products->name .' cadastrado';
            

            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);

           //   var_dump($products);
       // exit;

        }
        

        $this->flash->addMessage('success', 'Produto adicionada com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/products'); 


    
    }
    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $this->productsModel->delete($id);
        $this->flash->addMessage('success', 'Produto removida com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/products'); 
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $products = $this->productsModel->get($id);
        if (!$products) {
            $this->flash->addMessage('danger', 'Produto não encontrado.');
            return $this->httpRedirect($request, $response, '/admin/products');
}
         $products_type = $this->productsTypeModel->getAll();
            return $this->view->render($response, 'admin/products/edit.twig', ['products' => $products, 'products_type' => $products_type]);

    }

    public function history(Request $request, Response $response, array $args)
    {
        $id = intval($args['id']);
        $products = $this->productsModel->get($id);


        $event_logs = $this->eventLogModel->getByProducts($id);

        return $this->view->render($response, 'admin/products/history.twig', ['products' => $products,
            'event_logs' => $event_logs]);
           
    }

    public function update(Request $request, Response $response): Response
    {
   

        $data = $request->getParsedBody();

        $products = $request->getParsedBody();
        $products['id'] = (int) $data['id'];
        $products['id_products'] = (int) $data['id_products'];
        $products['category'] = (int) $products['id_products_type'];

        $old_product = $this->productsModel->get($products['id']);
        $products['quantity'] = (int) $old_product->quantity;
    
        $products = $this->entityFactory->createProducts($products);

        $this->productsModel->update($products);
         //$eventLog = $this->entityFactory->createEventLog($eventLog);
           // $this->eventLogModel->add($eventLog);

        // if it's all ok with updates, create event log


            $eventLog['id_products']         = $products->id;
            $eventLog['id_event_log_type']  = $this->eventLogTypeModel->getBySlug('edit_products')->id;
            $eventLog['description'] = 'Produto ' . $user->name .' atualizado';

            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);

        $this->flash->addMessage('success', 'Produto atualizado com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/products'); 

    }
}
