<?php

/**
 * Редактирование новостей
 * @author Vika
 *
 */
class EditNewsModel {
    
    private $id;
    private $id_edit;
    public $entry = [];
    private $title;
    private $catagory;
    private $text;
    private $db;
    
    
    use Check;
    
    public function __construct() {
        
        $this->db = DataBase::getInstance();
        
        if (!ADMIN) {
            die ('Only admin can edit news');
        } else {
            
            if (isset($_GET['id'])) {
                
                $this->id = intval($_GET['id']);
                
                /**
                 * Поиск новости, которую нужно отредактировать
                 */
                if ($result = $this->db->query("SELECT *
                                                FROM news
                                                WHERE id = $this->id
                                                LIMIT 1")) {
                            
                        $row = $result->results();

                        foreach ($row as $v) {

                            $this->entry = [
                                'id' => $v->id,
                                'date' => $v->date,
                                'catagory' => $this->check_data($v->catagory),
                                'title' => $this->check_data($v->title),
                                'text' => $v->text,
                                ];
                        }                      
                        
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
                if ($result = $this->db->query("UPDATE news SET
                                                title = ?,
                                                catagory = ?,
                                                text = ?
                                                WHERE id=?
                                                LIMIT 1", [
                                                $this->title, $this->catagory, $this->text, $this->id_edit])) {

                    $result->results();
                    header('Location: /02/UltrafastLaser/public/news/lasernews?id='.$this->id_edit);
                } else {
                    
                    die('Cannot update news');
                }                    
                
            }
            
        }
    }
    
}