<?php
/**
 * Авторизации зарегистрированных пользователей
 * @author Vika
 *
 */
class Auth {
	
	public $login;
	protected $password;
	public $error = '';
	
	/**
	 * Подключение функций, необходимых для проверки данных
	 */
	use Check;
	
	public function __construct() {
	
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
			/**
			 * Подключение к БД
			 * @var object
			 */
			$db = new DataBase;
			
			/**
			 * Проверка данных, введенных пользователем
			 * @var mixed
			 */
			$this->login = $this->check_data_in($_POST['login']);
			$this->password = $this->check_data_in($_POST['pass']);
			
			/**
			 * Подготовленный запрос: выборка из таблицы users пользователя
			 * с введенным логином (если существует)
			 */
			if ($result = $db->mysqli->prepare("SELECT * FROM `users`
												WHERE `login`=?					
												AND `active`=1
												LIMIT 1")) {
														
				$result->bind_param('s', $this->login);
				$result->execute();
				$result = $result->get_result();
				$row = $result->fetch_assoc();
			}
			
			/**
			 * Проверка введенного пароля на совпадение с паролем из БД
			 */
			if ($result->num_rows AND password_verify($this->password, $row['password'])) {
	
				$_SESSION['user'] = $row;
				
				/**
				 * Проверка, является ли данный пользователь администратором
				 */
				if ($row['access'] == 2) {
					
					$_SESSION['ADMIN'] = true;
				}
				
			} else {
				
				/**
				 * Сообщение об ошибке, если не существует пользователя с данным логином и паролем
				 * @var string
				 */
				$this->error = 'Нет пользователя с таким логином и паролем';
			}
			
			$result->close();
			
			$db->mysqli->close();
		}
		
		/**
		 * Завершение сеанса работы пользователя
		 */
		if (isset($_GET['act']) && ($_GET['act'] == 'user_exit')) {
			
			if ($_SESSION['user']['access'] == 2) {
				
				unset($_SESSION['ADMIN']);
			}
			unset($_SESSION['user']);
			
			/**
			 * Переадресация на страницу авторизации
			 */
			header('Location: /mvc/public/home/auth');
			die();
		}
	}
	
	
}