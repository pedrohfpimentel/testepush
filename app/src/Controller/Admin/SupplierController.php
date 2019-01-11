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



class SupplierController extends Controller
{

    protected $supplierModel;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $supplierModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->supplierModel = $supplierModel;
        $this->entityFactory = $entityFactory;
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


        $suppliers = $this->supplierModel->getAll($offset, $limit);


        $amountSuppliers = $this->supplierModel->getAmount();
        $amountPages = ceil($amountSuppliers->amount / $limit);

        return $this->view->render($response, 'admin/suppliers/index.twig', [
            'suppliers' => $suppliers,
            'page' => $page,
            'amountPages' => $amountPages
        ]);
    }

    public function add(Request $request, Response $response): Response
    {
        if (empty($request->getParsedBody())) {
            return $this->view->render($response, 'admin/suppliers/add.twig');
        }

        $suppliers = $request->getParsedBody();

        $suppliers = $this->entityFactory->createSupplier($request->getParsedBody());

        $this->supplierModel->add($suppliers);



        $this->flash->addMessage('success', 'Fornecedor adicionada com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/suppliers'); 
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $this->supplierModel->delete($id);

        $this->flash->addMessage('success', 'Fornecedor removido com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/suppliers'); 
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $suppliers = $this->supplierModel->get($id);
        if (!$suppliers) {
            $this->flash->addMessage('danger', 'Fornecedor não encontrado.');
            return $this->httpRedirect($request, $response, '/admin/suppliers');
        }
        return $this->view->render($response, 'admin/suppliers/edit.twig', ['suppliers' => $suppliers]); 


    }



     public function export(Request $request, Response $response)
     {
            $suppliers = $this->supplierModel->getAllDownload();

            var_dump($suppliers);
            die;

            $dir = getcwd();
            
      $html = "
      <div style='width: 24%; float:left;'>
        <img src='logo.png' style='width: 120px; float:left; padding-right: 15px;'>
      </div>
      <div style='width: 75%;'>
        <p style=' '>Fundação Waldyr Becker de Apoio ao Paciente com Câncer.</p>
        <h3 style='margin-top: 2px; margin-bottom: 2px;'>Relatório de Fabricantes Cadastrados</h3>
        <p> <strong>Data relatório:</strong>  " . date("d-m-Y") . " </p>
      
      </div>
      <hr>
      <div style='width:100%; margin-top: 10px;'>
      <table>
            
            <tr>
                <th style='width: 25%; text-align:left;'>Nome</th>
                <th style='width: 25%; text-align:left;'>Email</th>
               
               
               
            </tr>
        ";

         foreach ($suppliers as $supplier) {
            //var_dump( $supplier);
            //$supplier = $this->entityFactory->createSupplier($supplier);
            $html .= "
            <tr>
            <td style='width: 25%; text-align:left;'>$supplier->name</td>
            <td style='width: 25%; text-align:left;'>$supplier->email</td>
            
           
           
            </tr>";
        }
        //die;
    
        $html .= "</table> </div>";
    try {
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
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






    public function update(Request $request, Response $response): Response
    {
       $suppliers = $this->entityFactory->createSupplier($request->getParsedBody());
        $this->supplierModel->update($suppliers);

        $this->flash->addMessage('success', 'Fornecedor atualizado com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/suppliers'); 
    }
}
