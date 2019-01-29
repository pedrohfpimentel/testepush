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

    }


 

    public function add(Request $request, Response $response): Response
    {
       
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        
    }


    public function history (Request $request, Response $response, array $args)
    {
        
    }

    public function update(Request $request, Response $response): Response
    {

    }

}
