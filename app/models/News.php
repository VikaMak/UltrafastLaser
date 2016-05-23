<?php
/**
 * Новости сайта и комментарии к соответствующим новостям
 * @author Vika
 *
 */
class News {
	
	public $records = [];
	public $page;
	public $pages;
	protected $id;
	public $records1 = [];
	public $comments = [];
	protected $title_new;
	protected $catagory_new;
	protected $text_new;
	
	/**
	 * Подключение функций, необходимых для проверки данных
	 */
	use Check;
	
	public function __construct() {
		
		/**
		 * Открытие соединения с БД
		 * @var object
		 */
		$db = new DataBase;
		
		/**
		 * Пагинация
		 * Параметр, передаваемый в URL и определяющий номер страницы с новостями, 
		 * на которой находится $limit новостей
		 * @var integer
		 */
		$this->page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
		
		/**
		 * Максимальное число новостей на одной странице
		 * @var integer
		 */
		$limit = 4;
		
		/**
		 * Параметр, определяющий на какое количество новостей нужно сместиться вниз при выборке новостей
		 * @var integer
		 */
		$offset = ($this->page - 1)*$limit;
		
		/**
		 * Количество всех существующих новостей
		 * @var integer
		 */
		$pages_result = $db->mysqli->query("SELECT COUNT(*) AS `num` FROM `news`")->fetch_assoc();
		
		if ($pages_result['num'] % $limit == 0) {
			
			/**
			 * Параметр, определяющий количество страниц с новостями, на каждой из которых
			 * находится $limit новостей
			 * @var integer
			 */
			$this->pages = $pages_result['num']/$limit;
		} else {
			$this->pages = ceil($pages_result['num']/$limit);
		}
		
		/**
		 * Выборка всех существующих новостей и соответствующих им комментариев
		 */
		if ($result = $db->mysqli->query("SELECT `news`.* , COUNT(`comments`.`id`) AS `comm`
										FROM `news`
										LEFT JOIN `comments`
										ON `news`.`id` = `comments`.`entry_id`
										GROUP BY `news`.`id`
										ORDER BY `date` DESC
										LIMIT $offset, $limit")) {
										
			while ($row = $result->fetch_assoc()) {
				
				$row['catagory'] = $this->check_data($row['catagory']);
				$row['title'] = $this->check_data($row['title']);
				
				if (mb_strlen($row['text']) > 150) {
					$row['text'] =  mb_substr($this->check_data($row['text']), 0, 147). '...';
				}
				
				$row['text'] = nl2br($row['text']);
				$this->records[] = $row;
			}
			
			/**
			 * Освобождение памяти от запроса
			 */
			$result->free();
		}
		
		/**
		 * Выборка отдельной новости по id, переданному в URL
		 */
		if (isset($_GET['id'])) {
			
			$this->id = intval($_GET['id']);
			
			if ($result = $db->mysqli->query("SELECT * FROM `news`
											  WHERE `id`=$this->id
											  LIMIT 1")) {
				
				$row1 = $result->fetch_assoc();
				$row1['catagory'] = $this->check_data($row1['catagory']);
				$row1['title'] = $this->check_data($row1['title']);
				$row1['text'] = nl2br($this->check_data($row1['text']));
				$this->records1[] = $row1;
			}
			
			/**
			 * Освобождение памяти от запроса
			 */
			$result->free();
			
			/**
			 * Выборка комментариев к отдельной новости
			 */
			if ($result = $db->mysqli->query("SELECT * FROM `comments`
											  WHERE `entry_id`=$this->id
											  ORDER BY `date` DESC
											  LIMIT 10")) {
				
				while ($row_com = $result->fetch_assoc()) {
					
					$row_com['name'] = $this->check_data($row_com['name']);
					$row_com['text'] = nl2br($this->check_data($row_com['text']));
					$this->comments[] = $row_com;
				}
			}
			
			/**
			 * Освобождение памяти от запроса
			 */
			$result->free();			
		}
		
		/**
		 * Переадресация на страницу редактирования новости (для админа)
		 */
		if (isset($_GET['act']) && $_GET['act'] == 'edit-news') {
			
			header('Location: /mvc/public/home/editnews?id='.$_GET['id']);						
			
		}
		
		/**
		 * Переадресация на страницу(скрипт) удаления новости (для админа)
		 */
		if (isset($_GET['act']) && $_GET['act'] == 'remove-news') {
			
			header('Location: /mvc/public/home/removenews?id='.$_GET['id']);
			
		}
		
		/**
		 * Добавление новой новости (для админа)
		 */
		if (isset($_GET['act']) && $_GET['act'] == 'add-news') {
			
			/**
			 * Проверка данных, переданных через форму методом POST
			 * @var mixed
			 */
			$this->title_new = $this->check_data_in($_POST['title']);
			$this->catagory_new = $this->check_data_in($_POST['catagory']);
			$this->text_new = $this->check_data_in($_POST['text']);
			
			/**
			 * Подготовленный запрос: вставка новой новости в таблицу news
			 */
			if ($result = $db->mysqli->prepare("INSERT INTO `news` (`catagory`, `title`, `text`, `date`)
												VALUES (?, ?, ?, NOW())
												")) {
																	
				$result->bind_param('sss', $this->catagory_new, $this->title_new, $this->text_new);
				$result->execute();
				
				/**
				 * Закрытие подготовленного запроса
				 */
				$result->close();
				
				/**
				 * Переадресация на главную страницу с новостями
				 */
				header('Location: /mvc/public/home/news');
			}
		
		}
		
		/**
		 * Закрытие соединения с базой данных
		 */
		$db->mysqli->close();
		
	}
	
	
	
}