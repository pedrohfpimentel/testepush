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

        $params = $request->getQueryParams();

        if (!empty($params['page'])) {
            $page = intval($params['page']);
        } else {
            $page = 1;
        }
        $limit = 20;
        $offset = ($page - 1) * $limit;
        // get products list
        $products = $this->productsModel->getAll($offset, $limit);
        // remessa types
      $remessaTypes = $this->remessaTypeModel->getAll();

      $amountProducts = $this->productsModel->getAmount();
        $amountPages = ceil($amountProducts->amount / $limit);

      // getRemessasByIdProduct function
          foreach($products as $product) {
            $cost = 0;
            $quantity = 0;               
            $remessas = $this->productsModel->getRemessasByIdProduct($product->id); 
            
                foreach($remessas as $remessa) {
                   // var_dump($remessa);
                   // die;
                    $quantity = $quantity + $remessa->quantity;
                    $cost = $remessa->cost;      
                }

            $product->quantity= $quantity;
            $product->cost= $cost;
                           
        }
        
             
        // get quantity from remessas
        foreach ($products as $product) {
        }

        return $this->view->render($response, 'admin/products/index.twig', 
        [
            'products' => $products,
            'remessaTypes' => $remessaTypes,
            'quantity'=> $quantity,
            'cost'=> $cost,
            'page' => $page,
            'amountPages' => $amountPages
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

            $patrimony = 1;

            return $this->view->render($response, 'admin/products/add.twig', 
                [
                    'products_type' => $products_type, 
                    'remessaTypes' => $remessaTypes,
                    'id_supplier'    => $id_supplier,
                    'patrimony'    => $patrimony
                    //'patrimony_code' => $patrimony_code
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
     //   $products['patrimony_code'] = (int) $products['patrimony_code'];

        
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
            $remessa['cost'] =  $remessa['cost'];
            $remessa['id_product'] = $idProduct;

            $remessa['patrimony_code'] = (int) $remessa['patrimony_code'];

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





    //download
    public function export(Request $request, Response $response)
    {

            
        

            $products = $this->productsModel->getAll();

     //var_dump($products);
            //die;

      $html = "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <p style=' '>Fundação Waldyr Becker de Apoio ao Paciente com Câncer.</p>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Relatório de Produtos Cadastrados</h3>
                <p> <strong>Data relatório:</strong>  " . date("d-m-Y") . " </p>
            
            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table>
            
                <tr>
                    <th style='width: 20%; text-align:left;'>Nome</th>
                    <th style='width: 20%; text-align:left;'>Descrição</th>
                    <th style='width: 10%; text-align:left;'>Categoria</th>
                    <th style='width: 10%; text-align:left;'>Quantidade</th>
                    <th style='width: 10%; text-align:left;'>Custo</th>
                </tr>
        ";
        foreach ($products as $product) {
            //var_dump($product);
            //die;
            
           
           $html .= "
            <tr>
                <td style='width: 20%; text-align:left;'>$product->name</td>
                <td style='width: 20%; text-align:left;'>$product->description</td>
                <td style='width: 10%; text-align:left;'>$product->products_type_name</td>
                <td style='width: 10%; text-align:left;'>$product->quantity</td>
                <td style='width: 10%; text-align:left;'>$product->cost</td>
            </tr>";
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



    public function export_history(Request $request, Response $response) {
        
        $id = (int)$request->getQueryParams()['id'];
        $product = $this->productsModel->get($id);
       // var_dump($product);
       // die;
        $event_logs = $this->eventLogModel->getByProducts($id);

        //var_dump($event_logs);
          //  die;
        $html = "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Registro do Produto</h3>
                <p> <strong>Produto:</strong> $product->name </p>
                <p> <strong>Data relatório:</strong>  " . date("d-m-Y") . " </p>
            
            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table>
            
            <tr>
                <th style='width: 33%; text-align:left;'>Data / Hora</th>
                <th style='width: 33%; text-align:left;'>Tipo</th>
                <th style='width: 33%; text-align:left;'>Descrição</th>
                
            </tr>
        ";
        foreach ($event_logs as $event_log) {
            //var_dump($event_log);
            //die;
           
            $event_log->date = date("d/m/Y h:m:s", strtotime($event_log->date));
            $html .="
            <tr>
                <td style='width: 33%; text-align:left;'>$event_log->date</td>
                <td style='width: 33%; text-align:left;'>$event_log->event_log_types_name</td>
                <td style='width: 33%; text-align:left;'>$event_log->description</td>
            
            </tr> ";
            
        }
        $html .= "</table> </div>";
        try {
            $mpdf = new \Mpdf\Mpdf();
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
        $products['cost'] = (int) $old_product->cost;

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
