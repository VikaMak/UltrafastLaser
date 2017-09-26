<?php
/**
 * Авторизации зарегистрированных пользователей
 * @author Vika
 *
 */
class AuthModel
{   
    public $login;
    private $password;
    public $error = '';
    private $db;
    
    /**
     * Подключение функций, необходимых для проверки данных
     */
    use Check;
    
    public function __construct()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $this->db = DataBase::getInstance();
            
            
            $this->login = $this->check_data_in($_POST['login']);
            $this->password = $this->check_data_in($_POST['pass']);
            
            /**
             * Выборка из таблицы users пользователя
             * с введенным логином (если существует)
             */
            if ($result_db = $this->db->query("SELECT * FROM users
                                            WHERE login=?                 
                                            AND active=1
                                            LIMIT 1", [$this->login])) {
                                                        
                $result = $result_db->results();

                foreach ($result as $v) {
                    $row = [
                        'password' => $v->password,
                        'access' => $v->access ];
                }
                
            }
            
            /**
             * Проверка введенного пароля на совпадение с паролем из БД
             */
            if ($result_db->count() AND password_verify($this->password, $row['password'])) {
    
                $_SESSION['user'] = $row;
                
                /**
                 * Проверка, является ли данный пользователь администратором
                 */
                if ($row['access'] == 2) {
                    
                    $_SESSION['ADMIN'] = true;
                }
                
            } else {
                
                $this->error = 'Нет пользователя с таким логином и паролем';
            }
            
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
            header('Location: /02/UltrafastLaser/public/auth/authuser');
            die();
        }
    }
    
    
}