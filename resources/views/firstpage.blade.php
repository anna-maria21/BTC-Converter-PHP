@extends('layouts.app')

@section('title-block')Вхід @endsection

@section('content')
    <nav class="container d-flex header mt-5">
        <a class="py-2 d-none d-md-inline-block " href="{{ route('firstpage') }}">Вхід</a>
        <a class="py-2 d-none d-md-inline-block ml-5" href="{{ route('create') }}">Реєстрація</a>
    </nav>
    <h1 class="text-center mt-5">Увійдіть в систему</h1>

    <div class="send container">
        
        @if($errors->any())
            <ul>
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            </ul>
        @endif

        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" placeholder="Введіть email" id="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                
                <input type="password" id="password" placeholder="Введіть пароль" name="password" class="form-control">
            
            </div>
            <button type="submit" class="btn-lg btn-success">Увійти</button>
        </form>
    </div>

@endsection