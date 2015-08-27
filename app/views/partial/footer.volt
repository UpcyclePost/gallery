<!-- Footer -->
<footer class="footer hidden-xs">
    <div class="content-container">
        <nav class="slug clearfix">
            <li class="f-logo"><a href="{{ url('') }}"><img src="{{ static_url('img/footer-logo.png') }}"/></a></li>
            <li class="copyright">© <?=date('Y')?> UpcyclePost. All rights reserved.</li>
            <li class="social">
                <ul class="clearfix">
                    <li><a target="_blank" href="http://www.facebook.com/upcyclepost"><i class="fa fa-facebook-square"></i></a></li>
                    <li><a target="_blank" href="http://www.twitter.com/upcyclepost"><i class="fa fa-twitter"></i></a></li>
                    <li><a target="_blank" href="http://www.pinterest.com/upcyclepost"><i class="fa fa-pinterest"></i></a></li>
                    <li><a target="_blank" href="http://www.linkedin.com/company/upcyclepost-com"><i class="fa fa-linkedin-square"></i></a></li>
                </ul>
            </li>
            <li><a href="{{ url('policy') }}">Policies</a></li>
            <li><a href="{{ url('contact') }}">Contact Us</a></li>
            <li><a href="{{ url('blog') }}">Blog</a></li>
            <li><a href="{{ url('faq') }}">FAQ</a></li>
            <li><a href="{{ url('about') }}">About Us</a></li>
            <li><a href="{{ url('post/idea') }}">Post Ideas</a></li>
        </nav>
    </div>
</footer>

{{ partial('partial/menu/mobile') }}

</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="{{ static_url('js/libraries/jquery/jquery.min.js') }}"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ static_url('js/libraries/bootstrap/bootstrap.min.js') }}"></script>
<!-- ImagesLoaded -->
<script src="{{ static_url('js/libraries/imagesloaded/imagesloaded.pkgd.js') }}"></script>
<!-- Isotope -->
<script src="{{ static_url('js/libraries/isotope/isotope.pkgd.min.js') }}"></script>
<!-- Horizontal Dropdown Menu -->
<script src="{{ static_url('js/libraries/horizontal-menu/cbpHorizontalMenu.js') }}"></script>
<!-- Sharrre -->
<script src="{{ static_url('js/libraries/share/jquery.share.js') }}"></script>
<!-- Mobile Slide Menu -->
<script src="{{ static_url('js/libraries/mobile-slide-menu/jquery.mmenu.min.js') }}"></script>

<script src="{{ static_url('js/site.js') }}"></script>

<?php
    foreach ($this->assets->collection('js') AS $resource)
    {
        echo \Phalcon\Tag::javascriptInclude(sprintf('%s?%s', $resource->getPath(), $_version));
    }
?>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-51510786-1', 'upcyclepost.com');
    ga('require', 'linkid', 'linkid.js');
    ga('require', 'displayfeatures');
    ga('send', 'pageview');
</script>
</body>
</html>
