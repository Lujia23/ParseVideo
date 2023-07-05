<?php

namespace App\Http\Controllers;

class Index extends Controller
{
    public function index()
    {
        return view('index', [
            'title' => '解析工具'
        ]);
    }
}
