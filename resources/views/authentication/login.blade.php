@extends("common.base")

@section("base_content")

    <div class="login-page">
        <div class="form">
            <img src="/images/logo.png" class="img-responsive" style="padding-bottom: 10px;"/>
            <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <input type="text" placeholder="Username"/>
                <input type="password" placeholder="Password"/>
                <button>login</button>
                <p class="message">Not registered? <a href="/auth/register">Create an account</a></p>
            </form>
        </div>
    </div>

@stop