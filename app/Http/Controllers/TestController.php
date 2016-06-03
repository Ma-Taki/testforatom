<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Test;

class TestController extends Controller
{
    public function testDbConect(){

      $test = Test::find(1);

      return view('test', compact('test'));
    }
}
