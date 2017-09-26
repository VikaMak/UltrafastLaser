<?php

trait Check {
	
	public function check_data_in($data)
    {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

		return $data;
	}
	
	public function check_data($data)
    {
		$data = trim($data);
		$data = strip_tags($data);

		return $data;
	}
}