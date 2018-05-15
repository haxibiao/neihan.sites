<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcome() {
    	$data['user'] = 'pengchong ...';
    	return view('welcome')->withData($data);
    }
}
