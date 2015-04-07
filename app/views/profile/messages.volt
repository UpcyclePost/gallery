{{ content() }}

<div class="account-settings-container">
    <div class="login-panel">
        <div class="login-panel-header">
            <h1>Message Center</h1>
        </div>
        <div class="login-panel-header messages">
            <h4>Inbox</h4>
            Welcome to the Message Center, your inbox is below.
            If you have any messages you can reply by clicking on them.
            To send a message you must first be on a member's Profile page where you will see the Send Message button.
        </div>
        <form class="form-horizontal">
        <div class="login-panel-body">
            <div class="form-group">
                <label class="col-xs-1 col-sm-1 control-label hidden-xs"></label>
                <label class="col-xs-6 col-sm-2 control-label">From</label>
                <label class="col-xs-5 col-sm-2 control-label">Date</label>
                <label class="col-xs-11 col-sm-3 control-label">Subject</label>
            </div>
            {% for message in messages %}
                <div class="form-group">
                    <div class="col-xs-1 col-sm-1 hidden-xs"><i class="fa fa-envelope"{% if !message.read %} style="color: orange"{% endif %}></i></div>
                    <div class="col-xs-6 col-sm-2"><a href="{{ url('profile/view/') ~ message.User.ik }}">{{ message.User.user_name }}</a></div>
                    <div class="col-xs-5 col-sm-2"><?=date('M/j g:i a', strtotime($message->sent))?></div>
                    <div class="col-xs-11 col-sm-7">
                        <a href="{{ url('profile/message/' ~ message.User.name ~ '-' ~ message.ik) }}">{{ message.subject|trim|truncate }}</a>
                    </div>
                </div>
            {% else %}
                <div class="form-group">
                    You do not appear to have any messages.
                </div>
            {% endfor %}
        </div>
        </form>
    </div>
</div>