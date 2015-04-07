<div class="login-container">
    <form method="post" id="login-form">
    <div class="login-panel">
        <div class="login-panel-header">
            <h1>Sign In</h1>
        </div>
        <div class="upload-panel-body">
            {{ content() }}
            <form role="form">
                <div class="form-group">
                    <label for="email">What is your e-mail address?</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required tabindex="1">
                </div>
                <div class="form-group">
                    <label for="password">Enter your Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required tabindex="2">
                    <a href="{{ url('/profile/forgot-password') }}">Forgot your password?</a>
                    <br /><br />
                    <a href="{{ url('profile/register') }}">Not a member yet?  Click here to sign up</a>
                </div>
                <button type="submit" class="btn btn-md btn-blue btn-collapse login-register" tabindex="5"><i class="fa fa-sign-in"></i> Let's Go</button>
            </form>

        </div>
    </div>
    </form>
</div>