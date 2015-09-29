<div class="container tweenie">
    <div class="pull-left">
    <h2>Shop</h2>
    <h3>Buy unique products made by our upcycle<br>artisans and keep the movement growing.</h3>

    <a class="btn btn-blue" href="{{ url('/browse/products') }}">Shop our products</a>
    </div>
    <div class="pull-right">
        <img src="{{ url('/upmod/img/shopping.png') }}">
    </div>
</div>

<div class="tweenie-content">
    <div class="container">
        <div class="text-center">
            <h1>As a member your sharing powers the upcycling movement.</h1>
        </div>
        <div class="col-lg-4 col-md-12 text-center">
            <div class="tweenie-content-image">
                <img src="{{ url('upmod/img/stores.png') }}">
            </div>
            <h2>Shops</h2>
            <h3>Visit the Shop Gallery and<br>choose your starting point.</h3>
            <a href="{{ url('/browse/shops') }}">Browse by Shops</a>
        </div>

        <div class="col-lg-4 col-md-12 text-center">
            <div class="tweenie-content-image">
                <img src="{{ url('upmod/img/products.png') }}">
            </div>
            <h2>Products</h2>
            <h3>Visit the Products Gallery<br>look for what inspires you.</h3>
            <a href="{{ url('/browse/products') }}">Browse by Product Gallery</a>
        </div>

        <div class="col-lg-4 col-md-12 text-center">
            <div class="tweenie-content-image">
                <img src="{{ url('upmod/img/categories.png') }}">
            </div>
            <h2>Categories</h2>
            <h3>Shop by Category and find<br>what you need instantly.</h3>
            <a href="{{ url('browse/categories') }}">Browse by Category</a>
        </div>
    </div>
</div>