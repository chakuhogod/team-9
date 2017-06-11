@extends("common.base")

@section("base_content")

    <div class="login-page">
        <div class="form">
            <img src="/images/logo.png" class="img-responsive" style="padding-bottom: 10px;"/>

            <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif

                    @if ($errors->has('password'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="email" class="control-label">E-Mail Address</label>
                    <input id="email" type="email" name="email" placeholder="E-mail" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="control-label">Password</label>
                    <input id="password" type="password" name="password" placeholder="Password" required>
                </div>

                <button>login</button>
                <p class="message">Not registered? <a href="{{ route('register') }}">Create an account</a></p>
            </form>
        </div>
    </div>

@stop