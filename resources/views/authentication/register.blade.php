@extends("common.base")

@section("base_content")

    <div class="login-page">
        <div class="form">
            <img src="/images/logo.png" class="img-responsive" style="padding-bottom: 10px;"/>
            <form action="/auth/register/submit">
                <input type="text" placeholder="Name"/>
                <input type="text" placeholder="Username"/>
                <input type="password" placeholder="Password"/>
                <input type="password" placeholder="Confirm password"/>
                <input type="text" placeholder="Email address"/>
                <button>Register</button>
                <p class="message">Already registered? <a href="/auth/login">Sign In</a></p>
            </form>
        </div>
    </div>

@stop