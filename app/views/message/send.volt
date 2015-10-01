{{ content() }}

<div class="account-settings-container">
    <div class="login-panel">
        <div class="login-panel-header messages">
            <h4>Compose Message</h4>
            Write {{ profile.user_name }} a message.
        </div>
        <form class="form-horizontal" role="form" method="post">
            <div class="login-panel-footer text-right">
                <button type="submit" class="btn btn-green"><i class="fa fa-check"></i> Send</button>
            </div>
            <div class="login-panel-body">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-2 control-label">To</label>
                        <div class="col-xs-6 col-sm-5">
                            <a class="btn btn-gray" href="{{ url('profile/view/') ~ profile.ik }}">{{ profile.user_name }}</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-2 control-label">Subject</label>
                        <div class="col-xs-6 col-sm-5">
                            <input type="text" name="subject" class="form-control" id="subject" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-2 control-label">Message</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="10" name="message" required></textarea>
                        </div>
                    </div>
            </div>
            <div class="login-panel-footer text-right">
                <button type="submit" class="btn btn-green"><i class="fa fa-check"></i> Send</button>
            </div>
        </form>
    </div>
</div>