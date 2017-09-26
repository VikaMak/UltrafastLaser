<?php

class Activation extends Controller
{
    public function activationUser()
    {
        $user = $this->model('ActivationModel');

        $this->view('home/activation', ['info'=>$user->info,]);
    }
}