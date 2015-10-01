{{ content() }}

<div class="account-settings-container">
    <div class="login-panel">
        <form class="form-horizontal" role="form" method="post" id="account-settings-form">
            <div class="login-panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Email</label>
                    <div class="col-xs-6 col-sm-5">
                        <font class="btn btn-gray link-label">{{ profile.email }}</font>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Username</label>
                    <div class="col-xs-6 col-sm-5">
                        <input type="text" class="form-control" id="username" name="userName" required value="{{ profile.user_name }}" minLength="5" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <a href="#" class="btn btn-gray" data-toggle="modal" data-target="#myModal"><i class="fa fa-lock"></i> Change Your Password</a>
                    </div>
                </div>
            </div>
        <div class="login-panel-footer text-right">
            <button type="reset" class="btn btn-gray">Reset</button> <button type="submit" class="btn btn-green"><i class="fa fa-check"></i> Save Changes</button>
        </div>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Change Password</h4>
            </div>
            <form class="form-horizontal" role="form" method="post" id="change-password-form">
            <div class="modal-body">
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">Current Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <a href="{{ url('/profile/forgot-password') }}">Forgot your password?</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">New Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">Retype New Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="newPasswordConfirm" name="newPasswordConfirm" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <a data-dismiss="modal" class="btn btn-gray">Cancel</a> <button type="submit" class="btn btn-green"><i class="fa fa-check"></i> Change Password</button>
            </div>
            </form>
        </div>
    </div>
</div>