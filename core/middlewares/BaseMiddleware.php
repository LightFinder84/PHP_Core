<?php
    /**
     * Truong Pham
     */
    abstract class BaseMiddleware{
        abstract public function execute(string $controllerAction);
    }