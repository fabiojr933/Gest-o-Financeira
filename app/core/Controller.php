<?php
namespace app\core;

class Controller{
     public function load($viewName, $viewData=array()){
       extract($viewData); 
       include "app/views/" . $viewName .".php";
   }  
   
   public function redirect($view) {
       header('Location:' . $view);
       exit;
   }
   
   public function incluir($view){
       include "app/views/".$view .".php";
   }
   
  
}
