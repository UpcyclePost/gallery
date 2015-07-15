{{ partial('partial/header') }}
    <!-- Slideshow -->
    <div class="content-container">
        <div class="content-container">
            <section class="slideshow-wrapper">
                <div id="bg-slide" class="slider">
                    <div class="container" style="min-height: 196px;">
                        <div class="intro">
                            <h1>Discover thousands of upcycled, recycled,<br>reclaimed & reused products & ideas</h1>
                        </div>
                        <div id="cta">
                            <span id="arrow">
                                <span class="caption">Discover!</span>
                            </span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="content-wrapper">
        {% set offsetTop = 440 %}
        <div class="content-container">
            {% set isodiv = "iso" %}
            {{ partial('partial/gallery/layout') }}
            <div class="text-center"><a href="{{ url('gallery') }}" class="btn btn-green btn-lg"><i class="fa fa-chevron-right"></i> Show Me More</a></div>
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