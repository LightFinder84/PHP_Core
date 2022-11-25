<?php
    /**
     * Truong Pham
     */
    // namespace Core;

    class Router{
        
        public $request;
        private $response;
        private $supportedHttpMethod = array(
            Request::GET_METHOD,
            Request::POST_METHOD
        );

        // đã tìm được route giống với request hay chưa
        private $routes;

        function __construct(){
            $this->request = new Request();
            $this->response = new Response();
            $this->routes = array(
                strtolower(Request::GET_METHOD) => array(),
                strtolower(Request::POST_METHOD) => array()
            );
        }

        /**
         * Hàm này được gọi khi ta gọi các hàm chưa được định nghĩa (get, post,...)
         * Đây gọi là magic function trong php
         */
        function __call($httpMethod, $args){
            
            list($route, $object, $callback) = $args;
            // lưu route và method lại
            $this->routes[$httpMethod][$route] = array($object, $callback);
        }

        /**
         * Function này bỏ dấu / va params ở cuối của route
         */
        private function formatRoute($route){
            // remove params
            $route = preg_replace('/\?.*/', '', $route);
            //remove slash
            $route = rtrim($route, '/');
            if ($route === '') {
                return '/';
            }
            return $route;
        }

        private function invalidMethodHandler(){
            header("{$this->request->serverProtocol} 405 Method Not Allowed.");
        }
        
        private function defaultRequestHandler(){
            throw new NotFoundException();
        }

        /**
         * Hàm này gọi method controller tương ứng với route và request method
         */
        public function resolve(){
            $requestMethod = strtolower($this->request->requestMethod);
            
            // kiểm tra phương thức có được support không?
            // hiện tại chỉ có GET và POST
            if (!in_array(strtoupper($requestMethod), $this->supportedHttpMethod)) {
                // nếu phương thức chưa được hỗ trợ thì return 405 header
                $this->invalidMethodHandler();
                return;
            }
            
            $requestRoute = $this->formatRoute($this->request->requestUri);
            
            //kiểm tra xem route được request có nằm trong danh sách routes của router không
            if (isset($this->routes[$requestMethod][$requestRoute])){
                list($object, $callback) = $this->routes[$requestMethod][$requestRoute];
                $object->action = (string)$callback;
                foreach ($object->getMiddlewares() as $middleware ) {
                    $middleware->execute($object->action);
                }
                return call_user_func(array($object, $callback), $this->request, $this->response);
            }
            else {
                $this->defaultRequestHandler();
            }
        }

        public function render($view, array $data){
            $this->response->renderUserView($view, $data);
        }
        public function setStatusCode($code){
            $this->response->setStatusCode($code);
        }

        function __destruct(){
           
        }

    }
