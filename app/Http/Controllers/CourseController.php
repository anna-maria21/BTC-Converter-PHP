<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller {
    
    public function rate(Request $req, $thisEmail) {

        $finalRate = app()->call('App\Http\Controllers\UsersController@btctouah');
        $finalRate2 = app()->call('App\Http\Controllers\UsersController@uahtobtc', ["finalRate"=>$finalRate]);
        
        $finalRate = $finalRate*$req->btc;
        $finalRate2 = $finalRate2*$req->uah2;

        return view('home', ['email'=>$thisEmail, 'coins'=>$req->btc, 'uah'=>$req->uah2, 'rate'=>$finalRate, 'rate2'=>$finalRate2]);
    }
}
