<?php

require_once '../init.php';

class Ajax {
    
    public $ajaxResult = [];
    private $id;
    private $name;
    private $comment;
    private $db;
        
    use Check;
    
    public function __construct()
    {
        $this->db = DataBase::getInstance();
    
        if (isset($_POST['name'], $_POST['comment'])) {
        
            $this->id = intval($_POST['id']);
            $this->name = $this->check_data_in($_POST['name']);
            $this->comment = $this->check_data_in($_POST['comment']);
            
            $this->db->query("INSERT INTO comments (entry_id, name, text, date)
                                  VALUES (?,?,?,NOW())", [
                                  $this->id, $this->name, $this->comment])->results();
            
            $this->ajaxResult = [                     
                    'name'    => $this->name,
                    'comment' => $this->comment,
            ];
            
            echo json_encode($this->ajaxResult);
            
        }
        
    }
    
}
    
$ajax = new Ajax();