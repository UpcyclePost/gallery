{{ content() }}

<div class="account-settings-container">
    <div class="login-panel">
        <div class="login-panel-header">
            <h1>Message Center</h1>
        </div>
        <div class="login-panel-header messages">
            <h4>{{ message.subject }}</h4>
            Sent
            <?php
            echo (new Carbon\Carbon($message->sent))->diffForHumans();
            ?>
            by <a href="{{ url('profile/view/') ~ message.User.ik }}">{{ message.User.user_name }}</a>
        </div>
        <form class="form-horizontal">
        <div class="login-panel-body">
            <div class="form-group">
                <?php
                    if ($message->reply_to_ik)
                    {
                        echo nl2br(str_replace("\r\n-----\r\n", "<hr>", $message->message));
                    }
                    else
                    {
                        echo nl2br($message->message);
                    }
                ?>
            </div>
        </div>
        </form>
    </div>

<?php
    $subject = (substr($message->subject, 0, 3) == 'Re:') ? $message->subject : 'Re: ' . $message->subject;
?>
    <div class="login-panel">
        <div class="login-panel-header">
            <h1>Reply to <a href="{{ url('profile/view/') ~ message.User.ik }}">{{ message.User.user_name }}</a></h1>
        </div>
        <form class="form-horizontal" method="post" action="{{ url('profile/messages/send/') ~ message.User.ik }}">
        <div class="login-panel-footer text-right">
            <button type="submit" class="btn btn-green"><i class="fa fa-check"></i> Send</button>
        </div>
        <input type="hidden" name="reply-to" value="{{ message.ik }}" />
        <div class="login-panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 control-label">Subject</label>
                <div class="col-xs-6 col-sm-5">
                    <input type="text" name="subject" class="form-control" value="{{ subject }}" id="subject" required>
                </div>
            </div>
            <div class="form-group">
                <label for="message" class="col-sm-2 control-label">Message</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="10" name="message" required>


<?php
    $sent = new Carbon\Carbon($message->sent);
    $sentFormatted = $sent->toDayDateTimeString();
?>
-----
{{ message.User.user_name }} said on {{ sentFormatted }}

{{ message.message }}</textarea>
                </div>
            </div>
        </div>
        <div class="login-panel-footer text-right">
            <button type="submit" class="btn btn-green"><i class="fa fa-check"></i> Send</button>
        </div>
        </form>
    </div>
</div>