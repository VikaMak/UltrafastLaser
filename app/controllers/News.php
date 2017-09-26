<?php

class News extends Controller
{
    public function laserNews()
    {
        $user = $this->model('NewsModel');

        $this->view('home/news', [$user->records,
                                  $user->records1,
                                  $user->comments,
                                  'page'   =>$user->page,
                                  'pages'=>$user->pages,
        ]);
    }
}