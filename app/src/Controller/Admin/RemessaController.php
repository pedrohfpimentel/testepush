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
        Model $remessaTypeModel,
        Model $productsModel,
        Model $productsTypeModel,
        Model $userModel,
        Model $eventLogModel,
        Model $eventLogTypeModel,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->remessaModel         = $remessaModel;
        $this->remessaTypeModel     = $remessaTypeModel;
        $this->productsModel        = $productsModel;
        $this->productsTypeModel    = $productsTypeModel;
        $this->userModel            = $userModel;
        $this->eventLogModel        = $eventLogModel;
        $this->eventLogTypeModel    = $eventLogTypeModel;
        $this->entityFactory        = $entityFactory;
    }

    public function index(Request $request, Response $response): Response
    {
      // get products to autocomplete
      $products = $this->productsModel->getAll();

      // remessa types
      $remessaTypes = $this->remessaTypeModel->getAll();

      return $this->view->render($response, 'admin/remessa/index.twig',
      [
        'products' => $products,
        'remessaTypes' => $remessaTypes
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

            // remessa types
        $remessaTypes = $this->remessaTypeModel->getAll();

            return $this->view->render($response, 'admin/remessa/index.twig',
        [
          'products' => $products,
          'remessaTypes' => $remessaTypes
        ]);
    }
        

        $remessa = $request->getParsedBody();

      $remessa['id_product'] = (int) substr($remessa['id_product'], 0, strpos($remessa['id_product'], ' '));
      $remessa['id_remessa_type'] = (int) $remessa['id_remessa_type'];
      $remessa['quantity'] = (int) $remessa['quantity'];
      $remessa['cost'] = (bool) $remessa['cost'];


      $remessa = $this->entityFactory->createRemessa($remessa);
      $idRemessa = $this->remessaModel->add($remessa);


        // aqui trabalhar eventlog
        if ( ($idProduct != null) || ($idProduct != false) ) {
            
            $this->flash->addMessage('success', 'Remessa adicionada com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/products');


        }
    }
}