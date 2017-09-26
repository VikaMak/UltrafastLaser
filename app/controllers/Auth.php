<?php

class Auth extends Controller
{
    public function authUser()
    {
        $user = $this->model('AuthModel');
        $this->view('home/auth',   [//'status'=>$user->status,
                              'error' =>$user->error,
                              'login' =>$user->login,
        ]);
    }
}