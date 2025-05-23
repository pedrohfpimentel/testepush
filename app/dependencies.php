<?php
declare(strict_types=1);

$container = $app->getContainer();

$container['cache'] = function ($c) {
    return new Slim\HttpCache\CacheProvider();
};

// Database adapter
$container['db'] = function ($c) {
    $db = $c->get('settings')['db'];

    $dsn = 'mysql:host=' . $db['host'];
    $dsn .= ';dbname=' . $db['name'];
    $dsn .= ';port=' . $db['port'];
    $dsn .= ';charset=' . $db['charset'];

    $pdo = new PDO($dsn, $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    return $pdo;
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages();
};

// Monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    if (!empty($settings['path'])) {
        $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    } else {
        $logger->pushHandler(new Monolog\Handler\ErrorLogHandler(0, Monolog\Logger::DEBUG, true, true));
    }
    return $logger;
};

// Mailer
$container['mailer'] = function ($c) {
    $settings = $c->get('settings')['mail'];
    date_default_timezone_set('UTC');

    $mailer = new PHPMailer();
    $mailer->setLanguage('pt_br');
    $mailer->isSMTP();
    $mailer->isHTML(true);
    $mailer->SMTPAuth = true;
    $mailer->SMTPDebug = 0;
    $mailer->Debugoutput = 'html';
    $mailer->Host = $settings['host'];
    $mailer->Port = $settings['port'];
    $mailer->SMTPSecure = $settings['smtpSecureType'];
    $mailer->Username = $settings['username'];
    $mailer->Password = $settings['password'];
    $mailer->setFrom($settings['username'], 'Âncora EAD');
    $mailer->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];
    if ($c->get('settings')['displayErrorDetails']) {
        $mailer->SMTPDebug = 3;
    }

    return new Farol360\Ancora\Mailer($mailer);
};

// Parsedown: Markdown parser
$container['markdown'] = function ($c) {
    return new Farol360\Ancora\MarkdownParser();
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c->get('view')->render(
            $response->withStatus(404),
            '404.twig'
        );
    };
};

