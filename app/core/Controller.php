<?php

class Controller {
	
	public  function model($model) {
		
		/**
		 * Подключение необходимой модели
		 * Создание экземпляра класса подключенной модели
		 * @return object
		 */ 
		require_once '../app/models/'. $model .'.php';
		return new $model();
	}
	
	public function view($view, $data = []) {
		
		/**
		 * Подключение html файла
		 */
		require_once '../app/views/header.phtml';		
		require_once '../app/views/'.$view.'.phtml';
		require_once '../app/views/footer.phtml';
	}
}