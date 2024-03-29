        <footer{% if isIndexPage is not defined %} class="subpages"{% endif %}>
            <div class="container">
                <div class="row">
                    <div class="footer-logo"></div>
                    <ul class="footer-menu">
                        <li><a href="{{ url('about') }}">About Us</a></li>
                        <li class="hidden-sm hidden-xs"><a href="{{ url('faq') }}">FAQ</a></li>
                        <li><a href="{{ url('blog') }}">Blog</a></li>
                        <li><a href="{{ url('contact') }}">Contact</a></li>
                        <li><a href="{{ url('policy') }}">Policies</a></li>
                    </ul>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/upmodinc" target="_blank"><i class="fa fa-facebook-square"></i></a>
                        <a href="https://www.twitter.com/upmodinc" target="_blank"><i class="fa fa-twitter-square"></i></a>
                        <a href="https://www.pinterest.com/upmodinc" target="_blank"><i class="fa fa-pinterest-square"></i></a>
                        <a href="https://plus.google.com/+upmodinc" target="_blank"><i class="fa fa-google-plus-square"></i></a>
                    </div>
                    <div class="copyright">
                        &copy; <?=date('Y')?> Upmod. All Rights Reserved
                    </div>
                </div>
            </div>
        </footer>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

        <script>
            window.jQuery || document.write('<script src="{{ static_url('upmod/js/vendor/jquery-1.11.2.min.js') }}"><\/script>')
        </script>

        <script src="{{ static_url('upmod/js/vendor/bootstrap.min.js') }}"></script>

        <script src="{{ static_url('upmod/js/main.js') }}"></script>

        <script src="{{ static_url('js/libraries/typeahead/bloodhound.min.js') }}"></script>
        <script src="{{ static_url('js/libraries/typeahead/typeahead.jquery.min.js') }}"></script>
        <script src="{{ static_url('js/up.js') }}"></script>

        <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "0919549b-9f77-444b-bd9a-4c8683b78c51", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-51510786-2', 'auto');
	  ga('send', 'pageview');
	</script>

	<script type="text/javascript">
        mixpanel.track('Viewed Page', {'Page Name': '{{ _mixpanel_page }} Page'});
    </script>

    <?php
        foreach ($this->assets->collection('js') AS $resource)
        {
            echo \Phalcon\Tag::javascriptInclude(sprintf('%s?%s', $resource->getPath(), $_version));
        }
    ?>
    </body>
</html>
