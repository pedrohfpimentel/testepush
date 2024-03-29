<?php
declare(strict_types=1);
namespace Farol360\Ancora\Controller\Admin;
use Farol360\Ancora\Controller;
use Farol360\Ancora\Model;
use Farol360\Ancora\Model\EntityFactory;
use Shuchkin\SimpleXLSXGen;
use Farol360\Ancora\User;
use Fusonic\SpreadsheetExport\Spreadsheet;
use Fusonic\SpreadsheetExport\ColumnTypes\DateColumn;
use Fusonic\SpreadsheetExport\ColumnTypes\NumericColumn;
use Fusonic\SpreadsheetExport\ColumnTypes\TextColumn;
use Fusonic\SpreadsheetExport\Writers\CsvWriter;
use Fusonic\SpreadsheetExport\Writers\OdsWriter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Flash\Messages as FlashMessages;
use Slim\Views\Twig as View;
class UserController extends Controller
{
    protected $entityFactory;
    protected $roleModel;
    protected $userModel;
    public function __construct(
        View $view,
        FlashMessages $flash,
        Model $user,
        Model $role,
        EntityFactory $entityFactory
    ) {
        parent::__construct($view, $flash);
        $this->userModel = $user;
        $this->roleModel = $role;
        $this->entityFactory = $entityFactory;
    }
    public function index(Request $request, Response $response): Response
    {
        if ($request->getUri()->getPath() == '/admin/user/all') {
            $pageTitle = 'Todos os usuários';
            $users = $this->userModel->getAll();
        } else {
            $pageTitle = 'Clientes';
            $users = $this->userModel->getUsers();
        }
        
        return $this->view->render($response, 'admin/user/index.twig', [
            'users' => $users,
            'page_title' => $pageTitle,
            'timestamp' => time(),
        ]);
    }
    public function add(Request $request, Response $response): Response
    {
        if (empty($request->getParsedBody())) {
            $roles = $this->roleModel->getAll();
            return $this->view->render($response, 'admin/user/add.twig', [
                'roles' => $roles,
            ]);
        }
        // var_dump($request->getParsedBody());die;
        $data = $request->getParsedBody();
        if($data['end_numero'] ==''){
            $data['end_numero'] = 0;
        }
        $user = $this->entityFactory->createUser($data);
        $this->userModel->add($user);
        $this->flash->addMessage('success', 'Usuário adicionado com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/user/all');
    }
    public function delete(Request $request, Response $response, array $args): Response
    {
        $userId = intval($args['id']);
        $currentUser = $this->userModel->get();
        if ($userId == $currentUser->id) {
            $this->flash->addMessage('danger', 'Não é possível remover seu próprio usuário.');
            return $this->httpRedirect($request, $response, '/admin/user/all');
        }
        $this->userModel->delete((int)$userId);
        $this->flash->addMessage('success', 'Usuário removido com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/user/all');
    }
    public function edit(Request $request, Response $response, array $args): Response
    {
        $userId = intval($args['id']);
        $user = $this->userModel->get((int)$userId);
        if (!$user) {
            $this->flash->addMessage('danger', 'Usuário não encontrado.');
            return $this->httpRedirect($request, $response, '/admin/user');
        }
        $roles = $this->roleModel->getAll();
        return $this->view->render($response, 'admin/user/edit.twig', [
            'user' => $user,
            'roles' => $roles
        ]);
    }
    public function export(Request $request, Response $response)
    {
        $users = $this->userModel->getAll();
        
        // Create the headers.
        $data = [
            ['Nome', 'Email', 'Data do Cadastro', 'Cargo No Sistema', 'Data de Nascimento' , 'CPF', 'DDD', 'Telefone', 'Rua', 'Número', 'Complemento', 'Bairro', 'Cidade', 'Estado', 'CEP']
        ];

        foreach ($users as $key => $user) {
            
            $array_push = [
                $user->name,
                $user->email,
                $user->created_at,
                $user->role,
                $user->nascimento,
                $user->cpf,
                $user->tel_area,
                $user->tel_numero,
                $user->end_rua,
                $user->end_numero,
                $user->end_complemento,
                $user->end_bairro,
                $user->end_cidade,
                $user->end_estado,
                $user->end_cep,
            ];
            array_push($data, $array_push);
        }
     
        $xlsx = SimpleXLSXGen::fromArray( $data );
        $xlsx->downloadAs('Usuarios-' . time() .'.xlsx');
        
    }
    public function view(Request $request, Response $response, array $args): Response
    {
        $userId = intval($args['id']);
        $user = $this->userModel->get((int)$userId);
        if (!$user) {
            $this->flash->addMessage('danger', 'Usuário não encontrado.');
            return $this->httpRedirect($request, $response, '/admin/user');
        }
        $roles = $this->roleModel->getAll();
        return $this->view->render($response, 'admin/user/view.twig', [
            'user' => $user,
            'roles' => $roles
        ]);
    }
    public function update(Request $request, Response $response): Response
    {
        $user = $this->entityFactory->createUser($request->getParsedBody());
        $this->userModel->update($user);
        $this->flash->addMessage('success', 'Usuário atualizado com sucesso.');
        return $this->httpRedirect($request, $response, '/admin/user/all');
    }
}
