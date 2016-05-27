<?php

class App {
	
	/**
	 * Определяет используемый контроллер. По умолчанию используется контроллер home 
	 * @var string
	 */
	protected $controller = 'home';
	
	/** 
	 * Определяет используемый метод контроллера. По умолчанию используется метод index
	 * @var string
	 */
	protected $method = 'index';
	
	/**
	 * Массив передаваемых параметров
	 * @var array 
	 */
	protected $params = [];	
		
	public function __construct() {
		$url = $this->parseUrl();
		//print_r($url);
		
		/**
		 * Проверка на существование файла в папке controllers.
		 * Если файл существует, то переменной $controller присваивается 
		 * первый элемент массива $url. Затем первый элемент массива $url удаляется,
		 * чтобы массив $url можно было использовать при определении передаваемых параметров
		 */
		if (file_exists('../app/controllers/'.$url[0].'.php')) {
			$this->controller = $url[0];
			unset($url[0]);
		}
		
		/**
		 * Подключение нужного контроллера
		 */
		require_once '../app/controllers/'.$this->controller.'.php';
		
		/**
		 * Создание экземпляра класса вызываемого контроллера 
		 * @var object 
		 */
		$this->controller = new $this->controller;
		
		/**
		 * Проверка на существование метода в классе вызываемого контроллера.
		 * Если такой метод существует, то переменной $method присваивается
		 * второй элемент массива $url. Затем второй элемент массива $url удаляется,
		 * чтобы массив $url можно было использовать при определении передаваемых параметров
		 */
		if (isset($url[1])) {
			if (method_exists($this->controller, $url[1])) {
				$this->method = $url[1];
				unset($url[1]);
			}
		}
		
		
		/**
		 * Определяет параметры, передаваемые в url
		 * @var array 
		 */
		$this->params = $url ? array_values($url) : [];
		
		/**
		 * Вызывается метод класса контроллера с передаваемыми параметрами
		 */
		call_user_func_array([$this->controller, $this->method], $this->params);
	}
	
	/**
	 * Функция проверяет существование url. Если существует - фильтрует его, удаляет, если есть, в конце 
	 * слэш, и разбивает его на элементы массива по слэшу
	 * @return array
	 */
	public function parseUrl() {
		if (isset($_GET['url'])) {
			return $url = explode('/',filter_var(rtrim($_GET['url'] ,'/'), FILTER_SANITIZE_URL));
		}
	}
}