<?php

class Home extends Controller {
	
	public function index() {
		
		$this->view('home/index', []);
	}
	
	public function registration() {		
		
		$user = $this->model('Registration');	
		$this->view('home/registration',['login'     =>$user->login,
										'password'   =>$user->password,
										'email'      =>$user->email,
										'age'        =>$user->age,
										'er_login'   =>$user->errors['login'],
										'er_password'=>$user->errors['password'],
										'er_email'   =>$user->errors['email'],
		]);
	}
	
	public function activation() {
		
		$user = $this->model('Activation');
		$this->view('home/activation', ['info'=>$user->info,]);
	}
	
	public function auth() {
		
		$user = $this->model('Auth');
		$this->view('home/auth', [//'status'=>$user->status,
								  'error' =>$user->error,
								  'login' =>$user->login,
								  
		]);
	}
	
	public function theory ($name) {
		
		$this->view('home/theory/'.$name, []);
	}
	
	public function news() {
		
		$user = $this->model('News');
		$this->view('home/news', [$user->records,
								  $user->records1,
								  $user->comments,
								  'page'   =>$user->page,
								  'pages'=>$user->pages,
		]);
	}
	
	public function editnews() {
		
		$user = $this->model('EditNews');
		$this->view('home/editnews', [$user->entry,
		]);
	}
	
	public function removenews() {
		
		$user = $this->model('RemoveNews');
		
	}
}
