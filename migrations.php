<?php
    http_response_code(200);

    require_once __DIR__."/public/coreAutoLoad.php";

    $env = new DotEnv('./private/.env');
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
    Application::$database->applyMigrations();


    