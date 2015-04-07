<div class="login-container">
    <form method="post" id="open-shop-form">
    <div class="login-panel">
        <div class="login-panel-header">
            <h1>Open a Shop</h1>
        </div>
        <div class="upload-panel-body">
            {{ content() }}
            <form role="form">
                <div class="form-group">
                    <label for="email">Name your shop</label>
                    <input type="text" class="form-control" id="shop-name" minlength="3" placeholder="Enter a shop name" name="shopName" required tabindex="1">
                </div>
                <div class="form-group">
                    <label for="email">Shop address</label><br />
                    <font class="btn btn-gray">http://www.upcyclepost.com/shops/</font> <input type="text" class="form-control" style="width: 150px; display: inline-block" id="shop-url" minlength="3" placeholder="your-shop-address" name="shopUrl" required tabindex="1" maxlength="30" minlength="5">
                </div>
                <button type="submit" class="btn btn-md btn-blue btn-collapse login-register" tabindex="5"><i class="fa fa-sign-in"></i> Start your Shop</button>
            </form>

        </div>
    </div>
    </form>
</div>