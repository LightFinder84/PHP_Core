<?php
    /**
     * Truong Pham
     */
    $authController = new AuthController();

    $app->router->get('/auth/login', $authController, 'showLoginPage');
    $app->router->get('/auth/register', $authController, 'showRegisterPage');
    $app->router->post('/auth/register', $authController, 'register');
    $app->router->post('/auth/login', $authController, 'login');
    $app->router->get('/auth/logout', $authController, 'logout');