<?php
declare(strict_types=1);

namespace Farol360\Ancora\Controller\Admin;

use Farol360\Ancora\Controller;
use Farol360\Ancora\Model;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Flash\Messages as FlashMessages;
use Slim\Views\Twig as View;

class IndexController extends Controller
{

    protected $attendanceModel;
    protected $patientModel;
    protected $productModel;
    protected $professionalModel;
    protected $version;

    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $attendanceModel,
        Model $patientModel,
        Model $productModel,
        Model $professionalModel,
        $version
    ) {
        parent::__construct($view, $flash);
        $this->attendanceModel      = $attendanceModel;
        $this->patientModel         = $patientModel;
        $this->productModel         = $productModel;
        $this->professionalModel    = $professionalModel;
        $this->version = $version;
    }

    public function index(Request $request, Response $response): Response
    {
        $amountPatients = $this->patientModel->getAmount();
        $amountPatientsAtivos = $this->patientModel->getAmountName('2000-01-01',date("Y-m-d", strtotime("+ 1 day")),'', 1);
        $amountProfessionals = $this->professionalModel->getAmount();
        $amountProducts = $this->productModel->getAmount();
        $amountAttendances = $this->attendanceModel->getAmount();
        //var_dump($amountPatients->amount);die;
        return $this->view->render($response, 'admin/dashboard/index.twig',[
            'amountPatients' => $amountPatients->amount,
            'amountPatientsAtivos' => $amountPatientsAtivos->amount,
            'amountProfessionals' => $amountProfessionals->amount,
            'amountProducts' => $amountProducts->amount,
            'amountAttendances' => $amountAttendances->amount 
        ]);
    }

    public function sobre(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'admin/dashboard/sobre.twig', ['version' => $this->version]);
    }
}
