<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller {

    /**
     * @var (\Illuminate\Contracts\View\Factory&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    public $viewFactory;

    public function uahtousd($finalRate) {
        return 1/$finalRate;
    }

    public function usdtouah() {
        if(!isset($_SESSION['logged_user'])){
            $_SESSION['logged_user'] = null;
        }
        $coin = $this->getFileContent();
        $usdUAH = 0.0;
        foreach ($coin as $value) {
            if ($value['ccy'] == 'USD') {
                $usdUAH = $value['buy'];
            }
        }
        return $usdUAH;
    }

    public function getFileContent() {
        $link = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';
        $coin = file_get_contents($link);
        return json_decode($coin, true);
    }

    public function login(Request $req) {
        if(!isset($_SESSION['logged_user'])){
            $_SESSION['logged_user'] = null;
        }
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $messages = [
                'email.required' => "Будь ласка, введіть eлектронну пошту.",
                'email.email' => "Електронну пошту введено невірно.",
                'password.required' => "Пароль є обов'язковим для заповнення."
        ];

        $validator = Validator::make($req->all(), $rules, $messages);
            if ($validator->fails()) {
                return redirect()->back()
                            ->withErrors($validator)->withInput($req->all());
            }

        $count = 0;
        $string = Storage::get('file.txt');
        $lines = explode("\n", $string);
        $users =  array();
        $thisEmail = trim($req->email) . "\r";
        $thisPassword = trim($req->password) . "\r";


        foreach($lines as $line) {
            $count = $count + 1;
            array_push($users, $line);
        }
        for ($i=0; $i < $count; $i+=2) {

            if ($users[$i]==$thisEmail && $users[$i+1]==$thisPassword) {
                $finalRate = $this->usdtouah();
                $finalRate2 = $this->uahtousd($finalRate);

                $_SESSION['logged_user'] = $thisEmail;
                return view('home', ['email'=>$thisEmail, 'coins'=> 1, 'uah'=> 1, 'rate'=>$finalRate, 'rate2'=>$finalRate2]);
            }
        }
        return redirect()->back()->withErrors(['errors' => ['Користувача з такими логіном і паролем не знайдено. Перевірте правильність їх введення або зареєструйтеся.']]);
    }

    public function create(Request $req) {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
            'password2' => 'required'
        ];

        $messages = [
            'email.required' => "Будь ласка, введіть eлектронну пошту.",
            'email.email' => "Електронну пошту введено невірно.",
            'password.required' => "Введення пароля є обов'язковим.",
            'password2.required' => "Повторення паролю є обов'язковим."
        ];

        $validator = Validator::make($req->all(), $rules, $messages);
            if ($validator->fails()) {
                return redirect()->back()
                        ->withErrors($validator)->withInput($req->all());
            }
            $count = 0;
            $string = Storage::get('file.txt');
            $lines = explode("\n", $string);
            $emails =  array();
            $passwords = array();
            $thisEmail = trim($req->email);
            foreach($lines as $line) {
                $count = $count + 1;
            }
            for ($i = 0; $i < $count; $i++) {
                if ($i % 2 == 0) {
                    array_push($emails, $lines[$i]);
                } else {
                    array_push($passwords, $lines[$i]);
                }
            }
            for ($i=0; $i < $count/2; $i++) {
                if ($emails[$i]==$thisEmail . "\r") {
                    return redirect()->back()->withErrors(['errors' => ['Користувач з таким логіном уже зареєстрований.']]);
                }
            }
            if ($req->password != $req->password2) {
                return redirect()->back()->withErrors(['errors' => ['Паролі мають співпадати.']])->withInput($req->all());
            }

            $filename = 'file.txt';
            $this->writeToFile($filename, $thisEmail, $req->password);
            $finalRate = $this->usdtouah();
            $finalRate2 = $this->uahtousd($finalRate);
            $_SESSION['logged_user'] = $thisEmail;
            return view('home', ['email'=>$thisEmail, 'coins'=> 1, 'uah'=> 1, 'rate'=>$finalRate, 'rate2'=>$finalRate2]);
    }

    public function writeToFile($filename, $email, $password) {
        Storage::append($filename, $email);
        Storage::append($filename, trim($password)."\r");
    }


    public function exit() {
        $_SESSION['logged_user'] = null;
        return view('firstpage');
    }
}
