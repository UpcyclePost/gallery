{{ content() }}

<div class="account-settings-container">
    <div class="login-panel">
        <div class="login-panel-header">
            <h1>Get Paid</h1>
            Add your payment information using the form below.
        </div>
    </div>

    <form class="form-horizontal" role="form" method="post" id="get-paid-form">
        <div class="login-panel">
            <div class="login-panel-header">
                <h4>Bank Account</h4>
                We will deposit your funds into this account.
            </div>
            <div class="login-panel-body">
                <div class="form-group">
                    <label class="col-xs-14 col-sm-3 control-label">Account Holder Name</label>
                    <div class="col-xs-6 col-sm-5">
                        <input type="text" name="bankAccountLegalName" class="form-control" id="bank-account-legal-name" value="" required placeholder="Legal name of account holder">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-14 col-sm-3 control-label">Bank Account Number</label>
                    <div class="col-xs-6 col-sm-5">
                        <input type="text" name="bankAccountNumber" class="form-control" id="bank-account-number" minlength="7" maxlength="18" value="" required placeholder="Bank account number">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-14 col-sm-3 control-label">Bank Routing Number</label>
                    <div class="col-xs-6 col-sm-5">
                        <input type="text" name="bankRoutingNumber" class="form-control" id="bank-routing-number" minlength="9" maxlength="9" value="" required placeholder="Bank routing number">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-14 col-sm-3 control-label">Confirm Routing Number</label>
                    <div class="col-xs-6 col-sm-5">
                        <input type="text" name="confirmRoutingNumber" class="form-control" id="confirm-routing-number" minlength="9" maxlength="9" value="" required placeholder="Confirm bank routing number">
                    </div>
                </div>
                <div class="form-group">
                    <label for="expiration-month" control-label class="col-xs-14 col-sm-3">Account Type</label>
                    <div class="col-xs-6 col-sm-4">
                    <select class="form-control" name="bankAccountType" id="bank-account-type" style="width: auto; display: inline-block" required>
                        <option value="checking">Checking</option>
                        <option value="savings">Savings</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="login-panel-header">
                <h4>Credit Card</h4>
                If your issue a refund but your total balance is not high enough to cover it, we will charge this card.
            </div>
            <div class="payment-errors"></div>
            <div class="login-panel-body">
                    <div class="form-group">
                        <label class="col-xs-14 col-sm-3 control-label">Credit card number</label>
                        <div class="col-xs-6 col-sm-5">
                            <input type="text" name="creditCardNumber" class="form-control card-number" id="credit-card-number" value="" required minlength="16" maxlength="16" placeholder="Credit card number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-14 col-sm-3 control-label">CVC</label>
                        <div class="col-xs-4 col-sm-2">
                            <input type="text" class="form-control card-cvc" name="cvc" id="cvc" size="4" data-stripe="cvc" placeholder="CVC" style="width: 75px;" minlength="3" maxlength="4">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="expiration-month" control-label class="col-xs-14 col-sm-3">Expiration</label>
                        <div class="col-xs-6 col-sm-4">
                        <select data-stripe="exp-month" class="form-control card-expiry-month" style="width: auto; display: inline-block" required>
                            <option value=""></option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        /
                        <select data-stripe="exp-year" class="form-control card-expiry-year" style="width: auto; display: inline-block" required>
                            <option value=""></option>
                                <?php
                                for ($i = date('Y'); $i < date('Y') + 20; $i++)
                                {
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="login-panel-footer text-right">
                <button type="submit" class="btn btn-green"><i class="fa fa-check"></i> Save Payment Settings</button>
            </div>
        </div>
    </form>
</div>

<div class="account-settings-container sub-pages">
        <form class="form-horizontal" role="form">
        <h1>Fee Schedule</h1>

        </form>
</div>

<script type="text/javascript">
  var publishableKey = '{{ config.stripe.public_key }}';
</script>