<?php
declare(strict_types=1);

namespace Farol360\Ancora\Controller\Admin;

use Farol360\Ancora\Model\ModelException;
use Farol360\Ancora\CustomLogger;
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
    protected $produtoRemessaModel;
    protected $patientModel;

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
        Model $produtoRemessaModel,
        Model $patientModel,
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
        $this->produtoRemessaModel  = $produtoRemessaModel;
        $this->patientModel         = $patientModel;
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {
      $this->remessaModel->deleteByRemessaType(99);
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
      $remessa = $this->remessaModel->getAllByType([4,5,8], $offset, $limit);
      //$remessa_type = $this->remessaTypeModel->getAll();
      $remessa_type[] = $this->remessaTypeModel->get(4);
      $remessa_type[] = $this->remessaTypeModel->get(5);
      $remessa_type[] = $this->remessaTypeModel->get(8);
      //$product_name = $this->productsModel->getAll();
      foreach ($remessa as $remessas) {
        $remessas->quantidade_produto = 0;
        $remessas->total_produtos = 0;
        $remessas->total_geral = 0;
        $products_remessa = $this->produtoRemessaModel->getAllByRemessa((int)$remessas->id);
        foreach ($products_remessa as $product_remessa) {
          $product_remessa->cost = str_replace(".","",$product_remessa->cost);
          $float_cost = floatval(str_replace(',','.',$product_remessa->cost));
          $custo_total = $float_cost * ((int)$product_remessa->quantity);
          $product_remessa->custo_total = number_format($custo_total, 2, ',', '.');
          $remessas->total_produtos = $remessas->total_produtos + $custo_total;
          $remessas->quantidade_produto = $remessas->quantidade_produto + (float)$product_remessa->quantity;
          //var_dump($remessas->total_produtos);//die;
          $remessas->total_geral = $remessas->total_produtos;
        }
        // $remessas->product_name = $this->productsModel->get((int)$remessas->id_product)->name;
        if (($remessas->remessa_type == 4) || ($remessas->remessa_type == 5)) {
        //var_dump($remessas);die;
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
      }
      $amountRemessas = $this->remessaModel->getAmountSaida();
      $amountPages = ceil($amountRemessas->amount / $limit);
      $today = date('Y-m-d');
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
        foreach ($products as $key => $product) {
          //buscando todos os produto_remessa por id do product
          $produtos_remessa = $this->produtoRemessaModel->getAllByProduct((int) $product->id);
          
          //armazena quantidade temporaria
          $quantidade = 0;
          $contador_remessa = 0;
          //foreach para incrementar a quantidade dos produto_remessa
          foreach($produtos_remessa as $produto_remessa) {
            //variavel armazena remessa por id
            $remessa = $this->remessaModel->getRemovido((int) $produto_remessa->id_remessa);
            //die;
            //ifs para verificar tipo de entrada e faz soma/ subtracao na variavel quantidade
            if ($remessa->removido != '1'){
              //var_dump($remessa);
              if (isset($remessa->remessa_type)) {
                if (($remessa->remessa_type == '1')|| ($remessa->remessa_type == '2') || ($remessa->remessa_type == '3')|| ($remessa->remessa_type == '6') || ($remessa->remessa_type == '7')){
                    $quantidade = $quantidade + $produto_remessa->quantity;
                }
              }
              if (isset($remessa->remessa_type)) {
                  if (($remessa->remessa_type == '4') || ($remessa->remessa_type == '5') || ($remessa->remessa_type == '8')){
                      $quantidade = $quantidade - $produto_remessa->quantity;
                  }
              }
            }
          }//die;
          $product->quantity = $quantidade;
          $quantidade = 0;
          foreach($produtos_remessa as $produto_remessa) {
            $remessa = $this->remessaModel->get((int) $produto_remessa->id_remessa);
            if (isset($remessa->remessa_type)) {
              if ($remessa->remessa_type == '2'){
                $quantidade = $quantidade + $produto_remessa->quantity;
              }
            }
          }
          
          if($product->quantity <= 0) {
            unset($products[$key]);
          }
        }
        
        // $patients = $this->patientModel->getAll();

        $patients = $this->patientModel->getPatientsByName("2000-01-01", date("Y-m-d", strtotime("+ 1 day")), '', 1, 2);
        $this->remessaModel->deleteByRemessaType();
        // remessa types
        //$remessaTypes = [];
        $remessaTypes[] = $this->remessaTypeModel->get(4);
        $remessaTypes[] = $this->remessaTypeModel->get(5);
        $remessaTypes[] = $this->remessaTypeModel->get(8);
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
          'patients' => $patients,
          'remessaTypes' => $remessaTypes,
          'id_remessa' => $id_remessa,
        ]);
      }
      $remessa = $request->getParsedBody();
      $remessa['remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['id_remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['quantity'] = (int) $remessa['quantity'];
      $remessa['id'] = (int) $remessa['remessa_id'];
      $remessa['patient_id'] = (int) $remessa['patient_id'];
      $remessa['removido'] = '0';
      $products_remessa = $this->produtoRemessaModel->getAllByRemessa($remessa['id']);
      $lista_produtos = $this->produtoRemessaModel->getAllByRemessaId($remessa['id']);
      $lista_produtos =  json_encode($lista_produtos);
      if (count($products_remessa) < 1 ) {
        $this->flash->addMessage('danger', 'Não é permitido remessa sem produtos.');
        return $this->httpRedirect($request, $response, '/admin/remessa_saida/add');
      }
      //var_dump($remessa);die;
      try {
        $this->remessaModel->beginTransaction();
        $remessa = $this->entityFactory->createRemessa($remessa);
        if ($remessa->id_remessa_type == 8) {
          $remessa->patient_id = null;
        }
        $idRemessa = $this->remessaModel->updatePatient($remessa);
        if ($idRemessa->status == false) {
          throw new ModelException($idRemessa, "Erro no cadastro de Remessa. COD:0001.");
        }
        // aqui trabalhar eventlog
        if ( ($idRemessa != null) || ($idRemessa != false) ) {
          $eventLog['product_list'] = $lista_produtos;
          $eventLog['id_remessa'] = $remessa->id;
          $eventLog['suppliers'] =  $remessa->suppliers;
          $eventLog['id_patient'] = $remessa->patient_id;
          if ($remessa->remessa_type == 4){
            $remessa_type = $this->remessaTypeModel->get(4);
            $eventLog['id_remessa_type'] = (int) $eventLog['id_remessa_type'];
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('saida_doacao')->id;
            $eventLog['description'] = 'Remessa cadastrada';
            //$eventLog['id_products'] = $remessa->id_product;
            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $return_eventLog = $this->eventLogModel->add($eventLog);
            if ($return_eventLog->data == false) {
              throw new ModelException($return_eventLog, "Erro no cadastro de Remessa. COD:0002.");
            }
          } elseif ($remessa->remessa_type == 5){
            $remessa_type = $this->remessaTypeModel->get(5);
            $eventLog['id_remessa_type'] = (int) $eventLog['id_remessa_type'];
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('saida_emprestimo')->id;
            $eventLog['description'] = 'Remessa cadastrada';
            //$eventLog['id_products'] = $remessa->id_product;
            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $return_eventLog = $this->eventLogModel->add($eventLog);
            // var_dump($return_eventLog);die;
            if ($return_eventLog->data == false) {
              throw new ModelException($return_eventLog, "Erro no cadastro de Remessa. COD:0003.");
            }
          }elseif ($remessa->remessa_type == 8){
            $remessa_type = $this->remessaTypeModel->get(8);
            $eventLog['id_remessa_type'] = (int) $eventLog['id_remessa_type'];
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('saida_correcao')->id;
            $eventLog['description'] = 'Saída tipo correção.';
            $eventLog['id_products'] = null;
            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $return_eventLog = $this->eventLogModel->add($eventLog);
            if ($return_eventLog->data == false) {
              throw new ModelException($return_eventLog, "Erro no cadastro de Remessa. COD:0004.");
            }
          }

        }
        $this->remessaModel->commit();
        $this->flash->addMessage('success', 'Remessa adicionada com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/remessa_saida');
      } catch (ModelException $e) {
        $this->remessaModel->rollback();
        CustomLogger::ModelErrorLog($e->getMessage(), $e->getdata());
        $this->flash->addMessage('danger', $e->getMessage() . ' Se o problema persistir contate um administrador.');
        return $this->httpRedirect($request, $response, "/admin/remessa");
      }
    }

    public function update(Request $request, Response $response)
    {
      $products = $this->productsModel->getAll();
      $patients = $this->patientModel->getAll();
      $remessa_type[] = $this->remessaTypeModel->get(4);
      $remessa_type[] = $this->remessaTypeModel->get(5);
      $remessa_type[] = $this->remessaTypeModel->get(8);
      //$remessa_type = $this->remessaTypeModel->getAll();
      $data = $request->getParsedBody();

      $remessa = $request->getParsedBody();
      $remessa['id'] = (int) $data['id'];
      $remessa['remessa_type'] = (int) $data['remessa_type'];
      $remessa['patient_id'] = (int) $data['patient_id'];
      //var_dump($data);
      $remessa = $this->entityFactory->createRemessa($remessa);
      $remessa_return = $this->remessaSaidaModel->update($remessa);
      //var_dump($remessa);die;
      if  (($remessa_return != null) || ($remessa_return != false)) {
        $eventLog['remessa_type'] = (int) $data['remessa_type'];
        $eventLog['id_patient'] = (int) $data['patient_id'];
        $eventLog['id']         = (int) $remessa_return;
        $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_saida_edit')->id;
        if ($eventLog['remessa_type'] == 4) {
          $eventLog['description'] = 'Saída por Doação atualizada';
        } else if ($eventLog['remessa_type'] == 5) {
          $eventLog['description'] = 'Saída por Empréstimo atualizada';
        }else if ($eventLog['remessa_type'] == 8) {
          $eventLog['description'] = 'Saída por correção atualizada';
        }
        $eventLog = $this->entityFactory->createEventLog($eventLog);
        $this->eventLogModel->add($eventLog);
        $this->flash->addMessage('success', 'Saída de Estoque atualizada com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/remessa_saida');
      }
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
      $remessa_finish =  $params['remessa_finish'];
      if ($remessa_type == 0) {
        $remessa = $this->remessaModel->getAllByDate($remessa_start, $remessa_finish);
        foreach ($remessa as $remessas) {
          //$remessas->suppliers_name = $this->supplierModel->get((int)$remessas->suppliers)->name;
          //$remessas->patient_name = $this->patientModel->get((int) $remessas->patient_id)->name;
          $remessas->date = date("d/m/Y", strtotime($remessas->date));
          $remessas->remessa_type_name = $this->remessaTypeModel->get((int)$remessas->remessa_type)->name;
        }
      } else {
        $remessa = $this->remessaModel->getAllByStatus($remessa_type, $remessa_start, $remessa_finish);
        foreach ($remessa as $remessas) {
        $remessas->date = date("d/m/Y", strtotime($remessas->date));
        $remessas->remessa_type_name = $this->remessaTypeModel->get((int)$remessas->remessa_type)->name;
        }
      }
      foreach($remessa as $rem) {
      $produtos_remessa = $this->produtoRemessaModel->getAllByRemessa((int)$rem->id);
      $rem->produtos_remessa = $produtos_remessa;
    }
    $html .= "
      <style>
          table {
          border-collapse: collapse;
          border-spacing: 0;
          width: 100%;
          border: 1px solid #ddd;
          }

          th, td {
          text-align: left;
          padding: 5px;
          line-height: 100%;
          }

          tr:nth-child(even) {
          background-color: #f2f2f2;
          }
      </style>
      ";
    $html .= "
      <div style='width: 24%; float:left;'>
        <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
      </div>
      <div style='width: 75%;'>
        <p style=' '>Fundação Waldyr Becker de Apoio ao Paciente com Câncer.</p>
        <h3 style='margin-top: 2px; margin-bottom: 2px;'>Relatório de Saída Cadastrados</h3>
        <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>
      </div>
      <hr>
      <div style='width:100%; margin-top: 10px;'>
      
        <table style='width:100%; border-style:solid; border-width:1px; border-color:gray; border-collapse: collapse; '>
                  
          <tr style='border-style:solid; border-width:1px; border-color:gray;'>
            <th style='width: 10%; text-align:left;'>Data</th>
            <th style='width: 20%; text-align:left;'>Produto</th>
            <th style='width: 5%; text-align:left;'>Qtd</th>
            <th style='width: 35%; text-align:left;'>Fornecedor</th>
            <th style='width: 30%; text-align:left;'>Tipo de Entrada</th>
          </tr>
    ";
      foreach ($remessa as $remessas) {
        //var_dump($remessa);die;
        if($remessas->removido != '1'){
          foreach($remessas->produtos_remessa as $produto_remessa) {
            if (($remessas->remessa_type == 5) || ($remessas->remessa_type == 4)) {
              $remessas->patient_name = $this->patientModel->get((int) $remessas->patient_id)->name;
              $html .= "
              <tr>
                <td style=''>$remessas->date</td>
                <td style=''>$produto_remessa->name_product</td>
                <td style=''>$produto_remessa->quantity</td>
                <td style=''>$remessas->patient_name</td>
                <td style=''>$remessas->remessa_type_name</td>
              </tr>
                ";
            } else if ($remessas->remessa_type == 8) {
              //var_dump($remessas);
              //die;
              $html .= "
              <tr>
              <td style=''>$remessas->date</td>
              <td style=''>$produto_remessa->name_product</td>
              <td style=''>$produto_remessa->quantity</td>
              <td style=''>R$ ";
              $html .= ($produto_remessa->cost != '') ? "$produto_remessa->cost" : '-----';
              $html .= "</td>
              <td style=''>$remessas->remessa_type_name</td>


                </tr>
                ";
            }
          }
        }
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
    public function consulta_produto(Request $request, Response $response): Response
    {
      $idProduct = $request->getQueryParams()['id'];
      $product = $this->productsModel->get((int) $idProduct);
      if ($product) {
        //buscando todos os produto_remessa por id do product
        $produtos_remessa = $this->produtoRemessaModel->getAllByProduct((int) $product->id);
        
        //armazena quantidade temporaria
        $quantidade = 0;
        $contador_remessa = 0;
        //foreach para incrementar a quantidade dos produto_remessa
        foreach($produtos_remessa as $produto_remessa) {
          $remessa = $this->remessaModel->getRemovido((int) $produto_remessa->id_remessa);
          if ($remessa->removido != '1'){
            if (isset($remessa->remessa_type)) {
                if (($remessa->remessa_type == '1')|| ($remessa->remessa_type == '2') || ($remessa->remessa_type == '3')|| ($remessa->remessa_type == '6') || ($remessa->remessa_type == '7')){
                    $quantidade = $quantidade + $produto_remessa->quantity;
                }
            }
            if (isset($remessa->remessa_type)) {
                if (($remessa->remessa_type == '4') || ($remessa->remessa_type == '5') || ($remessa->remessa_type == '8')){
                    $quantidade = $quantidade - $produto_remessa->quantity;
                }
            }
          }
        }//die;
        $product->quantity = $quantidade;
        $quantidade = 0;
        foreach($produtos_remessa as $produto_remessa) {
          $remessa = $this->remessaModel->get((int) $produto_remessa->id_remessa);
          if (isset($remessa->remessa_type)) {
            if ($remessa->remessa_type == '2'){
              $quantidade = $quantidade + $produto_remessa->quantity;
            }
          }
        }
        
        return $response->withJson((array)$product, 200);
      }

      return $response->withJson('erro', 400);

    }
    public function view(Request $request, Response $response, array $args): Response
    {
      $patients       = $this->patientModel->getAll();
      //$remessa_type = $this->remessaTypeModel->getAll();
      $remessaTypes[] = $this->remessaTypeModel->get(4);
      $remessaTypes[] = $this->remessaTypeModel->get(5);
      $products_remessa = $this->produtoRemessaModel->getAll();
      $products = $this->productsModel->getAll();
      $id = intval($args['id']);
      $remessa = $this->remessaModel->get($id);
      $remessa_date = explode(" ", $remessa->date);
      $remessa->date = $remessa_date[0];
      //var_dump($remessa);die;
      $products_remessa = $this->produtoRemessaModel->getAllByRemessa((int)$remessa->id);
      foreach ($products_remessa as $product_remessa) {
        $name_product = $product_remessa->id_product;
        $product_remessa->name_product = $this->productsModel->get((int)$product_remessa->id_product)->name;
      }
      $patient_id = $remessa->patient_id;
      $id_suppliers = $remessa->suppliers;
      $remessa->name_patient = $this->patientModel->get((int)$remessa->patient_id)->name;
      $remessa->remessa_type_name = $this->remessaTypeModel->get((int)$remessa->remessa_type)->name;
      if (!$remessa) {
        $this->flash->addMessage('danger', '.');
        return $this->httpRedirect($request, $response, '/admin/remessa_saida');
      }
      $remessa_type = $this->remessaTypeModel->getAll();
      return $this->view->render($response, 'admin/remessa_saida/view.twig', [
        'remessa' => $remessa,
        'remessa_type' => $remessa_type,
        'remessaTypes' => $remessaTypes,
        'patient_id' => $patient_id,
        'id_suppliers' => $id_suppliers,
        'patients' => $patients,
        'products_remessa' => $products_remessa,
      ]);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
      
      //$body = $request->getParsedBody();
      $body = $args['id'];
      //var_dump($body);die;
      $remessa = $this->remessaSaidaModel->get((int)$body);
      $this->remessaSaidaModel->remove((int)$body);
      $this->flash->addMessage('success', 'Saída de estoque removida com sucesso.');
      return $this->httpRedirect($request, $response, '/admin/remessa_saida');
      //var_dump($remessa);die;
    }
}
