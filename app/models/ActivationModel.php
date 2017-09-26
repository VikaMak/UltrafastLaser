<?php
/**
 * Активации зарегистрированного пользователя
 * @author Vika
 *
 */
class ActivationModel
{
    
    private $id;
    private $hash;
    public $info;
    private $db;

    public function __construct()
    {
        if(isset($_GET['hash'], $_GET['id'])) {

            $this->db = DataBase::getInstance();

            /**
             * Данные, содержащиеся в URL строке, по которой пользователь должен пройти 
             * для успешной активации
             * @var mixed
            */
            $this->id = intval($_GET['id']);
            $this->hash = $_GET['hash'];
            
            if ($result = $this->db->query("UPDATE users SET
                                            active=1 WHERE
                                            id=? AND
                                            hash=?", 
                                            [$this->id, $this->hash])) {

                $result->results();
            }

            $this->info='Вы успешно прошли активацию на нашем сайте';
        }else {

            $this->info='Вы прошли по неверной ссылке';
        }
    }
}