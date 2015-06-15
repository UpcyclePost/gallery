<style type="text/css">
    #page {
        background: url('{{ static_url('img/sign-up.jpg') }}') #fff no-repeat center top fixed;
        background-size: cover;
    }
    #register-form {
        opacity: .95;
    }
</style>

<div class="login-container">
    <form method="post" id="register-form">
    <div class="login-panel">
        <div class="login-panel-header">
            <h1>Sign up and become an UpcyclePost Insider</h1>
        </div>
        <div class="upload-panel-body">
            {{ content() }}
            <form role="form">
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" class="form-control" id="first-name" name="firstName" placeholder="Enter your first name" required minLength="3" tabindex="1">
                </div>

                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" class="form-control" id="last-name" name="lastName" placeholder="Enter your last name" required minLength="2" tabindex="2">
                </div>

                <div class="form-group">
                    <label for="user-name">User Name</label>
                    <input type="text" class="form-control" id="user-name" name="userName" placeholder="Choose a user name" required minLength="5" tabindex="3">
                </div>

                <div class="form-group">
                    <label for="email">What is your e-mail address?</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required tabindex="4">
                </div>
                <div class="form-group">
                    <label for="password">Enter a password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter a password" required tabindex="5">
                </div>

                <div class="form-group">
                    <label for="password-confirm">Type it Again</label>
                    <input type="password" class="form-control" id="password-confirm" name="passwordConfirm" placeholder="Last time, we promise!" tabindex="6">
                </div>

                <div class="form-group subscribe">
                    <h4>Sign me up to become an UpcyclePost Insider &#9786;</h4>
                    <div style="float: left; width: 25px;"><input type="checkbox" name="mcRegister" id="mc-register" checked> </div>
                    <div style="float: left; width: 400px;">
                    <p><label for="mc-register" style="font-weight: normal;">Join our community of upcyclers and receive monthly news, promotions, featured items &amp; special offers.</label></p>
                    </div>
                </div>

                <button type="submit" class="btn btn-md btn-blue btn-collapse" tabindex="7"><i class="fa fa-user"></i> Let's Go</button>
                <br /><br />
                By signing up you have accepted our <a href="{{ url('terms') }}" target="_blank">Terms of Use</a> and <a href="{{ url('privacy') }}" target="_blank">Privacy Policy</a>.
            </form>

        </div>
    </div>
    </form>
</div>