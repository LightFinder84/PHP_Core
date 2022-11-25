<?php
    class ConstructingException extends Exception{
        protected $code = 300;
        protected $message = "This page is under construction";
    }