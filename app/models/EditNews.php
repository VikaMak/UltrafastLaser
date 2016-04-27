<?php

/**
 * Редактирование новостей
 * @author Vika
 *
 */
class EditNews {
	
	protected $id;
	protected $id_edit;
	public $entry = [];
	protected $title;
	protected $catagory;
	protected $text;
	
	/**
	 * Подключение функций, необходимых для проверки данных
	 */
	use Check;
	
	public function __construct() {
		
		$db = new DataBase;
		
		if (!ADMIN) {
			die ('Only admin can edit news');
		} else {
			
			if (isset($_GET['id'])) {
				
				$this->id = intval($_GET['id']);
				
				/**
				 * Поиск новости, которую нужно отредактировать
				 */
				if ($result = $db->mysqli->query("SELECT *
						FROM `news`
						WHERE `id` = $this->id
						LIMIT 1")) {
							
						$row = $result->fetch_assoc();
						$this->entry[] = $row;
						
						$result->free();
				} else {
				
					die ('Cannot edit news');
				}
				
			}
			
			if (isset($_GET['act']) && $_GET['act'] == 'apply-edit-news') {
				
				/**
				 * Проверка введенных данных
				 * @var mixed
				 */
				$this->id_edit = intval($_POST['id']);
				$this->title = $this->check_data_in($_POST['title']);
				$this->catagory = $this->check_data_in($_POST['catagory']);
				$this->text = $this->check_data_in($_POST['text']);
				
				/**
				 * Обновление новости в БД через подготовленный запрос
				 */
				if ($result = $db->mysqli->prepare("UPDATE `news` SET
													`title` = ?,
													`catagory` = ?,
													`text` = ?
													WHERE `id`=?
													LIMIT 1")) {
																		
					$result->bind_param('sssi', $this->title, $this->catagory, $this->text, $this->id_edit);
					
					if ($result->execute()) {
						
						/**
						 * Переадресация на страницу с обновленной новостью
						 */
						header('Location: /mvc/public/home/news?id='.$this->id_edit);
					} else {
						
						die('Cannot update news');
					}
					
					/**
					 * Закрытие подготовленного запроса
					 */
					$result->close();
				}
			}
			
		}
		
		/**
		 * Закрытие соединения с БД
		 */
		$db->mysqli->close();
	}
	
}