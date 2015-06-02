{{ partial('partial/header') }}
    <!-- Slideshow -->
    <section class="slideshow-wrapper">
        <div id="bg-slide" class="slider">
            <div class="container">
                <div class="intro">
                    <h1>Welcome to UpcyclePost
                        your place to discover the world's
                        greatest upcycled products</h1>
                    <a href="{{ url('shops') }}" class="btn btn-green btn-lg btn-header"><i class="fa fa-camera"></i> Shop Gallery</a>
                    {% if !isLoggedIn %}
                        <a href="{{ url('profile/login') }}" class="btn btn-blue btn-lg btn-header"><i class="fa fa-user"></i> Create Account</a>
                    {% endif %}
                </div>
            </div>
        </div>
    </section>

    <div class="content-wrapper">
        {% set offsetTop = 440 %}
        <div class="content-container">
            {% set isodiv = "iso" %}
            {{ partial('partial/gallery/layout') }}
        </div>
    </div>

{% if !isLoggedIn and showSubscribe %}
    <div id="myModal" class="modal fade subscribe">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="newsletter-subscribe-form">
                        <span class="email-icon hidden-xs">
                          <i class="fa fa-envelope-o"></i>
                        </span>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Sign up to become an UpcyclePost Insider &#9786;</h4>
                    <p>Join our community of upcyclers and receive monthly news, promotions, featured items &amp; special offers.</p>

                    <div class="input-group">
                            <input type="email" class="form-control" placeholder="Enter Your Email Address" id="mc-email" required />
                              <span class="input-group-btn">
                                <button class="btn btn-green" type="submit"><i class="fa fa-paper-plane"></i> Subscribe</button>
                              </span>
                    </div>
                    <!-- /input-group -->
                    </form>
                </div>
                <div class="modal-footer">
                    <p>UpcyclePost will never misuse your email, it will never be rented or sold, plus you can unsubscribe at any time.</p>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
{% endif %}

{{ partial('partial/footer') }}