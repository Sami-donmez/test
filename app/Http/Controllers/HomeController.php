<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('welcome');
    }
    public function dashboard()
    {
        return view('home');
    }
    public function login($company)
    {
        if ($company == "lagaranta") {
            $title = "Lagaranta";
            $id = 1;
            $bgcolor =  "rgb(213,81,48)";
            $bgcolor2 =  "linear-gradient(90deg, rgba(213,81,48,1) 0%, rgba(213,81,48,1) 99%)";
            $logoimage = asset('img/company/2-t.png');
            $image = asset('img/company/2-login.jpeg');
            $linear = "linear-gradient(90deg, rgba(255,138,108,0.7) 0%, rgba(255,188,171,0.72) 100%)";
        }elseif ($company == "bid"){
            $title = "BID";
            $id = 2;
            $bgcolor =  "rgb(1,47,93)";
            $bgcolor2 =  "linear-gradient(90deg, rgba(1,47,93,1) 0%, rgba(1,47,93,1) 99%)";
            $linear = " linear-gradient(90deg, rgba(149,202,255,0.7) 0%, rgba(195,225,255,0.72) 100%)";
            $logoimage = asset('img/company/3-t.png');
            $image = asset('img/company/1-login.jpeg');

        }elseif ($company == "nederhofje") {
            $title = "Nederhofje";
            $id = 3;
            $bgcolor =  "rgb(144,66,27)";
            $bgcolor2 =  "linear-gradient(90deg, rgba(144,66,27,1) 0%, rgba(144,66,27,1) 96%, rgba(144,66,27,1) 99%)";

            $linear = "linear-gradient(90deg, rgba(255,103,129,0.7) 0%, rgba(255,173,187,0.72) 100%)";
            $logoimage = asset('img/company/1-t.png');
            $image = asset('img/company/3-login.jpg');

        }

        return view('auth.login-company',compact('title','id','image','logoimage','linear','bgcolor','bgcolor2'));
    }
    public function logout(){
        Auth::logout();
        return redirect('login');
    }
}
