<?php
    /**
     * Truong Pham
     */
    class AuthMiddleWare extends BaseMiddleware{
        public array $actions = [];
        public function __construct(array $actions = [])
        {   
            $this->actions = $actions;
        }
        
        public function execute(string $controllerAction){
            // Is guest
            if(! Application::isLogined()){
                if (empty($this->actions) || in_array($controllerAction, $this->actions)){
                    throw new FordbiddenException();
                }
            }
        }
    }