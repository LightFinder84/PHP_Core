<?php
    /**
     * Truong Pham
     */
    require_once __DIR__."/controller.php";
    class AuthController extends Controller{

        public function showRegisterPage (Request $req, Response $res){
            $res->renderUserView('registerView', []);
        }

        public function register(Request $req, Response $res){
            $registerForm = new RegisterForm();
            $registerForm->loadData($req->getBody());

            if(!$registerForm->validate()){ //if validate failed
                $res->renderUserView('registerView', [
                    'registerForm' => $registerForm
                ]);
                return;
            } else {
                $userModel = new UserModel($req->getBody());
                $userModel->save();;
                Application::$session->setFlashMessage('message', 'Register successfully');
                $res->redirect("http://localhost/auth/login");
            }
        }

        public function showLoginPage(Request $req, Response $res){
            $res->renderUserView('loginView');
        }

        public function login(Request $req, Response $res){

            $loginForm = new LoginForm();
            $loginForm->loadData($req->getBody());
            if(!$loginForm->validate()){
                $res->setStatusCode(400);
                $res->renderUserView('loginView',[
                    'loginForm' => $loginForm
                ]);
                return;
            }
            if(!$loginForm->login()){
                Application::$session->setFlashMessage('loginAlert', 'Your password is incorrect.');
                $res->redirect('http://localhost/auth/login');
            } else {
                // save login status of client using session
                Application::login($loginForm->id);
                Application::$session->setFlashMessage('loginSuccess', 'Login successfully.');
                $res->redirect('http://localhost');
            }
        }

        public function logout(Request $req, Response $res){
            Application::logout();
            $res->redirect("http://localhost/auth/login");
        }
    }