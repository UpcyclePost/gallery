<div class="content-container">
    <div class="row row-centered">
        <div class="col-lg-12">
            <h1>Congratulations, you are now a Member!</h1>
        </div>
    </div>
    <br /><br />
    <div class="row row-centered">
    <div class="col-lg-3 col-centered" style="vertical-align:middle;">
        <form method="post" id="login-form">
            <div class="login-panel">
                <div class="login-panel-header">
                    <h1>Continue to Shopping</h1>
                </div>
                <div class="login-panel-body" style="height:200px;">
                    <h4>If you would like to go straight to the Marketplace, go here.</h4>
                </div>
                <div class="login-panel-footer">
                    <div class="text-center">
                        <a class="btn btn-lg btn-green" href="{{ url('') }}"><i class="fa up-shop-1"></i> Shop</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-3 col-centered" style="vertical-align:middle;">
        <form method="post" id="login-form">
            <div class="login-panel">
                <div class="login-panel-header">
                    <h1>Become a Seller</h1>
                </div>
                <div class="upload-panel-body" style="height:300px;">
                    <h4>To become a seller on UpcyclePost, please complete your Enhanced Member Profile and follow the instructions to Open a Shop.</h4>
                </div>
                <div class="login-panel-footer">
                    <div class="text-center">
                        <a class="btn btn-lg btn-blue" href="{{ url('profile/edit') }}?flow=shop"><i class="fa fa-edit"></i> Complete your Profile</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-3 col-centered" style="vertical-align:middle;">
        <form method="post" id="login-form">
            <div class="login-panel">
                <div class="login-panel-header">
                    <h1>Enhance your Profile</h1>
                </div>
                <div class="upload-panel-body" style="height:200px;">
                    <h4>If you would like an Enhanced Member Profile go here to create your unique profile that will then appear in the Profile Gallery.</h4>
                </div>
                <div class="login-panel-footer">
                    <div class="text-center">
                        <a class="btn btn-lg btn-green" href="{{ url('profile/edit') }}?flow=member"><i class="fa fa-edit"></i> Enhance your Profile</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>