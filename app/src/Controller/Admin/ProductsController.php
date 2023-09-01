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
    protected $patientModel;

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
        Model $patientModel,

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
        $this->patientModel         = $patientModel;
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
        if( $offset < 0 ) $offset = 0;
        // get products list
        $products = $this->productsModel->getAll($offset, $limit);
        // var_dump($products);die;
        // remessa types
        $remessaTypes = $this->remessaTypeModel->getAll();
        // var_dump($products);die;
        foreach ($products as $product) {
            //buscando todos os produto_remessa por id do product
            $produtos_remessa = $this->produtoRemessaModel->getAllByProduct((int) $product->id);
            //var_dump($produtos_remessa);die;
            //armazena quantidade temporaria
            $quantidade = 0;
            $contador_remessa = 0;
            $contador_custo = 0;
            $soma_custo = 0;
            $multiplica = 0;
            //foreach para incrementar a quantidade dos produto_remessa
            foreach($produtos_remessa as $produto_remessa) {
                //variavel armazena remessa por id
                $remessa = $this->remessaModel->getRemovido((int) $produto_remessa->id_remessa);
                //die;
                //ifs para verificar tipo de entrada e faz soma/ subtracao na variavel quantidade
                if ($remessa->removido != '1'){
                    //var_dump($remessa);
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
                    if (isset($remessa->remessa_type)) {
                        if ($remessa->remessa_type == '7'){
                            $quantidade = $quantidade + $produto_remessa->quantity;
                        }
                    }
                    if (isset($remessa->remessa_type)) {
                        if ($remessa->remessa_type == '8'){
                            $quantidade = $quantidade - $produto_remessa->quantity;
                        }
                    }
                }
            }//die;
            $product->quantity = $quantidade;
            $custo_medio = 0;
            $custo_total = 0;
            $quantidade = 0;
            $product->cost = $custo_medio;
            foreach($produtos_remessa as $produto_remessa) {
                $remessa = $this->remessaModel->get((int) $produto_remessa->id_remessa);
                if (isset($remessa->remessa_type)) {
                    if ($remessa->remessa_type == '2'){
                        $produto_remessa->cost = str_replace(".","",$produto_remessa->cost);
                        $float_cost = floatval(str_replace(',','.',$produto_remessa->cost));
                        $quantidade = $quantidade + $produto_remessa->quantity;
                        $custo_total = $custo_total + $float_cost;
                    }
                }
            }
            if ($quantidade == 0) {
                $custo_medio = 0;
            } else {
                $custo_medio = $multiplica / $quantidade;
                $product->cost = $custo_medio;
            }
        }
        //foreach para remover produtos zerados ou negativos
        // foreach($products as $key => $product) {
        //     // var_dump($product->quantity);//die;
        //     if($product->quantity <= 0) {
        //         unset($products[$key]);
        //     }
        //     $product->cost = number_format($product->cost, 2, ',', '.');
        // }
        // var_dump($products);die;
        $amountProducts = $this->productsModel->getAmount()->amount;
        //abaixo, tem um amount de produtos caso a lista n venha a exibir produtos zerados ou negativos
        // $amountProducts = count($products);
        // var_dump($amountProducts);die;
        // $products = array_chunk($products, 10, true);
        // $products = array_slice($products, $offset, $limit );
        // var_dump($products);die;
        // var_dump($amountProducts);die;
        $amountPages = ceil($amountProducts / $limit);
        // var_dump($amountPages);die;
        // get quantity from remessas
        
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
        $products['patrimony_code'] = (int) $products['patrimony_code'];
        if (isset($products['patrimony'])) {
            if ( $products['patrimony'] == true) {
                $products['patrimony'] = 1;
            } else {
                $products['patrimony'] = 0;
            }
        }
        $products = $this->entityFactory->createProducts($products);
        // 2 - CADASTRO DO PRODUTO
        $idProduct = $this->productsModel->add($products);
        $lista_produto[0]['id_product'] = $idProduct;
        $lista_produto = json_encode($lista_produto);
        // 3 - Recupera e trata as informações da interface para o CADASTRO DE REMESSA;
        $remessa = $request->getParsedBody();
        try {
            $this->remessaModel->beginTransaction();
            if ($remessa['isRemessaInicial'] == 'true') {
                $remessa['remessa_type'] = (int) $remessa['id_remessa_type'];
                $remessa['id_remessa_type'] = (int) $remessa['id_remessa_type'];
                //$remessa['id_product'] = (int) $idProduct;
                $remessa['suppliers'] =  (int) $products->id_supplier;
                //$remessa['patrimony_code'] = (int) $remessa['patrimony_code'];
                $remessa['cost'] = NULL;
                $remessa['quantity'] = NULL;
                $remessa_obj = $this->entityFactory->createRemessa($remessa);
                // 4 - Cadastro de remessa
                $idRemessa = $this->remessaModel->add($remessa_obj);
                if ($idRemessa == false) {
                    throw new ModelException($idRemessa, "Erro no cadastro de Remessa. COD:0001.");
                }
                $remessa_obj->id = (int)$idRemessa;
                $remessa_obj->id_remessa = (int) $idRemessa;
                $remessa_obj->isRemessaInicial = $remessa['isRemessaInicial'];
                //var_dump($remessa);
                //die;
                $data = $request->getQueryParams();
                $data["id_product"] = (int) $idProduct;
                $data["id_remessa"] = (int) $idRemessa;
                $data["patrimony_code"] = $products->patrimony;
                $data["cost"] = (int) $products->cost;
                $data["quantity"] = $products->quantity;
                $data["suppliers"] = (int) $products->id_supplier;
                $data = $this->entityFactory->createProdutoRemessa($data);
                $data->id = $this->produtoRemessaModel->add($data);
                if ($data->id == false) {
                    throw new ModelException($idRemessa, "Erro no cadastro de Remessa. COD:0001.");
                }
                // var_dump($data);die;
                if ($remessa->isRemessaInicial == 'false') {
                    $lista_produto[0]['id_product'] = $idProduct;
                    $lista_produto = json_encode($lista_produto);
                } else {
                }
            }
            // tratamento de eventlogs
            if ( ($idProduct != null) || ($idProduct != false) ) {
                $eventLog['product_list'] = $lista_produto;
                // 5 - tratamento para criar event log do CADASTRO DO PRODUTO
                $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('create_products')->id;
                $eventLog['suppliers'] =  $products->id_supplier;
                $eventLog['id_products'] =  (int)$idProduct;
                $eventLog['description'] = 'Produto ' . $products->name .' cadastrado';
                $eventLog = $this->entityFactory->createEventLog($eventLog);
                // 5 - linha que adicona o EVENTO DE CADASTRO DO PRODUTO
                $this->eventLogModel->add($eventLog);
                // conteúdo da interface
                $body = $request->getParsedBody();
                if ($body['isRemessaInicial'] == 'true') {
                    $eventLog1['product_list'] = $lista_produto;
                    // 6 - tratamento de event logs
                    if ($remessa->remessa_type == 1){
                        $eventLog1['id_remessa'] = $idRemessa;
                        $eventLog1['suppliers'] =  $products->id_supplier;
                        $eventLog1['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_doacao')->id;
                        $eventLog1['description'] = 'Remessa inicial para o produto ' . $products->name .'.';
                        $eventLog1 = $this->entityFactory->createEventLog($eventLog1);
                        $return_eventLog = $this->eventLogModel->add($eventLog1);
                        if ($return_eventLog->data == false) {
                        throw new ModelException($return_eventLog, "Erro no cadastro de Remessa. COD:0001.");
                        }
                    } elseif ($remessa->remessa_type == 2){
                        $eventLog1['id_remessa'] = $idRemessa;
                        $eventLog1['suppliers'] =  $products->id_supplier;
                        $eventLog1['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_compra')->id;
                        $eventLog1['description'] = 'Remessa inicial para o produto ' . $products->name .'.';
                        $eventLog1['id_products'] = $remessa->id_product;
                        $eventLog1 = $this->entityFactory->createEventLog($eventLog1);
                        $return_eventLog = $this->eventLogModel->add($eventLog1);
                        if ($return_eventLog->data == false) {
                        throw new ModelException($return_eventLog, "Erro no cadastro de Remessa. COD:0002.");
                        }
                    } elseif ($remessa->remessa_type == 3){
                        $eventLog1['id_remessa'] = $idRemessa;
                        $eventLog1['suppliers'] =  $products->id_supplier;
                        $eventLog1['event_log_type']  = $this->eventLogTypeModel->getBySlug('entrada_inicial')->id;
                        $eventLog1['description'] = 'Remessa inicial para o produto ' . $products->name .'.';
                        $eventLog1 = $this->entityFactory->createEventLog($eventLog1);
                        $return_eventLog = $this->eventLogModel->add($eventLog1);
                        if ($return_eventLog->data == false) {
                        throw new ModelException($return_eventLog, "Erro no cadastro de Remessa. COD:0003.");
                        }
                    }
                }
            }
            $this->remessaModel->commit();
            $this->flash->addMessage('success', 'Remessa adicionada com sucesso.');
            // return $this->httpRedirect($request, $response, '/admin/remessa');
        } catch (ModelException $e) {
            $this->remessaModel->rollback();
            CustomLogger::ModelErrorLog($e->getMessage(), $e->getdata());
            $this->flash->addMessage('danger', $e->getMessage() . ' Se o problema persistir contate um administrador.');
            // return $this->httpRedirect($request, $response, "/admin/remessa");
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
        $id_supplier = $products->id_supplier;
        //var_dump($products);
        //die;
        if (!$products) {
            $this->flash->addMessage('danger', 'Produto não encontrado.');
            return $this->httpRedirect($request, $response, '/admin/products');
        }
         $id_supplier = $this->supplierModel->getAll();
         $products_type = $this->productsTypeModel->getAll();
            return $this->view->render($response, 'admin/products/edit.twig', [
                'products' => $products,
                'products_type' => $products_type,
                'id_supplier' => $id_supplier,
            ]);
    }
    //download
    public function export(Request $request, Response $response)
    {
        $products = $this->productsModel->getAll();
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
                //var_dump($remessa->removido);die;
                //ifs para verificar tipo de entrada e faz soma/ subtracao na variavel quantidade
                if ($remessa->removido != '1'){
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
                    if (isset($remessa->remessa_type)) {
                        if ($remessa->remessa_type == '7'){
                            $quantidade = $quantidade + $produto_remessa->quantity;
                        }
                    }
                    if (isset($remessa->remessa_type)) {
                        if ($remessa->remessa_type == '8'){
                            $quantidade = $quantidade - $produto_remessa->quantity;
                        }
                    }
                }
            }
            $product->quantity = $quantidade;
            $custo_medio = 0;
            $custo_total = 0;
            $quantidade = 0;
            $product->cost = $custo_medio;
            foreach($produtos_remessa as $produto_remessa) {
                $remessa = $this->remessaModel->get((int) $produto_remessa->id_remessa);
                if (isset($remessa->remessa_type)) {
                    if ($remessa->remessa_type == '2'){
                        $produto_remessa->cost = str_replace(".","",$produto_remessa->cost);
                        $float_cost = floatval(str_replace(',','.',$produto_remessa->cost));
                        $quantidade = $quantidade + $produto_remessa->quantity;
                        $custo_total = $custo_total + $float_cost;
                    }
                }
            }
            if ($quantidade == 0) {
                $custo_medio = 0;
            } else {
                $custo_medio = $multiplica / $quantidade;
                $product->cost = $custo_medio;
            }
        }
        foreach($products as $key => $product) {
            if($product->quantity <= 0) {
                unset($products[$key]);
            }
            $product->cost = number_format($product->cost, 2, ',', '.');
        }
        $html = "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <p style=' '>Fundação Waldyr Becker de Apoio ao Paciente com Câncer.</p>
                <h3 style='margin-top: 2px; margin-bottom: 2px;'>Relatório de Produtos Cadastrados</h3>
                <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>
            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table style='width:100%;'>
                <tr>
                    <th style='text-align:left;'>ID</th>
                    <th style='text-align:left;'>Nome</th>
                    <th style='text-align:left;'>Descrição</th>
                    <th style='text-align:left;'>qtd</th>
                    <th style='text-align:left;'>Custo Médio</th>
                </tr>
        ";
        foreach ($products as $product){
           $html .= "
            <tr>
                <td style='text-align:left;'>$product->id</td>
                <td style='text-align:left;'>$product->name</td>
                <td style='text-align:left;'>$product->description</td>
                <td style='text-align:left;'>$product->quantity</td>
                <td style='text-align:left;'>R$ $product->cost</td>
            </tr>";
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
        $params = $request->getQueryParams();
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
        $product = $this->productsModel->get($id);
        $suppliers = $this->supplierModel->getAll();
        $patients = $this->patientModel->getAll();
        // retorna todos os eventlogs que tenham produc_id
        //$event_logs = $this->eventLogModel->getByProducts((int)$id, (int)$search);
        //var_dump($event_logs);die;
        $remessa_produtos = $this->produtoRemessaModel->getAllByProduct((int)$id);
        $contador_quantidade_total = 0;
        $event_logs = $this->eventLogModel->getByProducts2((int)$id, $history_start, $history_finish, (int)$search);
        $remessa_produtos = $this->produtoRemessaModel->getAllByProduct($id);
        $html = "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <h3 style='margin-top: 0px; margin-bottom: 2px;'>Histórico de Produto</h3>
                <p> <strong>Produto:</strong> $product->name </p>
                <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>
            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table style='width:100%;'>
                <tr>
                    <th style='text-align:left;width:100px;'>Data de Remessa</th>
                    <th style='text-align:left;width:100px;'>Data de Cadastro</th>
                    <th style='text-align:left;'>Tipo</th>
                    <th style='text-align:right;'>qtd</th>
                    <th style='text-align:left;'>Custo</th>
                    <th style='text-align:left;'>Fornecedor/Paciente</th>
                </tr>
            ";
            foreach ($event_logs as $event_log) {
                //var_dump($event_log);
                $event_log->suppliers_name ="";
                $remessa_atual = $this->remessaModel->get((int)$event_log->id_remessa);
                if ($remessa_atual != null) {
                    $event_log->removido = $remessa_atual->removido;
                    $event_log->data_remessa = date("d/m/Y", strtotime($remessa_atual->date));
                } else {
                    $event_log->removido = '0';
                    $event_log->data_remessa = "- - - -";
                }
                //var_dump($event_log);
                $event_log->date = date("d/m/Y", strtotime($event_log->date));
                if (($event_log->event_log_type == 12) || ($event_log->event_log_type == 13)  || ($event_log->event_log_type == 14)) {
                if(($event_log->suppliers != NULL) && ($event_log->suppliers != '0')){
                    $event_log->suppliers_name = $this->supplierModel->get((int)$event_log->suppliers)->name;
                } else {
                    $event_log->suppliers_name = '- - - - - ';
                }
                //$event_log->suppliers_name = $this->supplierModel->get((int)$event_log->suppliers)->name;
                }
                if (($event_log->event_log_type == 15) || ($event_log->event_log_type == 16) || ($event_log->event_log_type == 9)) {
                $event_log->patient_name = $this->patientModel->get((int) $event_log->id_patient)->name;
                }
                foreach($remessa_produtos as $remessa_produto) {
                    if ($event_log->id_remessa == $remessa_produto->id_remessa) {

                        $event_log->cost = (($remessa_produto->cost != 'undefined') && ($remessa_produto->cost != '')) ? $remessa_produto->cost : null;
                        $type = $remessa_produto->remessa_type;
                        if (($type == '1') || ($type == '2') || ($type == '3') || ($type == '6') || ($type == '7')) {
                            $event_log->quantity = "+$remessa_produto->quantity";
                            $contador_quantidade_total = $contador_quantidade_total + $remessa_produto->quantity;
                        }
                        if (($type == '4') || ($type == '5') || ($type == '8')) {
                            $event_log->quantity = "-  $remessa_produto->quantity";
                            $contador_quantidade_total = $contador_quantidade_total - $remessa_produto->quantity;
                        }
                    }
                }
                if (!isset($event_log->cost)) {
                    $event_log->cost = '---';
                }
                if (!isset($event_log->quantity)) {
                    $event_log->quantity = '---';
                }
                
                if($event_log->removido !='1'){
                    $html .="
                    <tr>
                        <td style='text-align:left;'>$event_log->data_remessa </td>
                        <td style='text-align:left;'>$event_log->date</td>
                        <td style='text-align:left;'>$event_log->event_log_types_name</td>
                        <td style='text-align:right;'>";
                            $html .="$event_log->quantity</td>
                        <td style='text-align:left;'>R$";
                        if ($event_log->cost == '0') {
                            $html .= '-----';
                        } elseif ($event_log->cost == null) {
                            $html .= '-----';
                        } else {
                            $html .= "$event_log->cost";
                        }
                        if (($event_log->event_log_type == 15) || ($event_log->event_log_type == 16) || ($event_log->event_log_type == 9)) {
                            $html .="<td style='text-align:left;'>$event_log->patient_name</td>";
                        } else if (($event_log->event_log_type == 12) || ($event_log->event_log_type == 13)  || ($event_log->event_log_type == 14)) {
                            if(($event_log->suppliers != NULL) && ($event_log->suppliers != '0')){
          
                                $event_log->suppliers_name = $this->supplierModel->get((int)$event_log->suppliers)->name;
                            } else {
                            
                                $event_log->suppliers_name = '- - - - - ';
                            }
                            $html .="<td style='text-align:left;'>$event_log->suppliers_name
                            </td>";
                        } else {
                            $html .= "<td style='text-align:left;'> - - - -
                            </td>";
                        }
                    $html .= "
                    </tr> ";
                }
            }//die;
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

    public function export_history_remessa(Request $request, Response $response) {
        $id = (int)$request->getQueryParams()['id'];
        $params = $request->getQueryParams();
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
        $product = $this->productsModel->get($id);
        $suppliers = $this->supplierModel->getAll();
        $patients = $this->patientModel->getAll();
        // retorna todos os eventlogs que tenham produc_id
        //$event_logs = $this->eventLogModel->getByProducts((int)$id, (int)$search);
        //var_dump($event_logs);die;
        $remessa_produtos = $this->produtoRemessaModel->getAllByProduct((int)$id);
        $contador_quantidade_total = 0;
        $event_logs = $this->eventLogModel->getByProducts2((int)$id, $history_start, $history_finish, (int)$search);
        $remessa_produtos = $this->produtoRemessaModel->getAllByProduct($id);
        $html = "
            <div style='width: 24%; float:left;'>
                <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
            </div>
            <div style='width: 75%;'>
                <h3 style='margin-top: 0px; margin-bottom: 2px;'>Histórico de Produto</h3>
                <p> <strong>Produto:</strong> $product->name </p>
                <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>
            </div>
            <hr>
            <div style='width:100%; margin-top: 10px;'>
            <table style='width:100%;'>
                <tr>
                    <th style='text-align:left;width:100px;'>Data de Remessa</th>
                    <th style='text-align:left;width:100px;'>Data de Cadastro</th>
                    <th style='text-align:left;'>Tipo</th>
                    <th style='text-align:right;'>qtd</th>
                    <th style='text-align:left;'>Custo</th>
                    <th style='text-align:left;'>Fornecedor/Paciente</th>
                </tr>
            ";
            foreach ($event_logs as $event_log) {//var_dump($event_log);
            $event_log->suppliers_name ="";
            $remessa_atual = $this->remessaModel->get((int)$event_log->id_remessa);
            //var_dump($remessa_atual);die;
            if (($remessa_atual != null) && ($remessa_atual->removido !='1')) {
                $event_log->removido = $remessa_atual->removido;
                $event_log->data_remessa = date("d/m/Y", strtotime($remessa_atual->date));
                //var_dump($event_log->data_remessa);
                $event_log->date = date("d/m/Y", strtotime($event_log->date));
                if (($event_log->event_log_type == 12) || ($event_log->event_log_type == 13)  || ($event_log->event_log_type == 14)) {
                    if(($event_log->suppliers != NULL) && ($event_log->suppliers != '0')){
          
                        $event_log->suppliers_name = $this->supplierModel->get((int)$event_log->suppliers)->name;
                    } else {
                    
                        $event_log->suppliers_name = '- - - - - ';
                    }
                }
                if (($event_log->event_log_type == 15) || ($event_log->event_log_type == 16) || ($event_log->event_log_type == 9)) {
                    $event_log->patient_name = $this->patientModel->get((int) $event_log->id_patient)->name;
                }
                foreach($remessa_produtos as $remessa_produto) {
                    if ($event_log->id_remessa == $remessa_produto->id_remessa) {

                        $event_log->cost = (($remessa_produto->cost != 'undefined') && ($remessa_produto->cost != '')) ? $remessa_produto->cost : null;
                        $type = $remessa_produto->remessa_type;
                        if (($type == '1') || ($type == '2') || ($type == '3') || ($type == '6') || ($type == '7')) {
                            $event_log->quantity = "+$remessa_produto->quantity";
                            $contador_quantidade_total = $contador_quantidade_total + $remessa_produto->quantity;
                        }
                        if (($type == '4') || ($type == '5') || ($type == '8')) {
                            $event_log->quantity = "-  $remessa_produto->quantity";
                            $contador_quantidade_total = $contador_quantidade_total - $remessa_produto->quantity;
                        }
                    }
                }
                if (!isset($event_log->cost)) {
                    $event_log->cost = '---';
                }
                if (!isset($event_log->quantity)) {
                    $event_log->quantity = '---';
                }
                $html .="
                <tr>
                    <td style='text-align:left;'>$event_log->data_remessa</td>
                    <td style='text-align:left;'>$event_log->date</td>
                    <td style='text-align:left;'>$event_log->event_log_types_name</td>
                    <td style='text-align:right;'>";
                        $html .="$event_log->quantity</td>
                    <td style='text-align:left;'>R$";
                    if ($event_log->cost == '0') {
                        $html .= '-----';
                    } elseif ($event_log->cost == null) {
                        $html .= '-----';
                    } else {
                        $html .= "$event_log->cost";
                    }
                    if (($event_log->event_log_type == 15) || ($event_log->event_log_type == 16) || ($event_log->event_log_type == 9)) {
                        $html .="<td style='text-align:left;'>$event_log->patient_name</td>";
                    } else if (($event_log->event_log_type == 12) || ($event_log->event_log_type == 13)  || ($event_log->event_log_type == 14)) {
                        if(($event_log->suppliers != NULL) && ($event_log->suppliers != '0')){
          
                            $event_log->suppliers_name = $this->supplierModel->get((int)$event_log->suppliers)->name;
                        } else {
                        
                            $event_log->suppliers_name = '- - - - - ';
                        }
                        $html .="<td style='text-align:left;'>$event_log->suppliers_name
                        </td>";
                    }
                }

                $html .= "
                </tr> ";
            }//die;
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

    public function history(Request $request, Response $response, array $args)
    {
        $params = $request->getQueryParams();

        $search = isset($request->getQueryParams()['search']) ? $request->getQueryParams()['search'] : 0;
        $id = intval($args['id']);
        $products = $this->productsModel->get($id);
        $suppliers = $this->supplierModel->getAll();
        $patients = $this->patientModel->getAll();
        // retorna todos os eventlogs que tenham produc_id
        $event_logs = $this->eventLogModel->getByProducts((int)$id, (int)$search);
        $remessa_produtos = $this->produtoRemessaModel->getAllByProduct($id);
        //var_dump($remessa_produtos);die;
        $contador_quantidade_total = 0;//var_dump($event_logs);die;
        $remessa_atual = "";
        foreach ($event_logs as $event_log) {
            $remessa_atual = $this->remessaModel->get((int)$event_log->id_remessa);
            //var_dump($remessa_atual);die;
            if ($remessa_atual != null) {
                $event_log->removido = $remessa_atual->removido;
                $event_log->data_remessa = date("d/m/Y", strtotime($remessa_atual->date));
            }

            $event_log->date = date("d/m/Y", strtotime($event_log->date));
            //var_dump($event_log);die;
            if (($event_log->event_log_type == 12) || ($event_log->event_log_type == 13)  || ($event_log->event_log_type == 14)) {
                if(($event_log->suppliers != NULL) && ($event_log->suppliers != '0')){
          
                    
                    $event_log->suppliers_name = $this->supplierModel->get((int)$event_log->suppliers)->name;
                  } else {
                    
                  $event_log->suppliers_name = '- - - - - ';
                  }
            }
            if (($event_log->event_log_type == 15) || ($event_log->event_log_type == 16) || ($event_log->event_log_type == 9)) {
            $event_log->patient_name = $this->patientModel->get((int) $event_log->id_patient)->name;
            }//var_dump($event_log);die;
            foreach($remessa_produtos as $remessa_produto) {
                //
                //var_dump($remessa_atual);
                if ($event_log->id_remessa == $remessa_produto->id_remessa) {

                    $event_log->cost = (($remessa_produto->cost != 'undefined') && ($remessa_produto->cost != '')) ? $remessa_produto->cost : null;
                    $type = $remessa_produto->remessa_type;
                    if (($type == '1') || ($type == '2') || ($type == '3') || ($type == '6') || ($type == '7')) {
                        $event_log->quantity = "+$remessa_produto->quantity";
                        $contador_quantidade_total = $contador_quantidade_total + $remessa_produto->quantity;
                    }
                    if (($type == '4') || ($type == '5') || ($type == '8')) {
                        $event_log->quantity = "-  $remessa_produto->quantity";
                        $contador_quantidade_total = $contador_quantidade_total - $remessa_produto->quantity;
                    }
                }
            }
            if (!isset($event_log->cost)) {
                $event_log->cost = '---';
            }
            if (!isset($event_log->quantity)) {
                $event_log->quantity = '---';
            }
        }
        return $this->view->render($response, 'admin/products/history.twig', [
            'products' => $products,
            'event_logs' => $event_logs,
            'remessa_atual' =>$remessa_atual,
            'search' => $search
        ]);
    }
    public function update(Request $request, Response $response): Response
    {
        // $data = $request->getParsedBody();
        $products = $request->getParsedBody();
        $products['id'] = (int) $products['id'];
        //$products['id_products'] = (int) $data['id_products'];
        $products['category'] = (int) $products['id_products_type'];
        $products['id_supplier'] = (int) $products['id_supplier'];
        $products['patrimony'] = (int) $products['patrimony'];
        $old_product = $this->productsModel->get($products['id']);
        $products['quantity'] = (int) $old_product->quantity;
        $products['cost'] = (int) $old_product->cost;
        $products = $this->entityFactory->createProducts($products);
        $return_product = $this->productsModel->update($products);
        // var_dump($return_product);die;
        if($return_product != false){
            $eventLog['id_products']         = $products->id;
            $eventLog['suppliers']         = $products->id_supplier;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('edit_products')->id;
            $eventLog['description'] = 'Produto ' . $products->name .' atualizado';
            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);
        }
        $this->flash->addMessage('success', 'Produto atualizado com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/products');
    }
}
