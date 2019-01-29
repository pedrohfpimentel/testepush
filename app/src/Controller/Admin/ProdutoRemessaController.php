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
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->produtoRemessaModel  = $produtoRemessaModel;
        $this->remessaModel         = $remessaModel;
        $this->productsModel        = $productsModel;
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
       if (empty($request->getParsedBody())) {

            $products       = $this->productsModel->getAll();
            $remessa  = $this->remessaModel->getAll();

            return $this->view->render($response, 'admin/produto_remessa/add.twig', [
                'products'      => $products,
                'remessa' => $remessa
            ]);
        }

        $data = $request->getParsedBody();

        $produto_remessa = $this->entityFactory->createProdutoRemessa($data);

        $id_produto_remessa = $this->produtoRemessaModel->add($produto_remessa);

        //var_dump($attendance);
        //die;

        // create eventLog when add attendance
        if ( ($id_produto_remessa != null) || ($id_produto_remessa != false) )
        {
            $eventLog['id_product']         = $produto_remessa->id_product;
            $eventLog['id_remessa']    = $produto_remessa->id_remessa;
            $eventLog['patrimony_code'] = $produto_remessa->patrimony_code;
            $eventLog['cost'] = $produto_remessa->cost;
            $eventLog['event_log_type']  = $this->eventLogTypeModel->getBySlug('produto_remessa')->id;


            $eventLog = $this->entityFactory->createEventLog($eventLog);
            $this->eventLogModel->add($eventLog);

            $this->flash->addMessage('success', 'Evento adicionado com sucesso.');
            return $this->httpRedirect($request, $response, '/admin/produto_remessa');
        }


    }

    public function delete(Request $request, Response $response, array $args): Response
    {
         $id = intval($args['id']);
        $this->produtoRemessaModel->delete($id);
        $this->flash->addMessage('success', 'Evento removido com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/produto_remessa');
    }

   


    public function history (Request $request, Response $response, array $args)
    {
        
    }

    public function update(Request $request, Response $response): Response
    {

    }

}
