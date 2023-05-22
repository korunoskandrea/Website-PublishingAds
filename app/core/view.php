<?php
class View {
    protected $viewName;
    protected $viewData;

    public function __construct($viewName, $viewData) {
        $this->viewName = $viewName;
        $this->viewData = $viewData;
    } 

    public function __get($name) {
        if(array_key_exists($name, $this->viewData)) 
            return $this->viewData[$name];
    }

    public function render() {
        if(file_exists(VIEW."$this->viewName.view.phtml")) {
            require_once VIEW . "$this->viewName.view.phtml";
        }
    }
}