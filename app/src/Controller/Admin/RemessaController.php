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
    protected $produtoRemessaModel;
    protected $patientModel;

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
        Model $produtoRemessaModel,
        Model $patientModel,
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
        $this->produtoRemessaModel  = $produtoRemessaModel;
        $this->patientModel         = $patientModel;
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {
      $this->remessaModel->deleteByRemessaType();

      // get products to autocomplete
      $products = $this->productsModel->getAll();
      $suppliers = $this->supplierModel->getAll();

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
      $remessa = $this->remessaModel->getAllByType([1,2,3,6,7], $offset, $limit);

     // $remessa_type = $this->remessaTypeModel->getAll();
      $remessa_type[] = $this->remessaTypeModel->get(1);
      $remessa_type[] = $this->remessaTypeModel->get(2);
      $remessa_type[] = $this->remessaTypeModel->get(3);
      $remessa_type[] = $this->remessaTypeModel->get(6);
      //var_dump($remessa_type);
      //die;
      
      foreach ($remessa as $remessas) {
        //var_dump($remessas);die;
        //die;
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
        $remessas->total_geral = 'R$ ' . number_format($remessas->total_geral, 2, ',', '.');
        //var_dump($remessas->total_geral);
        //die;
        if (($remessas->suppliers != NULL ) &&  ($remessas->suppliers != 0 ))
        {
          $remessas->suppliers_name = $this->supplierModel->get((int)$remessas->suppliers)->name;
        } else {
          $remessas->suppliers_name = "- - - - -";
        }
        $remType = (int)$remessas->remessa_type;
        if ($remType == 6) {
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
        // $date = substr($remessas->date, 0, 10);
        // $date = strtotime($date);
        // $remessas->date = date('d/m/Y', $date);
      }
      $amountRemessas = $this->remessaModel->getAmount();
      $amountPages = ceil($amountRemessas->amount / $limit);
      $today = date('Y-m-d');
      //var_dump($remessa);
      //die;

      return $this->view->render($response, 'admin/remessa/index.twig',[
        //'date' => $date,
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
      if (empty($request->getParsedBody())) {
        // get products to autocomplete
        $products = $this->productsModel->getAll();
        $suppliers = $this->supplierModel->getAll();
        $this->remessaModel->deleteByRemessaType();
        $remessaTypes[] = $this->remessaTypeModel->get(1);
        $remessaTypes[] = $this->remessaTypeModel->get(2);
        $remessaTypes[] = $this->remessaTypeModel->get(6);
        $remessaTypes[] = $this->remessaTypeModel->get(7);
        $temp['id_products'] = 0;
        $temp['quantity'] = 0;
        $temp['cost'] = 0;
        $temp['remessa_type'] = 99;
        $temp = $this->entityFactory->createRemessa($temp);
        $id_remessa = $this->remessaModel->add($temp);
        $patients = $this->patientModel->getAll();
        return $this->view->render($response, 'admin/remessa/add.twig',
        [
          'products' => $products,
          'suppliers' => $suppliers,
          'remessaTypes' => $remessaTypes,
          'id_remessa' => $id_remessa,
          'patients' => $patients
        ]);
      }
      $remessa = $request->getParsedBody();
      $remessa['suppliers'] =  (int) $remessa['suppliers'];
      $remessa['remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['id_remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['quantity'] = (int) $remessa['quantity'];
      $remessa['cost'] = (float) $remessa['cost'];
      $remessa['id'] = (int) $remessa['remessa_id'];
      $remessa['patient_id'] = (int) $remessa['patient_id'];
      $remessa['removido'] = '0';
      $products_remessa = $this->produtoRemessaModel->getAllByRemessa($remessa['id']);
      $lista_produtos = $this->produtoRemessaModel->getAllByRemessaId($remessa['id']);
      $lista_produtos =  json_encode($lista_produtos);
      if (count($products_remessa) < 1 ) {
        $this->flash->addMessage('danger', 'Não é permitido remessa sem produtos.');
        return $this->httpRedirect($request, $response, '/admin/remessa/add');
      }
      //var_dump($remessa);die;
      $remessa = $this->entityFactory->createRemessa($remessa);
      if ( ($remessa->remessa_type == 1) || ($remessa->remessa_type == 2)) {
        $eventLog['id_remessa'] = $remessa->id;
        $eventLog['suppliers'] =  $remessa->suppliers;
        $idRemessa = $this->remessaModel->update($remessa);
      } else if ($remessa->remessa_type == 6) {
        $remessa->suppliers =  NULL;
        $eventLog['id_remessa'] = $remessa->id;
        $eventLog['suppliers'] =  $remessa->suppliers;
        $eventLog['id_patient'] = $remessa->patient_id;
        $idRemessa = $this->remessaModel->updatePatient($remessa);
      } else if ($remessa->remessa_type == 7) {
        $remessa->suppliers =  NULL;
        $remessa->patient_id =  NULL;
        $eventLog['id_remessa'] = $remessa->id;
        $eventLog['suppliers'] =  null;
        $eventLog['id_patient'] = null;
        $idRemessa = $this->remessaModel->update($remessa);
      }
      // aqui trabalhar eventlog
      if ( ($idRemessa != null) || ($idRemessa != false) ) {
        $eventLog['product_list'] = $lista_produtos;
        //var_dump($eventLog);
        if ($remessa->remessa_type == 1){
          $remessa_type = $this->remessaTypeModel->get(1);
          $eventLog['id_remessa_type'] = (int) $eventLog['id_remessa_type'];
          $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_doacao')->id;
          $eventLog['description'] = 'Remessa cadastrada';
          //$eventLog['id_products'] = $remessa->id_product;
          //var_dump($remessa_type);
          $eventLog = $this->entityFactory->createEventLog($eventLog);
          //var_dump($eventLog);
          //die;
          $this->eventLogModel->add($eventLog);
          //die;
        } elseif  ($remessa->remessa_type == 2){
          $remessa_type = $this->remessaTypeModel->get(2);
          $eventLog['id_remessa_type'] = (int) $eventLog['id_remessa_type'];
          $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_entrada_compra')->id;
          $eventLog['description'] = 'Remessa cadastrada';
          //$eventLog['id_products'] = $remessa->id_product;
          $eventLog['supplier'] = $remessa->suppliers;
          $eventLog = $this->entityFactory->createEventLog($eventLog);
          $this->eventLogModel->add($eventLog);
        } elseif  ($remessa->remessa_type == 6){
          $remessa_type = $this->remessaTypeModel->get(6);
          $eventLog['id_remessa_type'] = (int) $eventLog['id_remessa_type'];
          $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('devolution')->id;
          $eventLog['description'] = 'Devolução cadastrada';
          $eventLog = $this->entityFactory->createEventLog($eventLog);
          $this->eventLogModel->add($eventLog);
        } elseif  ($remessa->remessa_type == 7){
          $remessa_type = $this->remessaTypeModel->get(7);
          $eventLog['id_remessa_type'] = (int) $eventLog['id_remessa_type'];
          $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('entrada_correcao')->id;
          $eventLog['description'] = 'Entrada tipo Correção.';
          $eventLog = $this->entityFactory->createEventLog($eventLog);
          $this->eventLogModel->add($eventLog);
        }
      }

      $this->flash->addMessage('success', 'Remessa adicionada com sucesso.');
      return $this->httpRedirect($request, $response, '/admin/remessa');
    }

    public function update(Request $request, Response $response): Response
    {
     $products = $this->productsModel->getAll();
      $patients = $this->patientModel->getAll();
      $remessa_type[] = $this->remessaTypeModel->get(1);
      $remessa_type[] = $this->remessaTypeModel->get(2);
      $remessa_type[] = $this->remessaTypeModel->get(6);
      $remessa_type[] = $this->remessaTypeModel->get(7);
      //$remessa_type = $this->remessaTypeModel->getAll();
      $data = $request->getParsedBody();
      $remessa = $request->getParsedBody();
        $remessa['id'] = (int) $data['id'];
        $remessa['remessa_type'] = (int) $data['remessa_type'];
        $remessa['date'] = $data['date'];
        if (($remessa['remessa_type'] == 1) || ($remessa['remessa_type'] == 2)) {
          $remessa['patient_id'] = NULL;
          $remessa['suppliers'] = (int) $data['suppliers'];

        } else if ($remessa['remessa_type'] == 6) {
          $remessa['suppliers'] = NULL;
          $remessa['patient_id'] = (int) $data['patient_id'];
        } else if ($remessa['remessa_type'] == 7) {
          $remessa['suppliers'] = NULL;
          $remessa['patient_id'] = NULL;
        }
       //var_dump($remessa);die;
      $remessa = $this->entityFactory->createRemessa($remessa);
      $remessa_return = $this->remessaModel->update($remessa);

      // var_dump($remessa);die;

      if  (($remessa_return != null) || ($remessa_return != false)) {

        $eventLog['remessa_type'] = (int) $data['remessa_type'];
        $eventLog['id_patient'] = (int) $data['patient_id'];
        $eventLog['suppliers'] = (int) $data['suppliers'];
        $eventlog['date'] = $data['date'];
        $eventLog['id']         = (int) $attendance->id;
        $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('remessa_edit')->id;

        if ($eventLog['remessa_type'] == 1) {
          $eventLog['description'] = 'Recebimento de Doação atualizada';
        } else if ($eventLog['remessa_type'] == 2) {
          $eventLog['description'] = 'Compra atualizada';
        } else if ($eventLog['remessa_type'] ==  6) {
          $eventLog['description'] = 'Devolução atualizada';
        } else if ($eventLog['remessa_type'] ==  7) {
          $eventLog['description'] = 'Correção atualizada';
        }

        $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);

            $this->flash->addMessage('success', 'Entrada de Estoque atualizada com sucesso.');
            return $this->httpRedirect($request, $response, '/admin/remessa');
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
        //var_dump($rem);
      }
      $html = "
        <div style='width: 24%; float:left;'>
            <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
        </div>
        <div style='width: 75%;'>
            <p style=' '>Fundação Waldyr Becker de Apoio ao Paciente com Câncer.</p>
            <h3 style='margin-top: 2px; margin-bottom: 2px;'>Relatório de Entradas</h3>
            <p> <strong>Data relatório:</strong>  " . date("d/m/Y") . " </p>
        </div>
        <hr>
        <div style='width:100%; margin-top: 10px;'>
        <table>
            <tr>
              <th style='width: 10%; text-align:left;'>Data</th>
              <th style='width: 20%; text-align:left;'>Produto</th>
              <th style='width: 5%; text-align:left;'>qtd</th>
              <th style='width: 10%; text-align:left;'>Preço</th>
              <th style='width: 30%; text-align:left;'>Fornecedor</th>
              <th style='width: 30%; text-align:left;'>Tipo de Entrada</th>

            </tr>
      ";

      foreach ($remessa as $remessas) {
        
        foreach($remessas->produtos_remessa as $produto_remessa) {
          //var_dump($remessas);//die;
          if($remessas->removido != '1'){
            if ($remessas->remessa_type == 6) {
              $remessas->patient_name = $this->patientModel->get((int) $remessas->patient_id)->name;
              //var_dump($remessas);

              $html .= "
              <tr>
              <td style=''>$remessas->date</td>
              <td style=''>$produto_remessa->name_product</td>
              <td style=''>$produto_remessa->quantity</td>
              <td style=''>R$ ";
              $html .= ($produto_remessa->cost != '') ? "$produto_remessa->cost" : '-----';
              $html .= "</td>
              <td style=''>$remessas->patient_name</td>
              <td style=''>$remessas->remessa_type_name</td>
              </tr>
              ";
            }else if (
              ($remessas->remessa_type == 1) ||
              ($remessas->remessa_type == 2) ||
              ($remessas->remessa_type == 3)) {
                if (($remessas->suppliers != NULL ) &&  ($remessas->suppliers != 0 ))
                {
                  $remessas->suppliers_name = $this->supplierModel->get((int)$remessas->suppliers)->name;
                } else {
                  $remessas->suppliers_name = "- - - - -";
                }
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
              <td style=''>$remessas->suppliers_name</td>
              <td style=''>$remessas->remessa_type_name</td>


                </tr>
                ";
            }
          }//die;
        }
      }//die;


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
    public function view(Request $request, Response $response, array $args): Response
    {
      $patients       = $this->patientModel->getAll();
      $suppliers  = $this->supplierModel->getAll();
      $products_remessa = $this->produtoRemessaModel->getAll();
      $products = $this->productsModel->getAll();
      $remessaTypes[] = $this->remessaTypeModel->get(1);
      $remessaTypes[] = $this->remessaTypeModel->get(2);
      $remessaTypes[] = $this->remessaTypeModel->get(6);
      $id = intval($args['id']);
      $remessa = $this->remessaModel->get($id);
      $remessa_date = explode(" ", $remessa->date);
      $remessa->date = $remessa_date[0];
      $products_remessa = $this->produtoRemessaModel->getAllByRemessa((int)$remessa->id);
      $total_produtos = 0;
      foreach ($products_remessa as $product_remessa) {
        $name_product = $product_remessa->id_product;
        $product_remessa->name_product = $this->productsModel->get((int)$product_remessa->id_product)->name;
        $product_remessa->cost = str_replace(".","",$product_remessa->cost);
        $float_cost = floatval(str_replace(',','.',$product_remessa->cost));
        $custo_total = $float_cost * ((int)$product_remessa->quantity);
        $product_remessa->custo_total = number_format($custo_total, 2, ',', '.');
        $total_produtos = $total_produtos + $custo_total;
      }
      $total_produtos = 'R$ ' . number_format($total_produtos, 2, ',', '.');
      $patient_id = $remessa->patient_id;
      $id_suppliers = $remessa->suppliers;
      if($remessa->remessa_type == 6){
        $remessa->name_patient = $this->patientModel->get((int)$remessa->patient_id)->name;
      } else {
        if(($remessa->suppliers != NULL) && ($remessa->suppliers != '0')){
          
          $remessa->name_suppliers = $this->supplierModel->get((int)$remessa->suppliers)->name;
        } else {
          
        $remessa->name_suppliers = '- - - - - ';
        }
        //var_dump($remessa);die;
      }
      $remessa->remessa_type_name = $this->remessaTypeModel->get((int)$remessa->remessa_type)->name;
      if (!$remessa) {
        $this->flash->addMessage('danger', '.');
        return $this->httpRedirect($request, $response, '/admin/remessa');
      }
      // var_dump($products_remessa);
      // die;
      //var_dump($remessa);die;
      return $this->view->render($response, 'admin/remessa/view.twig', [
        'remessa' => $remessa,
        'remessaTypes' => $remessaTypes,
        'patient_id' => $patient_id,
        'id_suppliers' => $id_suppliers,
        'patients' => $patients,
        'suppliers' => $suppliers,
        'products_remessa' => $products_remessa,
        'total_produtos' => $total_produtos
      ]);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
      //$body = $request->getParsedBody();
      $body = $args['id'];
      //var_dump($body);die;
      $remessa = $this->remessaModel->get((int)$body);
      $this->remessaModel->remove((int)$body);
      $this->flash->addMessage('success', 'Saída de estoque removida com sucesso.');
      return $this->httpRedirect($request, $response, '/admin/remessa');
      //var_dump($remessa);die;
    }
}
