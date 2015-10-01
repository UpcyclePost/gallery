<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-2">
            <div class="login-panel">
                <div class="login-panel-header">
                    <h1>Contact Us</h1>
                </div>
                <div class="login-panel-subheader">
                    Fill out the form below to send us a message.
                </div>
                <div class="upload-panel-body">
                    {{ content() }}
                    <form role="form" method="post">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="First & Last Name Please" name="name">
                        </div>
                        <div class="form-group">
                            <label for="company">Company</label>
                            <input type="text" class="form-control" id="company" placeholder="Optional" name="company">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Email Address" name="email">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" placeholder="Optional" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="phone">Message</label>
                            <textarea class="form-control" rows="3" name="message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-blue btn-collapse"><i class="fa fa-check"></i> Submit</button>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-4 contact-info">
            <div class="row">
                <div class="col-sm-6 col-md-12">
                    <h4 class="green">Contact Info</h4>
                    <p>
                        <strong>Upmod, Inc.</strong>
                    </p>
                    <p>
                        <strong>Location:</strong> Issaquah, WA
                        <br/>
                        <strong>Email:</strong> info@upmod.com
                    </p>
                </div>
                <div class="col-sm-6 col-md-12">
                    <h4 class="green">Connect With Us</h4>
                    <p style="font-size:20px;">
                        <a target="blank" href="https://www.facebook.com/upmodinc"><i class="fa fa-facebook-square"></i></a>
                        <a target="blank" href="https://www.twitter.com/upmodinc"><i class="fa fa-twitter-square"></i></a>
                        <a target="blank" href="https://www.pinterest.com/upmodinc"><i class="fa fa-pinterest-square"></i></a>
                        <a target="blank" href="https://plus.google.com/+upmodinc"><i class="fa fa-google-plus-square"></i></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>