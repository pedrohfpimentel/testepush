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
    protected $remessaModel;
    protected $remessaTypeModel;
    protected $supplierModel;
    protected $userModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;

    public function __construct( View $view, FlashMessages $flash,
        Model $productsModel,
        Model $productsTypeModel,
        Model $remessaModel,
        Model $remessaTypeModel,
        Model $supplierModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->productsModel        = $productsModel;
        $this->productsTypeModel    = $productsTypeModel;
        $this->remessaModel         = $remessaModel;
        $this->remessaTypeModel     = $remessaTypeModel;
        $this->supplierModel        = $supplierModel;
        $this->userModel            = $userModel;
        $this->eventLogModel        = $eventLogModel;
        $this->eventLogTypeModel    = $eventLogTypeModel;
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {
        // get products list
        $products = $this->productsModel->getAll();

        // remessa types
      $remessaTypes = $this->remessaTypeModel->getAll();

      // getRemessasByIdProduct function
    
    
    
       // $products_id = $this->productsModel->getProductID();

        foreach($products as $product) {

            $quantity = 0;               
            $remessas = $this->productsModel->getRemessasByIdProduct($product->id); 
            
                foreach($remessas as $remessa) {
                    $quantity = $quantity + $remessa->quantity;      
                }

            $product->quantity= $quantity;
                //var_dump($quantity);            
        }
         // die;
             
        // get quantity from remessas
        foreach ($products as $product) {
          // $remessaList = $this->remessaModel->getAll($product->id);
        }

        return $this->view->render($response, 'admin/products/index.twig', 
        [
            'products' => $products,
            'remessaTypes' => $remessaTypes,
            'quantity'=> $quantity
        ]);
    }

    /*
        A função trata o CADASTRO DE PRODUTOS, CADASTRO DE REMESSA e EVENT LOGS para ambos.
        A lógica segue os seguintes passos:

        1 - Recupera e trata as informações da interface pra o CADASTRO DO PRODUTO;

        2 - Cadastra o produto;

        3 - Recupera e trata as informações da interface para o CADASTRO DE REMESSA;

        4 - Cadastro de remessa;

        5 - Gera o EVENTO DE CADASTRO DO PRODUTO;
    */
    public function add(Request $request, Response $response): Response
    {
        // if que verifica se deve renderizar a tela de cadastro.
        if (empty($request->getParsedBody())) {
            $products_type = $this->productsTypeModel->getAll();
            $remessaTypes = $this->remessaTypeModel->getAll();
            $id_supplier = $this->supplierModel->getAll();

            return $this->view->render($response, 'admin/products/add.twig', 
                [
                    'products_type' => $products_type, 
                    'remessaTypes' => $remessaTypes,
                    'id_supplier'      => $id_supplier,
                    'patrimony'    => $patrimony,
                    'patrimony_code' => $patrimony_code
                ]);
        }

   

        // A partir desta linha, segue a lógica para o cadastro do produto.

        // 1 - recupera e trata as informações da interface
        $products = $request->getParsedBody();
        $products['category'] = (int) $products['id_products_type'];
        $products['remessa_type'] = (int) $products['id_remessa_type'];
        $products['id_remessa_type'] = (int) $products['id_remessa_type'];
        $products['id_supplier'] = (int) $products['id_supplier'];
        
      //  if (isset($products['patrimony'])) {


            if (
                $products['patrimony'] == true) {
 
                $products['patrimony'] = 1;
        
            } else {

                $products['patrimony'] = 0;

            }

        //}
        $products['patrimony_code'] = (int) $products['patrimony_code'];

        
        //var_dump($products);
        //die;
        
        $products = $this->entityFactory->createProducts($products);
        
        // 2 - CADASTRO DO PRODUTO
        $idProduct = $this->productsModel->add($products);



        // 3 - Recupera e trata as informações da interface para o CADASTRO DE REMESSA;
        $remessa = $request->getParsedBody();
         
        if ($remessa['isRemessaInicial'] == 'true') {
            $remessa['remessa_type'] = (int) $remessa['id_remessa_type'];
            //$remessa['id_product'] = (int) $remessa['id_product'];
            $remessa['id_remessa_type'] = (int) $remessa['id_remessa_type'];
            $remessa['quantity'] = (int) $remessa['quantity'];
            //$remessa['cost'] =  $remessa['cost'];
            $remessa['id_product'] = $idProduct;

            $remessa = $this->entityFactory->createRemessa($remessa);
            
            // 4 - Cadastro de remessa
            $idRemessa = $this->remessaModel->add($remessa);
        
        }
        

        // tratamento de eventlogs
        if ( ($idProduct != null) || ($idProduct != false) ) {
            // 5 - tratamento para criar event log do CADASTRO DO PRODUTO
            $eventLog['id_products'] = $idProduct;
            
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('create_products')->id;
            $eventLog['description'] = 'Produto ' . $products->name .' cadastrado';
            $eventLog = $this->entityFactory->createEventLog($eventLog);

            // 5 - linha que adicona o EVENTO DE CADASTRO DO PRODUTO
            $this->eventLogModel->add($eventLog);

            // conteúdo da interface
            $body = $request->getParsedBody();

            if ($body['isRemessaInicial'] == 'true') {
                // 6 - tratamento de event logs 
                if ($remessa->remessa_type == 1){

                    $eventLog1['id_remessa'] = $idRemessa;

                    $eventLog1['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_doacao')->id;

                    $eventLog1['description'] = 'Remessa inicial para o produto ' . $products->name .'.';
                   // $eventLog1['id_products'] = $remessa->id_product;
                    $eventLog1 = $this->entityFactory->createEventLog($eventLog1);
                    $this->eventLogModel->add($eventLog1);

                } elseif ($remessa->remessa_type == 2){
                    $eventLog1['id_remessa'] = $idRemessa;
                    
                    $eventLog1['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_compra')->id;
                    $eventLog1['description'] = 'Remessa inicial para o produto ' . $products->name .'.';
                    $eventLog1['id_products'] = $remessa->id_product;
                    $eventLog1 = $this->entityFactory->createEventLog($eventLog1);
                    $this->eventLogModel->add($eventLog1);
                    
                } elseif ($remessa->remessa_type == 3){
                    $eventLog1['id_remessa'] = $idRemessa;
                    
                    $eventLog1['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_inicial')->id;
                    $eventLog1['description'] = 'Remessa inicial para o produto ' . $products->name .'.';
                    $eventLog1['id_products'] = $remessa->id_product;
                    $eventLog1 = $this->entityFactory->createEventLog($eventLog1);
                    $this->eventLogModel->add($eventLog1);
                }
            }

            
        } 
        $this->flash->addMessage('success', 'Produto adicionada com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/products');

    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $this->productsModel->delete($id);
        $this->flash->addMessage('success', 'Produto removido com sucesso.');
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
         $products['id_supplier'] = (int) $products['id_supplier'];

        $old_product = $this->productsModel->get($products['id']);
        $products['quantity'] = (int) $old_product->quantity;

        $products = $this->entityFactory->createProducts($products);

        $this->productsModel->update($products);
         //$eventLog = $this->entityFactory->createEventLog($eventLog);
           // $this->eventLogModel->add($eventLog);

        // if it's all ok with updates, create event log


            $eventLog['id_products']         = $products->id;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('edit_products')->id;
            $eventLog['description'] = 'Produto ' . $user->name .' atualizado';

            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);

        $this->flash->addMessage('success', 'Produto atualizado com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/products');

    }
}
