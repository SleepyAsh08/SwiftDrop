@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row gx-lg-5 align-items-center">
        <div class="col-lg-6 mb-5 p-4 mb-lg-0 text-center bg-success">
            <img src="/images/AgrishopLogo.png" alt="" height="120px">
          <h1 class="my-3 display-4 fw-bold ls-tight">
            <span>NEW HERE?</span></br>
          </h1>
          <h1 class="my-3 lead fw-bold ls-tight">
            Connect Courier and Sellers easily - Sign up to pick up and deliver products today!
          </h1>
          <a type="button"class="btn btn-success text-dark rounded-pill border-white" style="color: black" href="{{ route('register') }}">Sign Up</a>
        </div>

        <div class="col-lg-6 mb-5 mb-lg-0">
          <div class="card">
              <div class="card-body py-5 px-md-5">
                  <form method="POST" action="{{ route('login') }}">
                      @csrf
                      <h2 class="fw-bold mb-2 text-uppercase text-center">Login</h2>
                      <p class="mb-5 text-center">Please enter your Email and password!</p>
                      <div class="row mb-3">
                          <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                          <div class="col-md-6">
                              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                              @error('email')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                      </div>

                      <div class="row mb-3">
                          <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                          <div class="col-md-6">
                              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                              @error('password')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                      </div>

                      {{-- <div class="row mb-3">
                          <div class="col-md-6 offset-md-4">
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                  <label class="form-check-label" for="remember">
                                      {{ __('Remember Me') }}
                                  </label>
                              </div>
                          </div>
                      </div> --}}

                      <div class="row mb-0">
                          <div class="col-4 offset-8">
                              <button type="submit" class="btn btn-success">
                                  {{ __('Login') }}
                              </button>

                              {{-- @if (Route::has('password.request'))
                                  <a class="btn btn-link" href="{{ route('password.request') }}">
                                      {{ __('Forgot Your Password?') }}
                                  </a>
                              @endif --}}
                          </div>
                      </div>
                  </form>
              </div>
          </div>
        </div>
    </div>
</div>
@endsection
