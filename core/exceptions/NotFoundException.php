<?php
    /**
     * Truong Pham
     */
    class NotFoundException extends Exception{
        protected $message = "Page not found.";
        protected $code = 404;
    }