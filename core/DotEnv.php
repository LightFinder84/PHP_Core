<?php
    /**
     * Truong Pham
     */
    class DotEnv{

        protected $path;

        public function __construct(string $path)
        {
            if(!file_exists($path)){
                http_response_code(500);
                $_SERVER["error"] = "Env file path does not exist : ".$path." , current path: ". __DIR__;
                return;
            }
            $this->path = $path;
        }

        public function load(){
            if(http_response_code() != 200){
                return;
            }

            if(!is_readable($this->path)){
                http_response_code(500);
                $_SERVER["error"] = "Could not read env file";
                return;
            }

            $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if(strpos(trim($line), '#') === 0){
                    continue;
                }
                list($name, $value) = explode('=', trim($line), 2);
                $name = trim($name);
                $value = trim($value);
    
                if(!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)){
                    putenv($name.'='.$value);
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }