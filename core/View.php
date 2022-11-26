<?php
    /**
     * Truong Pham
     */
    class View{
        public $title = '';
        public array $scripts = [];
        public array $styleSheets = [];
        public array $data = [];

        public function renderUserView($view, array $data){
            $this->data = $data;
            $viewContent = $this->viewContent($view);
            $headerContent = $this->userHeaderContent();
            $footerContent = $this->userFooterContent();
            $layoutContent = $this->userLayoutContent();
            $content =  str_replace("{{content}}", $viewContent, $layoutContent);
            $content =  str_replace(
                ["{{content}}", "{{header}}", "{{footer}}"], 
                [$viewContent, $headerContent, $footerContent], 
                $layoutContent
            );
            echo $content;
        }

        public function userLayoutContent(){
            ob_start();
            require_once Application::$rootPath.'/view/layouts/userLayout.php';
            return ob_get_clean();
        }

        public function viewContent($view){
            ob_start();
            require_once Application::$rootPath."/view/".$view.'.php';
            return ob_get_clean();
        }

        public function userHeaderContent(){
            ob_start();
            require_once Application::$rootPath."/view/partials/header.php";
            return ob_get_clean();
        }

        public function userFooterContent(){
            ob_start();
            require_once Application::$rootPath."/view/partials/footer.php";
            return ob_get_clean();
        }
    }