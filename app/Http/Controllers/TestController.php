<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Item;

class TestController extends Controller
{
    public function testDbConect(){

      $item = Item::find(4);

      return view('test', compact('item'));
    }

    public function testJenkinsDeploy(){

      $item = Item::find(5);

      return view('test', compact('item'));
    }

}
