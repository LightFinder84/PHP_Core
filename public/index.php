<?php
    http_response_code(200);

    require_once __DIR__."/coreAutoLoad.php";

    $env = new DotEnv('./../private/.env');
    $env->load();

    $config = [
        'db' => [
            'db_host'=>$_ENV["DB_HOST"],
            'db_user'=>$_ENV["DB_USER"],
            'db_password'=>$_ENV["DB_PASS"],
            'db_name'=>$_ENV["DB_NAME"]
        ]
        ];
    
    $app = new Application($config);
    $database = new Database($config['db']);

    require_once __DIR__."/../router/homeRouter.php";
    require_once __DIR__."/../router/authRouter.php";    
    
    $app->run();


    