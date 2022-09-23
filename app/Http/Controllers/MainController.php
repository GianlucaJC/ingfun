<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


use DB;


class mainController extends Controller
{
	public function dashboard() {
		$name="";
		return view('all_views/dashboard')->with('name', $name);
	}

}
