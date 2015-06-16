<div class="login-container">
    <form method="post" id="login-form">
    <div class="login-panel">
        <div class="login-panel-header">
            <h1>Impersonate</h1>
        </div>
        <div class="upload-panel-body">
            {{ content() }}
            <form role="form">
                <div class="form-group">
                    <label for="email">Enter a username or email address</label>
                    <input type="text" class="form-control" id="email" placeholder="Enter username or email address" name="user" required tabindex="1">
                </div>
                <button type="submit" class="btn btn-md btn-blue btn-collapse login-register" tabindex="5"><i class="fa fa-paper-plane-o"></i> Impersonate</button>
            </form>

        </div>
    </div>
    </form>
</div>