// Embera
$container['oembed'] = function ($c) {
    return new Embera\Embera([
        'allow' => ['Youtube', 'Vimeo'],
        'params' => [
            'width' => 560,
            'heigth' => 315,
        ],
    ]);
};

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings')['view'];
    $view = new Slim\Views\Twig($settings['template_path'], $settings['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new Farol360\Ancora\Twig\AuthExtension());
    $view->addExtension(new Farol360\Ancora\Twig\MarkdownExtension());

    return $view;
};

$container['version'] = $settings['version'];

Farol360\Ancora\User::setupUser($container);

// ------------------
// ------------------
// Controllers
// ------------------
// ------------------

$container['Farol360\Ancora\Controller\Admin\AttendanceController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\AttendanceController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\AttendanceModel($c['db']),
        new Farol360\Ancora\Model\AttendanceStatusModel($c['db']),
        new Farol360\Ancora\Model\PatientModel($c['db']),
        new Farol360\Ancora\Model\ProductsModel($c['db']),
        new Farol360\Ancora\Model\ProfessionalModel($c['db']),
        new Farol360\Ancora\Model\RemessaModel($c['db']),
        new Farol360\Ancora\Model\AttendanceStatusModel($c['db']),
        new Farol360\Ancora\Model\RemessaTypeModel($c['db']),
        new Farol360\Ancora\Model\UserModel($c['db']),
        new Farol360\Ancora\Model\EventLogModel($c['db']),
        new Farol360\Ancora\Model\EventLogTypeModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};

$container['Farol360\Ancora\Controller\Admin\DiseaseController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\DiseaseController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\DiseaseModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};

$container['Farol360\Ancora\Controller\Admin\IndexController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\IndexController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\AttendanceModel($c['db']),
        new Farol360\Ancora\Model\PatientModel($c['db']),
        new Farol360\Ancora\Model\ProductsModel($c['db']),
        new Farol360\Ancora\Model\ProfessionalModel($c['db']),
        $c['version']
    );
};

$container['Farol360\Ancora\Controller\Admin\PatientController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\PatientController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\AttendanceModel($c['db']),
        new Farol360\Ancora\Model\PatientModel($c['db']),
        new Farol360\Ancora\Model\PatientFileModel($c['db']),
        new Farol360\Ancora\Model\DiseaseModel($c['db']),
        new Farol360\Ancora\Model\PatientStatusModel($c['db']),
        new Farol360\Ancora\Model\ProfessionalModel($c['db']),
        new Farol360\Ancora\Model\RemessaModel($c['db']),
        new Farol360\Ancora\Model\ProdutoRemessaModel($c['db']),
        new Farol360\Ancora\Model\ProductsModel($c['db']),
        new Farol360\Ancora\Model\UserModel($c['db']),
        new Farol360\Ancora\Model\EventLogModel($c['db']),
        new Farol360\Ancora\Model\EventLogTypeModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};


$container['Farol360\Ancora\Controller\Admin\ProductsController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\ProductsController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\ProductsModel($c['db']),
        new Farol360\Ancora\Model\ProductsTypeModel($c['db']),
        new Farol360\Ancora\Model\ProdutoRemessaModel($c['db']),
        new Farol360\Ancora\Model\RemessaModel($c['db']),
        new Farol360\Ancora\Model\RemessaTypeModel($c['db']),
        new Farol360\Ancora\Model\SupplierModel($c['db']),
        new Farol360\Ancora\Model\UserModel($c['db']),
        new Farol360\Ancora\Model\EventLogModel($c['db']),
        new Farol360\Ancora\Model\EventLogTypeModel($c['db']),
        new Farol360\Ancora\Model\PatientModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};


$container['Farol360\Ancora\Controller\Admin\ProductsTypeController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\ProductsTypeController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\ProductsTypeModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};


$container['Farol360\Ancora\Controller\Admin\ProdutoRemessaController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\ProdutoRemessaController(
        $c['view'],
        $c['flash'],
       // new Farol360\Ancora\Model\ProductsTypeModel($c['db']),
        new Farol360\Ancora\Model\ProdutoRemessaModel($c['db']),
        new Farol360\Ancora\Model\RemessaModel($c['db']),
        new Farol360\Ancora\Model\ProductsModel($c['db']),
        new Farol360\Ancora\Model\EventLogModel($c['db']),
        new Farol360\Ancora\Model\EventLogTypeModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};



$container['Farol360\Ancora\Controller\Admin\ProfessionalController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\ProfessionalController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\ProfessionalModel($c['db']),
        new Farol360\Ancora\Model\ProfessionalTypeModel($c['db']),
        new Farol360\Ancora\Model\PatientModel($c['db']),
        new Farol360\Ancora\Model\AttendanceModel($c['db']),
        new Farol360\Ancora\Model\UserModel($c['db']),
        new Farol360\Ancora\Model\EventLogModel($c['db']),
        new Farol360\Ancora\Model\EventLogTypeModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};

$container['Farol360\Ancora\Controller\Admin\ProfessionalTypeController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\ProfessionalTypeController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\ProfessionalTypeModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};

$container['Farol360\Ancora\Controller\Admin\PermissionController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\PermissionController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\PermissionModel($c['db']),
        new Farol360\Ancora\Model\RoleModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};

$container['Farol360\Ancora\Controller\Admin\RemessaController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\RemessaController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\RemessaModel($c['db']),
        new Farol360\Ancora\Model\RemessaTypeModel($c['db']),
        new Farol360\Ancora\Model\ProductsModel($c['db']),
        new Farol360\Ancora\Model\ProductsTypeModel($c['db']),
        new Farol360\Ancora\Model\SupplierModel($c['db']),
        new Farol360\Ancora\Model\UserModel($c['db']),
        new Farol360\Ancora\Model\EventLogModel($c['db']),
        new Farol360\Ancora\Model\EventLogTypeModel($c['db']),
        new Farol360\Ancora\Model\ProdutoRemessaModel($c['db']),
        new Farol360\Ancora\Model\PatientModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};

$container['Farol360\Ancora\Controller\Admin\RemessaSaidaController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\RemessaSaidaController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\RemessaModel($c['db']),
       // new Farol360\Ancora\Model\RemessaTypeModel($c['db']),
        new Farol360\Ancora\Model\RemessaSaidaModel($c['db']),
        new Farol360\Ancora\Model\RemessaSaidaTypeModel($c['db']),
        new Farol360\Ancora\Model\ProductsModel($c['db']),
        new Farol360\Ancora\Model\ProductsTypeModel($c['db']),
        new Farol360\Ancora\Model\UserModel($c['db']),
        new Farol360\Ancora\Model\EventLogModel($c['db']),
        new Farol360\Ancora\Model\EventLogTypeModel($c['db']),
        new Farol360\Ancora\Model\ProdutoRemessaModel($c['db']),
        new Farol360\Ancora\Model\PatientModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};

$container['Farol360\Ancora\Controller\Admin\RoleController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\RoleController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\RoleModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};

$container['Farol360\Ancora\Controller\Admin\SupplierController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\SupplierController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\SupplierModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};

$container['Farol360\Ancora\Controller\Admin\UserController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\UserController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\UserModel($c['db'], $c['settings']['api']['baseUrl']),
        new Farol360\Ancora\Model\RoleModel($c['db'], $c['settings']['api']['baseUrl']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};

$container['Farol360\Ancora\Controller\Admin\VolunteerController'] = function ($c) {
    return new Farol360\Ancora\Controller\Admin\VolunteerController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\VolunteerModel($c['db']),
        new Farol360\Ancora\Model\UserModel($c['db']),
        new Farol360\Ancora\Model\EventLogModel($c['db']),
        new Farol360\Ancora\Model\EventLogTypeModel($c['db']),
        new Farol360\Ancora\Model\EntityFactory()
    );
};


$container['Farol360\Ancora\Controller\PageController'] = function ($c) {
    return new Farol360\Ancora\Controller\PageController(
        $c['view'],
        $c['flash'],
        $c['mailer'],
        new Farol360\Ancora\Model\EntityFactory()
    );
};

$container['Farol360\Ancora\Controller\UserController'] = function ($c) {
    return new Farol360\Ancora\Controller\UserController(
        $c['view'],
        $c['flash'],
        new Farol360\Ancora\Model\UserModel($c['db']),
        $c['mailer'],
        new Farol360\Ancora\Model\EntityFactory()
    );
};
