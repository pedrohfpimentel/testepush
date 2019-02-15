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


class ProdutoRemessaController extends Controller
{

    protected $produtoRemessaModel;
    protected $remessaModel;
    protected $productsModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $produtoRemessaModel,
        Model $remessaModel,
        Model $productsModel,   
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->produtoRemessaModel  = $produtoRemessaModel;
        $this->remessaModel         = $remessaModel;
        $this->productsModel        = $productsModel;   
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

     
        $produto_remessa = $this->produtoRemessaModel->getAll($offset, $limit);
        foreach ($produto_remessa as $produto_de_remessa) {
            $produto_de_remessa->product_name = $this->productsModel->get((int)$produto_de_remessa->id_products)->name;
            $produto_de_remessa->professional_name = $this->professionalModel->get((int)$attendance->id_professional)->name;
        }
               
        $amountProdutoDeRemessa = $this->produtoRemessaModel->getAmount();
        $amountPages = ceil($amountProdutoDeRemessa->amount / $limit);

        return $this->view->render($response, 'admin/attendance/index.twig', [
            'produto_remessa' => $produto_remessa,
            'page' => $page,
            'amountPages' => $amountPages
            ]);
    }


 

    public function add(Request $request, Response $response): Response
    {
       
        $data = $request->getQueryParams();
       
        
        
        $data["id_product"] = (int) substr($data["nome_produto"], 0, strpos($data["nome_produto"], ' '));
        $data["id_remessa"] = (int) $data["remessa_id"];
        $data["patrimony_code"] = $data["patrimony"];
        $data["cost"] = $data["custo_produto"];
        $data["quantity"] = $data["quantidade_produto"];
       //


       // var_dump($id_produto);
        //die;

        //var_dump($data);
        //die;
        $data = $this->entityFactory->createProdutoRemessa($data);

        $data->id = $this->produtoRemessaModel->add($data);
        return $response->withJson($data, 200);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
         $id = intval($args['id']);
        $var = $this->produtoRemessaModel->delete($id);
      
        return $response->withJson($var);
    }

   


    public function history (Request $request, Response $response, array $args)
    {
        
    }

    public function update(Request $request, Response $response): Response
    {

    }

}
