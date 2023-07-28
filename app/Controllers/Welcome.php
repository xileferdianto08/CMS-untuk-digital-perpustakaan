<?php

namespace App\Controllers;

class Welcome extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Welcome Page'
        ];
        echo view('welcome', $data);

    }

}
