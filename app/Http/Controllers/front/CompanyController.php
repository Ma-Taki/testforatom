<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    public function index(){
        return view('front.company');
    }

    public function store(){
        return view('front.company');
    }
}
