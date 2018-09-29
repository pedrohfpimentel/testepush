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
    protected $productsModel;
    protected $productsTypeModel;
    protected $userModel;
    protected $eventLogModel;
    protected $eventLogTypeModel;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $remessaModel,
        Model $productsModel,
        Model $productsTypeModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->remessaModel         = $remessaModel;
        $this->productsModel        = $productsModel;
        $this->productsTypeModel    = $productsTypeModel;
        $this->userModel            = $userModel;
        $this->eventLogModel        = $eventLogModel;
        $this->eventLogTypeModel    = $eventLogTypeModel;
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {

        $products = $this->productsModel->getAll();
        return $this->view->render($response, 'admin/remessa/index.twig', ['products' => $products]);
    }

    public function sobre(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'admin/remessa/sobre.twig', ['version' => $this->version]);
    }
}
