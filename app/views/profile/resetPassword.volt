<div class="login-container">
    <form method="post" id="reset-password-form">
        <div class="login-panel">
            <div class="login-panel-header">
                <h1>Reset Password</h1>
            </div>
            <div class="upload-panel-body">
                {{ content() }}
                <div class="form-group">
                    <label for="password">Choose a new Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required tabindex="2">
                </div>

                <div class="form-group">
                    <label for="password-confirm">Type it Again</label>
                    <input type="password" class="form-control" id="password-confirm" name="passwordConfirm" placeholder="Last time, we promise!" tabindex="3" required>
                </div>

                <button type="submit" class="btn btn-md btn-blue btn-collapse login-register" tabindex="5"><i class="fa fa-sign-in"></i> Reset Password</button>
            </div>
        </div>
    </form>
</div>