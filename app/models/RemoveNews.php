<?php
/**
 * Удаление существующих новостей
 * @author Vika
 *
 */
class RemoveNews {
	
	protected $id;
	
	public function __construct() {
		
		/**
		 * Создание соединения с БД
		 * @var object
		 */
		$db = new DataBase;
		
		/**
		 * Проверка, является ли пользователь админом
		 */
		if (!ADMIN) {
			
			die('Only admin can remove news');
		} else {
			
			if (isset($_GET['id'])) {
			
				$this->id = intval($_GET['id']);
				
				/**
				 *Запрос к БД: удаление новости и соответствующих ей комментариев
				 * @var mixed
				 */
				$result = $db->mysqli->query("DELETE 
						FROM `news`
						WHERE `id` = $this->id
						LIMIT 1");
				$result1 = $db->mysqli->query("DELETE 
					FROM `comments`
					WHERE `entry_id` = $this->id
					");
				
				/**
				 * Переадресация на главную страницу с новостями
				 */
				header('Location: /mvc/public/home/news');
			}
			
			/**
			 * Закрытие соединения с БД
			 */
			$db->mysqli->close();
		}
	}
}