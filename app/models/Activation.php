<?php
/**
 * Активации зарегистрированного пользователя
 * @author Vika
 *
 */
class Activation {
	
	protected $id;
	protected $hash;
	public $info;
	
	public function __construct() {
		
		if(isset($_GET['hash'], $_GET['id'])) {
			
			/**
			 * Открытие соединения с БД
			 * @var object
			 */
			$db = new DataBase;
			
			/**
			 * Данные, содержащиеся в URL строке, по которой пользователь должен пройти 
			 * для успешной активации
			 * @var mixed
			 */
			$this->id = intval($_GET['id']);
			$this->hash = $_GET['hash'];
			
			/**
			 * Подготовленный запрос
			 */
			if ($result = $db->mysqli->prepare("UPDATE `users` SET
										`active`=1 WHERE
										`id`=? AND
										`hash`=?
										")) {
		
				$result->bind_param('is', $this->id ,$this->hash);
				$result->execute();
				
				/**
				 * Закрытие подготовленного запроса
				 */
				$result->close();
				
				/**
				 * Закрытие соединения с БД
				 */
				$db->mysqli->close();
			}
		
			$this->info='Вы успешно прошли активацию на нашем сайте';
		}else {
			$this->info='Вы прошли по неверной ссылке';
		}
	}		
}
