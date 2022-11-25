<?php
    /**
     * Truong Pham
     */
    // namespace Core;

    class Request{

        const GET_METHOD = "GET";
        const POST_METHOD = "POST";
        // const PUT_METHOD = "PUT";
        // const HEAD_METHOD = "HEAD";
        // const PATCH_METHOD = "PATCH";
        // const DELETE_METHOD = "DELETE";
        const urlParams = array();

        function __construct(){
            $this->bootstrapSelf();
        }


        /**
         * bootstrapSelf là hàm lấy tất cả các param của $_SERVER đổ vào cho đối tượng gốc.
         * Sau này việc dùng router sẽ không cần dùng tới biến global của PHP
         * thay vào đó ta sẽ truyền đối tượng request vào
         * các key của biến $_SERVER sẽ được format theo dạng camelCase
         * @return void
         */
        private function bootstrapSelf(){
            foreach ($_SERVER as $key => $value) {
                $this->{$this->toCamelCase($key)} = $value;
            }
        }

        /**
         * Hàm này dùng để format chuổi string dạng HELLO_WORLD thành dạng camelcase
         * @param String $string
         * @return String
         */
        private function toCamelCase($string){
            $result = strtolower($string);

            preg_match_all('/_[a-z]/', $result, $matches);

            foreach ($matches[0] as $match) {

                $c      = str_replace('_', '', strtoupper($match));
                $result = str_replace($match, $c, $result);
            }

            return $result;
        }

        /**
         * Hàm này dùng để lấy dữ liệu người dùng gửi đi kèm theo các phương thức như POST, PUT, DELETE...
         * Hàm này không trả về gì nếu phương thức là get
         */
        public function getBody(){
            
            if($this->requestMethod === Request::GET_METHOD){
                $body = array();
                foreach ($_GET as $key => $value) {
                    // $body[$key] = trim($value);
                    $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                return $body;
            }

            if($this->requestMethod === Request::POST_METHOD){
                $body = array();
                foreach ($_POST as $key => $value) {
                    // $body[$key] = trim($value);
                    $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                return $body;
            }
        }

        /**
         * Hàm này dùng đểu lấy dữ liệu người dùng gửi đi kèm theo URL
         * @return Array
         */
        // public function getUrlParams(){
        //     $result = array();
        //     // lấy param trong url nếu có
        //     if($this->hasParams()){
        //         // tách route và param trong url
        //         list($route, $params) = explode("?", $this->request->requestUri);
        //         // tách từng param string
        //         $params = explode("&", $params);
        //         foreach ($params as $string) {
        //             list($key, $value) = explode("=", $string);
        //             $result[$key] = $value;
        //         }
        //     }
        //     return $result;
        // }

        /**
         * Hàm này kiểm tra xem trên url có param hay khong
         * @return Boolean
         */
        public function hasParams(){
            return preg_match('/\?[a-z]+=[a-z0-9]/i', $this->requestUri);
        }
    }