<?php
require_once '../core/DataBase.php';

	function check_data_in($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
		return $data;
	}
	
	$array = [];
	
	$db = new DataBase();
	
	if (isset($_POST['name'], $_POST['comment'])) {
	
		$id = intval($_POST['id']);
		$name = check_data_in($_POST['name']);
		$comment = check_data_in($_POST['comment']);
		
		$result1 = $db->mysqli->prepare("INSERT INTO `comments` (`entry_id`, `name`, `text`, `date`)
												VALUES (?,?,?,NOW())");
		$result1->bind_param('iss',$id, $name, $comment);
		$result1->execute();
		$result1->close();
		
		$array = [	
				'name' => $name,
				'comment' => $comment,
		];
		echo json_encode($array);
	}
	
	$db->mysqli->close();
