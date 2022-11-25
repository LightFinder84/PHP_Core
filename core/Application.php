<?php
    /**
     * Truong Pham
     */
    class Application{
        
        public Router $router;
        public static $rootPath = __DIR__."\..";
        public static Database $database;
        public static Session $session;
        public static $user = null;

        function __construct($config){
            $this->router = new Router();
            self::$database = new Database($config["db"]);
            self::$session = new Session();

            if($id = self::$session->get('user')){
                self::$user = UserModel::findOne(['id'=>$id]);
            }
        }
        
        public static function isLogined(){
            if(self::$session->get('user') && !is_null(self::$user)){
                return true;
            }
            return false;
        }

        public function run(){
            try {
                $this->router->resolve();
            } catch (Exception $exception) {
                $this->router->setStatusCode($exception->getCode());
                $this->router->render('errors/error',[
                    'exception'=>$exception
                ]);
            }
        }

        /**
         * @param user: associative array
         */
        public static function login($id){
            self::$session->set('user', $id);
        }

        public static function logout(){
            self::$session->remove('user');
        }
    }