<?php
/**
 * Удаление существующих новостей
 * @author Vika
 *
 */
class RemoveNewsModel {
    
    protected $id;
    protected $db;
    
    public function __construct() {
        
        $this->db = DataBase::getInstance();
        
        
        if (!ADMIN) {
            
            die('Only admin can remove news');
        } else {
            
            if (isset($_GET['id'])) {
            
                $this->id = intval($_GET['id']);
                
                /**
                 *Запрос к БД: удаление новости и соответствующих ей комментариев
                 * @var mixed
                 */
                $result = $this->db->query("DELETE 
                                            FROM news
                                            WHERE id = $this->id
                                            LIMIT 1")->results();
                $result1 = $this->db->query("DELETE 
                                            FROM comments
                                            WHERE entry_id = $this->id
                                            ")->results();
                
                /**
                 * Переадресация на главную страницу с новостями
                 */
                header('Location: /02/UltrafastLaser/public/news/lasernews');
            }
            
        }
    }
}