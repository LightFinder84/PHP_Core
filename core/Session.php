<?php
    /**
     * Truong Pham
     */
    class Session{
        protected const FLASH_KEY = 'flash_messages';

        public function __construct()
        {
            session_start();
            $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
            foreach ($flashMessages as $key => &$messageInfo) {
                // mark to be remove
                $messageInfo['remove'] = true;
            }
            $_SESSION[self::FLASH_KEY] = $flashMessages;
        }

        public function setFlashMessage($key, $message){
            $_SESSION[self::FLASH_KEY][$key] = [
                'message'=>$message,
                'remove'=>false
            ];
        }
        
        public function getFlashMessage($key){
            if(isset($_SESSION[self::FLASH_KEY][$key])){
                return $_SESSION[self::FLASH_KEY][$key]['message'];
            }
            return false;
        }

        public function set($key, $value){
            $_SESSION[$key] = $value;
        }

        public function get($key){
            return $_SESSION[$key] ?? false;
        }

        public function remove($key){
            unset($_SESSION[$key]);
        }

        public function __destruct()
        {
            // iterate over marked message to be removed
            $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
            foreach ($flashMessages as $key => &$messageInfo) {
                // mark to be remove
                if($messageInfo['remove']){
                    unset($flashMessages[$key]);
                }
            }
            $_SESSION[self::FLASH_KEY] = $flashMessages;
        }
    }