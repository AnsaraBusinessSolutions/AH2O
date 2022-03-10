@extends("layouts.app")

@section("content")
<style type='text/css'>
*{
    box-sizing: border-box;
}
body{
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 16px;
    font-weight: 400;
    color: #666666;
    background: #eaeff4;
}

.wrapper{
    margin: 0 auto;
    width: 100%;
    max-width: 1140px;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.container{
    position: relative;
    width: 100%;
    max-width: 600px;
    height: auto;
    display: flex;
    background: #ffffff;
    box-shadow: 0 0 5px #999999;
}
.login .col-left,
.login .col-right{
    padding: 30px;
    display: flex;
}
.login .col-left{
    width: 60%;
    clip-path: polygon(0 0, 0% 100%, 100% 0 );
    background: #2c5875;
}
.login .col-right{
    padding: 60px 30px;
    width: 50%;
    margin-left: -10%;
}

@media(max-width: 575px){
    .login .container{
        flex-direction: column;
        box-shadow: none;
    }
    .login .col-left,
    .login .col-right{
     width: 100%;
     margin: 0;
     clip-path: none;
    }
    .login .col-right{
        padding: 30px;
    }

    
}


.login .login-text{
    position: relative;
    width: 100%;
    color: #ffffff;
}
.login .login-text h2{
    margin: 0 0 15px 0;
    font-size: 30px;
    font-weight: 700;
}
.login .login-text p{
    margin: 0 0 20px 0;
    font-size: 16px;
    font-weight: 500;
    line-height: 22px;
}

.login .login-text .btn{
    display: inline-block;
    padding: 7px 20px;
    font-size: 16px;
    letter-spacing: 1px;
    text-decoration: none;
    border-radius: 30px;
    color: #ffffff;
    outline: none;
    border: 1px solid #ffffff;
    box-shadow: inset 0 0 0 0 #ffffff;
    transition: .3s;
}
.login .login-text .btn:hover{
    color: #4197fe;
    box-shadow: inset 150px 0 0 0  #ffffff;
}


.login .login-form{
    position: relative;
    width: 100%;
}
.login .login-form h2{
    margin: 0 0 15px 0;
    font-size: 22px;
    font-weight: 700;
}
.login .login-form p{
    margin: 0 0 10px 0;
    text-align: left;
    color: #666666;
    font-size: 15px;
}
.login .login-form p:last-child{
    margin: 0;
    padding-top: 3px;
}
.login .login-form p a{
    color: #4197fe;
    font-size: 14px;
    text-decoration: none;
}
.login .login-form label {
    display: block;
    width: 100%;
    margin-bottom: 2px;
    letter-spacing: .5px;
}
.login .login-form p:last-child label{
    width: 60%;
    float: left;
}
.login .login-form label span{
    color: #ff574e;
    padding-left: 2px;
}
.login .login-form input{
    display: block;
    width: 100%;
    height: 35px;
    padding: 0 10px;
    outline: none;
    border: 1px solid #cccccc;
    border-radius: 30px;
}
.login .login-form input:focus{
    border-color: #ff574e;
}
.login .login-form button,
.login .login-form button[type=button] {
    display: inline-block;
    width: 100%;
    margin-top: 5px;
    color: #4197fe;
    font-size: 16px;
    letter-spacing: 1px;
    cursor: pointer;
    background: transparent;
    border: 1px solid #4197fe;
    border-radius: 30px;
    box-shadow: inset 0 0 0 0 #4197fe;
    transition: .3s;
}
.login .login-form button:hover,
.login .login-form button[type=button]:hover{
    color: #ffffff;
    box-shadow: inset 250px 0 0 0 #4197fe;
}
</style>
<div class="wrapper login">
        <div class="container">
            <div class="col-left">
                <div class="login-text">
                    <h2>Welcome Back</h2>
                
                    
                </div>
            </div>

            <div class="col-right">
                <div class="login-form">
                    <h2>Login</h2>
                    <form method="POST" action="#">
                        <p>
                            <label>Email address<span>*</span></label>
                            <input type="email" placeholder="Email" name="email" id="email" value="{{ isset($email)?$email:'' }}" required>
                        </p>
                        <p class="password-section" style="{{ isset($email)?'':'display:none' }}">
                            <label>Password<span>*</span></label>
                            <input type="password" placeholder="Password" name="password" id="password" required>
                        </p>
                        <p class='role_section' style="display:none">
                            <label class='form-label'>Role<span>*</span></label>
                            <select name='role' class='form-control' required>
                                    <option value=''>Select Role for login</option>
                            </select>
                        </p>
                        <p>
                            <button type="submit" name="login" >{{ isset($email)?"Sign In":"Verify" }}</button>
                        </p>
                        <p>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                        </p>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@include("scripts.login_script")