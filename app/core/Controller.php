<?php

class Controller {

    public  function model($model)
    {
        /**
         * Подключение необходимой модели 
         */ 
        require_once '../app/models/'. $model .'.php';
        return new $model();
    }

    public function view($view, $data = [])
    {
        /**
         * Подключение html файла
         */
        require_once '../app/views/header.php';		
        require_once '../app/views/'.$view.'.php';
        require_once '../app/views/footer.php';
    }
}