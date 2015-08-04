<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="lG-LmHDTKc0bi93c1EtfeDFk9Ee20h3BWHDg5x3_vNI" />
    <meta name="p:domain_verify" content="213cc78d10ce6a40afaef000ecd7ad46"/>
    <meta name="description" content="{{ metaDescription }}" />
    <title>{{ title }}</title>

    <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />
    <link rel="icon" href="/favicon.ico" type="image/x-icon" />
    {% if canonical_url is defined %}
        <link rel="canonical" href="{{ canonical_url }}" />
    {% endif %}

    <!-- Bootstrap -->
    <link href="{{ static_url('css/styles.min.css?') }}{{ _version }}" rel="stylesheet">

    <!-- Google Web Fonts -->
    <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,700,600' rel='stylesheet' type='text/css'>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <!-- UpcyclePost Icons -->
    <link href="{{ static_url('css/upcyclepost/css/upcyclepost.css') }}" rel="stylesheet">

    <!-- mmenu -->
    <link href="{{ static_url('css/libraries/mobile-slide-menu/jquery.mmenu.positioning.css') }}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    {% if og_img is defined %}
        <meta property="og:image" content="{{ og_img }}" />
    {% endif %}
    {% if og_img_size is defined %}
        <meta property="og:image:width" content="{{ og_img_size[0] }}" />
        <meta property="og:image:height" content="{{ og_img_size[1] }}" />
    {% endif %}
    {% if og_description is defined %}
        <meta property="og:description" content="{{ og_description }}" />
    {% endif %}
    {% if canonical_url is defined %}
        <meta property="og:url" content="{{ canonical_url }}" />
    {% endif %}
    <meta property="og:title" content="{{ title }}" />
    <!-- Start of upcyclepost Zendesk Widget script -->
    <script>
    /*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("//assets.zendesk.com/embeddable_framework/main.js","upcyclepost.zendesk.com");/*]]>*/
    </script>
    <!-- End of upcyclepost Zendesk Widget script -->

    <?php $this->assets->outputCss() ?>
</head>
<body>
<div id="page">
    <!-- Header -->
    <header class="mm-fixed-top">
        <div class="header-container clearfix">
            <a id="mobile-menu" class="mobile-menu fa fa-bars visible-lg visible-md"></a>

            <div class="col-xs-6 col-md-5 search-container">
                <form class="search-form form-inline" method="post" action="{{ url('gallery') }}">
                    <input id="universal-search" type="search" name="term" class="form-control search" placeholder="Find Inspiration">
                    <button type="submit" class="search-icon"><img src="{{ static_url('img/icons/search-icon.png') }}"></button>
                </form>
            </div>

            <div class="logo"><a href="{{ url('') }}"><img class="hidden-xs" src="{{ static_url('img/logo.jpg') }}" /><img class="visible-xs" src="{{ static_url('img/micro-logo.png') }}" /></a></div>
            <div class="buttons hidden-xs hidden-sm">
                {{ partial('partial/menu/login') }}
            </div>
            <a class="slide-menu fa fa-bars hidden-lg hidden-md" href="#mobile-slide-menu"></a>
        </div>
    </header>

    {{ partial('partial/menu/header') }}
