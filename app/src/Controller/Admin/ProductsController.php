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
    protected $produtoRemessaModel;
    protected $remessaModel;
    protected $remessaTypeModel;
    protected $supplierModel;
    protected $userModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;
    

    public function __construct( View $view, FlashMessages $flash,
        Model $productsModel,
        Model $productsTypeModel,
        Model $produtoRemessaModel,
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
        $this->produtoRemessaModel  = $produtoRemessaModel;
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
          
             
        // get quantity from remessas
        foreach ($products as $product) {

            //buscando todos os produto_remessa por id do product
            $produtos_remessa = $this->produtoRemessaModel->getAllByProduct((int) $product->id);
            //var_dump($produtos_remessa);    
            //armazena quantidade temporaria
            $quantidade = 0;
            $contador_remessa = 0;
            $contador_custo = 0;
            $soma_custo = 0;
            $multiplica = 0;
            //foreach para incrementar a quantidade dos produto_remessa
            foreach($produtos_remessa as $produto_remessa) {
                //variavel armazena remessa por id
                $remessa = $this->remessaModel->get((int) $produto_remessa->id_remessa);
               //
                //ifs para verificar tipo de entrada e faz soma/ subtracao na variavel quantidade
            if (isset($remessa->remessa_type)) {
                if ($remessa->remessa_type == '1'){
                    $quantidade = $quantidade + $produto_remessa->quantity;
                }
            }

            if (isset($remessa->remessa_type)) {
                if ($remessa->remessa_type == '2'){
                    $quantidade = $quantidade + $produto_remessa->quantity;
                    $contador_remessa = $contador_remessa + 1;

                    $produto_remessa->cost = str_replace(".","",$produto_remessa->cost);
                    $float_cost = floatval(str_replace(',','.',$produto_remessa->cost));

                    $soma_custo = $soma_custo + ((int)$produto_remessa->cost);

                    $multiplica = $multiplica + ($produto_remessa->quantity * $float_cost);

                    //var_dump($float_cost);
                }
            }

            if (isset($remessa->remessa_type)) {
                if ($remessa->remessa_type == '3'){
                    $quantidade = $quantidade + $produto_remessa->quantity;
                }
            }

            if (isset($remessa->remessa_type)) {
                if ($remessa->remessa_type == '4'){
                    $quantidade = $quantidade - $produto_remessa->quantity;
                }
            }

            if (isset($remessa->remessa_type)) {
                if ($remessa->remessa_type == '5'){
                    $quantidade = $quantidade - $produto_remessa->quantity;
                }
            }

            if (isset($remessa->remessa_type)) {
                if ($remessa->remessa_type == '6'){
                    $quantidade = $quantidade + $produto_remessa->quantity;

                }              
            }

            }
            //var_dump($contador_remessa);
            //die;
            //recebe o valor da variavel quantidade
            $product->quantity = $quantidade;
            

            $custo_medio = 0;
            $custo_total = 0;
            $quantidade = 0;
            $product->cost = $custo_medio; 
            //foreach para incrementar custo total em cost (esse calculo se faz somente com entrada tipo 2 = compra)

             //var_dump($product);
        //die;
            foreach($produtos_remessa as $produto_remessa) {
                 $remessa = $this->remessaModel->get((int) $produto_remessa->id_remessa);
            if (isset($remessa->remessa_type)) {
                 if ($remessa->remessa_type == '2'){

                    $produto_remessa->cost = str_replace(".","",$produto_remessa->cost);
                    $float_cost = floatval(str_replace(',','.',$produto_remessa->cost));
                    //$custo_total = $float_cost * ((int)$product_remessa->quantity);

                    $quantidade = $quantidade + $produto_remessa->quantity;
                    $custo_total = $custo_total + $float_cost;

                   
                    //var_dump($quantidade);
                    //die;
                }
            }
        }

            if ($quantidade == 0) {

                $custo_medio = 0;

            } else {


                

                $custo_medio = $multiplica / $quantidade;
                //var_dump($custo_medio);
                //die;

                $product->cost = $custo_medio;

            }
           
            //var_dump($multiplica);

        }
        //die;



     
        return $this->view->render($response, 'admin/products/index.twig', 
        [
            'products' => $products,      
            'remessaTypes' => $remessaTypes,
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
            //$remessaTypes = $this->remessaTypeModel->getAll();
            $id_supplier = $this->supplierModel->getAll();

            $remessaTypes[] = $this->remessaTypeModel->get(1);
            $remessaTypes[] = $this->remessaTypeModel->get(2);
            $remessaTypes[] = $this->remessaTypeModel->get(3);
            $remessaTypes[] = $this->remessaTypeModel->get(6);

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
       // var_dump($products);

            if (
                $products['patrimony'] == true) {
 
                $products['patrimony'] = 1;
        
            } else {

                $products['patrimony'] = 0;

            }

        //}

        
        //var_dump($products);
       // die;
        
        $products = $this->entityFactory->createProducts($products);
        
        // 2 - CADASTRO DO PRODUTO
        $idProduct = $this->productsModel->add($products);

        // 3 - Recupera e trata as informações da interface para o CADASTRO DE REMESSA;
        $remessa = $request->getParsedBody();
           
           
        if ($remessa['isRemessaInicial'] == 'true') {
            $remessa['remessa_type'] = (int) $remessa['id_remessa_type'];
            $remessa['id_remessa_type'] = (int) $remessa['id_remessa_type'];
            //$remessa['id_product'] = (int) $idProduct;
            $remessa['suppliers'] =  (int) $products->id_supplier;
            //$remessa['patrimony_code'] = (int) $remessa['patrimony_code'];
            $remessa['cost'] = NULL;
            $remessa['quantity'] = NULL;
            

            $remessa = $this->entityFactory->createRemessa($remessa);
       
            // 4 - Cadastro de remessa
            $idRemessa = $this->remessaModel->add($remessa);
             
            $remessa->id = (int)$remessa->id = $idRemessa;
            $remessa->id_remessa = (int) $idRemessa;
           //var_dump($remessa);
           // die;
            $data = $request->getQueryParams();
                
        
        $data["id_product"] = (int) $idProduct;
        $data["id_remessa"] = (int) $idRemessa;
        $data["patrimony_code"] = $products->patrimony;
        $data["cost"] = (int) $products->cost;
        $data["quantity"] = $products->quantity;

        //var_dump($data);
        //die;

        $data = $this->entityFactory->createProdutoRemessa($data);

        $data->id = $this->produtoRemessaModel->add($data);
           
           //  var_dump($products_remessa);

        $lista_produto[0]['id_product'] = $idProduct;

        $lista_produto = json_encode($lista_produto);       
    }
        

        // tratamento de eventlogs
        if ( ($idProduct != null) || ($idProduct != false) ) {

             $eventLog['product_list'] = $lista_produto;
            // 5 - tratamento para criar event log do CADASTRO DO PRODUTO
           
            
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('create_products')->id;
            $eventLog['suppliers'] =  $products->id_supplier;
            $eventLog['description'] = 'Produto ' . $products->name .' cadastrado';
            $eventLog = $this->entityFactory->createEventLog($eventLog);

            // 5 - linha que adicona o EVENTO DE CADASTRO DO PRODUTO
            $this->eventLogModel->add($eventLog);

            // conteúdo da interface
            $body = $request->getParsedBody();
            //var_dump($eventLog);
            //die;
            if ($body['isRemessaInicial'] == 'true') {

                $eventLog1['product_list'] = $lista_produto;
                // 6 - tratamento de event logs 
                if ($remessa->remessa_type == 1){

                    

                    $eventLog1['id_remessa'] = $idRemessa;
                    $eventLog1['suppliers'] =  $products->id_supplier;
                    $eventLog1['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_doacao')->id;

                    $eventLog1['description'] = 'Remessa inicial para o produto ' . $products->name .'.';
                    $eventLog1 = $this->entityFactory->createEventLog($eventLog1);
                    $this->eventLogModel->add($eventLog1);
                    //var_dump($eventLog);
                    //var_dump($eventLog1);
       

                } elseif ($remessa->remessa_type == 2){
                    $eventLog1['id_remessa'] = $idRemessa;
                    $eventLog1['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_compra')->id;
                    $eventLog1['description'] = 'Remessa inicial para o produto ' . $products->name .'.';
                    $eventLog1['id_products'] = $remessa->id_product;
                    $eventLog1 = $this->entityFactory->createEventLog($eventLog1);
                    $this->eventLogModel->add($eventLog1);
                    
                } elseif ($remessa->remessa_type == 3){
                    $eventLog1['id_remessa'] = $idRemessa;
                    $eventLog['suppliers'] =  $products->id_supplier;
                    $eventLog1['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_inicial')->id;
                    $eventLog1['description'] = 'Remessa inicial para o produto ' . $products->name .'.';
                    $eventLog1['id_products'] = $remessa->id_product;
                    $eventLog1 = $this->entityFactory->createEventLog($eventLog1);
                    $this->eventLogModel->add($eventLog1);
                }
            }

            // die;
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
                    
                </tr>
        ";
        foreach ($products as $product){
            //var_dump($product);
            //die;
           
           
           $html .= "
            <tr>
                <td style='width: 20%; text-align:left;'>$product->name</td>
                <td style='width: 20%; text-align:left;'>$product->description</td>
                <td style='width: 10%; text-align:left;'>$product->products_type_name</td>
               
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
        $suppliers = $this->supplierModel->getAll();

        // retorna todos os eventlogs que tenham produc_id  
        $event_logs = $this->eventLogModel->getByProducts($id);
       
        //$event_logs['suppliers_name'] = $this->supplierModel->get((int)$suppliers)->name;


        //$event_logs_product_list = $this->eventLogModel->getAllByProductList($id);

        //var_dump($event_logs['suppliers_name'] = $this->supplierModel->get((int)$suppliers)->name);
        //var_dump($product);
        //die;

        foreach ($event_logs as $event_log) {

 

            $event_log->date = date("d/m/Y h:m", strtotime($event_log->date));
            $event_log->suppliers = $this->supplierModel->get((int)$suppliers)->name;
           // $event_log->products = $this->productsModel->get((int)$products)->name;
              //var_dump($event_log);
            //die;
        }
        //var_dump($products);
        //die;
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
