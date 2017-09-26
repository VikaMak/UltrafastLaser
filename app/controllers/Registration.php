<?php 

class Registration extends Controller
{
    public function newUserRegistration() {
        
        $user = $this->model('RegistrationModel');

        $this->view('home/registration',['login'     =>$user->login,
                                        'password'   =>$user->password,
                                        'email'      =>$user->email,
                                        'age'        =>$user->age,
                                        'er_login'   =>$user->errors['login'],
                                        'er_password'=>$user->errors['password'],
                                        'er_email'   =>$user->errors['email'],
        ]);
    }
}