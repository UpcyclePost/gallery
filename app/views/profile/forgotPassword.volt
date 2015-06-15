<div class="login-container">
    <form method="post" id="login-form">
        <div class="login-panel">
            <div class="login-panel-header">
                <h1>Forgot Password</h1>
            </div>
            <div class="upload-panel-body">
                {{ content() }}
                <form role="form">
                    <div class="form-group">
                        <label for="email">What is your e-mail address?</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required tabindex="1">
                    </div>
                    <button type="submit" class="btn btn-md btn-blue btn-collapse login-register" tabindex="5"><i class="fa fa-sign-in"></i> Reset Password</button>
                </form>

            </div>
        </div>
    </form>
</div>