<?php
    /**
     * Truong Pham
     */
    require_once __DIR__."/controller.php";

    class HomeController extends Controller{
        
        public function showHomePage (Request $req, Response $res){
            $res->renderUserView('homeView');
        }
    
    }