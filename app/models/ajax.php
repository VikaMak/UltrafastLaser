<?php
require_once '../core/DataBase.php';
require_once '../core/Check.php';

class Ajax {
	
	public $array = [];
	protected $id;
	protected $name;
	protected $comment;
		
	use Check;
	
	public function __construct() {
		
		$db = new DataBase();
	
		if (isset($_POST['name'], $_POST['comment'])) {
		
			$this->id = intval($_POST['id']);
			$this->name = $this->check_data_in($_POST['name']);
			$this->comment = $this->check_data_in($_POST['comment']);
			
			$result1 = $db->mysqli->prepare("INSERT INTO `comments` (`entry_id`, `name`, `text`, `date`)
											VALUES (?,?,?,NOW())");
			$result1->bind_param('iss',$this->id, $this->name, $this->comment);
			$result1->execute();
			$result1->close();
			
			$this->array = [	
					'name'    => $this->name,
					'comment' => $this->comment,
			];
			echo json_encode($this->array);
		}
		
		$db->mysqli->close();
		}
	
}
	
$ajax = new Ajax();
