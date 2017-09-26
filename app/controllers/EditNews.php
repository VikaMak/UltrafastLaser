<?php

class EditNews extends Controller
{
    public function editNewsLaser()
    {
        $user = $this->model('EditNewsModel');

        $this->view('home/editnews', [$user->entry,]);
    }
}