<?php
    /**
     * Truong Pham
     */
    class Controller{   
        /**
         * @var /core/middlewares/BaseMiddleware[]
         */
        protected array $middlewares = [];
        public string $action = '';

        public function RegisterMiddleware(BaseMiddleware $middleware){
            $this->middlewares[] = $middleware;
        }

        public function getMiddlewares(){
            return $this->middlewares;
        }
    }