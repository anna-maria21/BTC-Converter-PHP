@extends('layouts.app')

@section('title-block')Success @endsection

@section('content')
    <nav class="container d-flex flex-column flex-md-row justify-content-around header mt-5">
        <a class="py-2 d-none d-md-inline-block" href = " {{ route('exit')}} ">Вихід</a>
    </nav>

    <h1 class="text-center mt-5">HELLO, {{ $_SESSION['logged_user'] }} !</h1>

    <div class="send container d-flex justify-content-around mt-5">
        <form action="{{ route('home', $email) }}" class="w-50 text-center">
            @csrf
            <div class="d-flex justify-content-around">
                <div class="form-group">
                    <label for="email">BTC</label>
                    <input type="text" name="btc" id="btc" class="form-control" value = "{{ $coins }}">
                </div>
                <div class="form-group">
                    <label for="password">UAH</label>
                    <input type="text" name="uah" id="uah" class="form-control" value="{{ $rate }}" readonly>
                </div>
            </div>
            <div class="d-flex justify-content-around mt-5">
                <div class="form-group">
                    <label for="password">UAH</label>
                    <input type="text" name="uah2" id="uah" class="form-control" value="{{ $uah }}">
                </div>
                <div class="form-group">
                    <label for="email">BTC</label>
                    <input type="text" name="btc2" id="btc" class="form-control" value = "{{ $rate2 }}" readonly>
                </div>
            </div>
            <button type="submit" class="btn-lg btn-success w-50 mt-5">Розрахувати</button>
        </form>
    </div>

@endsection