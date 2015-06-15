<script type="text/javascript">
    var disqus_config = function() {
        this.page.remote_auth_s3 = "{{ disqus_message }} {{ disqus_hmac }} {{ disqus_timestamp }}";
        this.page.api_key = "<?=$this->config->disqus->public_key?>";

        this.sso = {
            name:   "UpcyclePost",
            button: "http://www.upcyclepost.com/img/mobile-logo.png",
            icon:   "http://www.upcyclepost.com/favicon.png",
            url:    "https://www.upcyclepost.com/profile/login/?redirect=disqus",
            logout: "https://www.upcyclepost.com/profile/logout/",
            width:  "575",
            height: "615"
        };
    }
</script>

<div id="disqus_thread"></div>
<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
    var disqus_shortname = 'upcyclepost'; // required: replace example with your forum shortname

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>