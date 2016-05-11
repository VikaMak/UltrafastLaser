<?php
/**
 * Регистрация нового пользователя
 * @author Vika
 *
 */
class Registration {
	
	/**
	 * $login, $password, $email, $age - переменные для хранения данных, вводимых пользователем
	 * @var mixed
	 */
	public $login = '';
	public $password = '';
	public $email = '';
	public $age = '';
	
	/**
	 * переменная для хранения хэша, используемого для формирования ссылки,
	 * перейдя по которой пользователь может активироваться на сайте
	 * @var string
	 */
	protected $hash;
	
	protected $id;
	
	/**
	 * $to, $subject, $message - переменные, необходимые для отправки подтверждения регистрации пользователю
	 * @var string
	 */
	protected $to;
	protected $subject;
	protected $message;
	
	/**
	 * Массив для хранения сообщений об ошибках
	 * @var array
	 */
	public $errors = array('login'=>'', 'password'=>'', 'email'=>'');
	
	use Check;
	
	public function __construct() {
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
			/**
			 * Проверка введенного логина
			 */
			if (empty($_POST['login'])) {
				$this->errors['login'] = 'Вы не ввели логин';
			} elseif (mb_strlen($_POST['login'])<2) {
				$this->errors['login'] = 'Логин слишком короткий';
			} elseif (mb_strlen($_POST['login'])>20) {
				$this->errors['login'] = 'Логин слишком длинный';
			} else {
				$this->login = $this->check_data_in($_POST['login']);
			}
			
			/**
			 * Проверка введенного пароля
			 */
			if (empty($_POST['password'])) {
				$this->errors['password'] = 'Вы не ввели пароль';
			} elseif (mb_strlen($_POST['password'])<5) {
				$this->errors['password'] = 'Ваш пароль не должен содержать меньше 5 символов';
			} else {
				$this->password = $this->check_data_in($_POST['password']);
			}
			
			/**
			 * Проверка введенного email
			 */
			if (empty($_POST['email'])) {
				$this->errors['email'] = 'Вы не ввели e-mail';
			} else {
				$this->email = $this->check_data_in($_POST['email']);
			
				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
					$this->errors['email'] = 'Вы ввели несуществующий e-mail';
				}
			}
			
			/**
			 * Проверка введенного возраста
			 */
			if (empty($_POST['age'])) {
				$this->age ="";
			} else {
				$this->age = $this->check_data_in($_POST['age']);
			}
			
			/**
			 * Проверка на наличие сообщениий об ошибках в введенных данных
			 */
			if ( empty($this->errors['login']) and empty($this->errors['password']) and empty($this->errors['email']) ) {
				
				/**
				 * Создание соединения с БД
				 * @var object
				 */
				$db = new DataBase;				
				
				/**
				 * Подготовленный запрос: выборка из таблицы users пользователя по введенному логину
				 */
				if ($result = $db->mysqli->prepare("SELECT `id` FROM `users`
													WHERE `login`=? 
													LIMIT 1")) {
					
					$result->bind_param('s', $this->login);
					$result->execute();
					$result = $result->get_result();
					
					if ($result->num_rows) {
						
						/**
						 * Фиксация сообщения об ошибке при существовании введенного логина
						 */
						$this->errors['login'] = 'Такой логин уже занят';
					}
					/**
					 * Закрытие подготовленного запроса
					 */
					$result->close();
				}
				
				/**
				 * Подготовленный запрос: выборка из таблицы users пользователя по введенному email
				 */
				if ($result = $db->mysqli->prepare("SELECT `id` FROM `users`
													WHERE `email`=?
													LIMIT 1")) {
													
					$result->bind_param('s', $this->email);
					$result->execute();
					$result = $result->get_result();
					
					if ($result->num_rows) {
						
						/**
						 * Фиксация сообщения об ошибке при существовании введенного email
						 */
						$this->errors['email'] = 'Такой email уже занят';
					}
					/**
					 * Закрытие подготовленного запроса
					 */
					$result->close();
				}
				
				/**
				 * Проверка на наличие сообщений об ошибках
				 */
				if ( empty($this->errors['login']) and empty($this->errors['password']) and empty($this->errors['email']) ) {
					
					/**
					 * Подготовленный запрос: вставка сведений о новом пользователе в таблицу users
					 * @var mixed
					 */
					$result = $db->mysqli->prepare("
							INSERT INTO `users` (`login`,`password`,`email`,`age`,`hash`)
							VALUES (?,?,?,?,?)");
					$this->age = intval($this->age);
					
					/**
					 * Хэширование пароля с использованием алгоритма bcrypt
					 */
					$this->password = password_hash($this->password, PASSWORD_DEFAULT);
					
					/**
					 * переменная для хранения хэша, используемого для формированияя ссылки, 
					 * перейдя по которой пользователь может активироваться на сайте
					 * @var string
					 */
					$this->hash = crypt($this->login.$this->age,'azz');
					$result->bind_param('sssis', $this->login, $this->password, $this->email, $this->age, $this->hash);
					$result->execute();	
										
					$this->id = $db->mysqli->insert_id;
					
					/**
					 * Закрытие подготовленного запроса
					 */
					$result->close();
					
					/**
					 * Закрытие соединения с БД
					 */
					$db->mysqli->close();
					
					/**
					 * Переменная сессии, обозначающая успешное заполнение формы новым пользователем
					 * и последующая вставка сведений о нем в БД
					 */
					$_SESSION['regok'] = 'OK';
					
					$this->to = $this->email;
					$this->subject = 'Registration';
					$this->message = 
					'Пройдите по ссылке для активации вашего аккаунта: http://localhost/mvc/public/home/activation&id='.$this->id.'&hash='.$this->hash;
					
					/**
					 * Отправление сообщения об активации новому пользователю
					 */
					mail($this->to, $this->subject, $this->message);
					
					/**
					 * Переадресация на страницу регистрации с сообщением о том, что на email
					 * отправлена ссылка для активации пользователя на сайте
					 */
					header('Location: /mvc/public/home/registration');
					die();
				}
				
			}			
			 
		}
	}
	
}
