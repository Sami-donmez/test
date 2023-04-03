@extends('layouts.auth')
@section('css')
    <style>
        .right {
            background: {{$linear}},url({{$image}});
            color: #fff;
            position: relative;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .login {
            height: 1000px;
            width: 100%;
            background:{{$bgcolor}};
            background:{{$bgcolor2}};
            position: relative;

        }
    </style>
    @endsection
@section('content')
    <div class="left">
        <div class="top_link"><a href="/"><img src="https://drive.google.com/u/0/uc?id=16U__U5dJdaTfNGobB_OpwAJ73vM50rPV&export=download" alt="">Terug naar bedrijvenpagina</a></div>
        <div class="contact">
              <form method="POST" action="{{ route('login') }}">
                @csrf
                <input name="company" type="hidden" value="{{$id}}">

                <div class="right-inductor"><img src="{{$logoimage}}" alt=""><h3 class="my-2">LOGIN</h3></div>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">

                @error('email')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Wachtwoord">

                @error('password')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
                <button class="submit">Inloggen</button>
                <div class="text-center my-2" >
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" style="color: grey!important;" href="{{ route('password.request') }}">
                            {{ __('Wachtwoord vergeten?') }}
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>
    <div class="right">
        <div class="right-text">
            <h1 class="text-center">{{$title}}</h1>
        </div>
    </div>
@endsection
