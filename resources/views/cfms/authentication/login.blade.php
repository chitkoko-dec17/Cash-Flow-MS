@extends('cfms.authentication.master')

@section('title')login
 {{ $title }}
@endsection

@push('css')
@endpush

@section('content')
<section>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="login-card">
                    <form class="theme-form login-form" action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <h4>Login</h4>
                        <h6>Welcome back! Log in to your account.</h6>
                        @if (session('success'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="form-group">
                            <label>Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="icon-email"></i></span>
                                <input class="form-control" id="email_address" type="email"   name="email" required autofocus/>
                            </div>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="icon-lock"></i></span>
                                <input class="form-control" type="password" id="password"  name="password" required/>
                                <div class="show-hide"><span class="show"> </span></div>
                            </div>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <!-- <div class="form-group">
                            <div class="checkbox">
                                <input id="checkbox1" type="checkbox" />
                                <label for="checkbox1">Remember password</label>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="submit">Sign in</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

	
    @push('scripts')
    @endpush

@endsection