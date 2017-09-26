<?php

class Theory extends Controller
{
    public function laserTheory ($name) {
        
        $this->view('home/theory/'.$name, []);
    }
}