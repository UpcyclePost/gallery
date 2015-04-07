<?php
    $states = [
        'AK'=>'Alaska',
        'AL'=>'Alabama',
        'AR'=>'Arkansas',
        'AZ'=>'Arizona',
        'CA'=>'California',
        'CO'=>'Colorado',
        'CT'=>'Connecticut',
        'DC'=>'District of Columbia',
        'DE'=>'Delaware',
        'FL'=>'Florida',
        'GA'=>'Georgia',
        'HI'=>'Hawaii',
        'IA'=>'Iowa',
        'ID'=>'Idaho',
        'IL'=>'Illinois',
        'IN'=>'Indiana',
        'KS'=>'Kansas',
        'KY'=>'Kentucky',
        'LA'=>'Louisiana',
        'MA'=>'Massachusetts',
        'MD'=>'Maryland',
        'ME'=>'Maine',
        'MI'=>'Michigan',
        'MN'=>'Minnesota',
        'MO'=>'Missouri',
        'MS'=>'Mississippi',
        'MT'=>'Montana',
        'NC'=>'North Carolina',
        'ND'=>'North Dakota',
        'NE'=>'Nebraska',
        'NH'=>'New Hampshire',
        'NJ'=>'New Jersey',
        'NM'=>'New Mexico',
        'NV'=>'Nevada',
        'NY'=>'New York',
        'OH'=>'Ohio',
        'OK'=>'Oklahoma',
        'OR'=>'Oregon',
        'PA'=>'Pennsylvania',
        'RI'=>'Rhode Island',
        'SC'=>'South Carolina',
        'SD'=>'South Dakota',
        'TN'=>'Tennessee',
        'TX'=>'Texas',
        'UT'=>'Utah',
        'VA'=>'Virginia',
        'VT'=>'Vermont',
        'WA'=>'Washington',
        'WI'=>'Wisconsin',
        'WV'=>'West Virginia',
        'WY'=>'Wyoming',
    ];
?>
<div class="upload-container">
    <form action="{{ url('market/buy/' ~ post.ik ~ '/purchase') }}" method="POST" id="payment-form" class="form-horizontal">
        <input type="hidden" name="token" value="{{ itemHash }}">
        <div class="upload-panel">
            <div class="upload-panel-header text-center">
                <h1>Buy {{ post.title }}</h1>
            </div>
            <div class="upload-panel-subheader text-center">
                Fill in your credit card details below to purchase <a href="<?=$post->url()?>">{{ post.title }}</a> from <a href="{{ url('profile/view/') ~ post.User.ik }}">{{ post.User.user_name }}</a>.
            </div>
            <div class="upload-panel-body">
                {{ content() }}
                <div class="row">
                    <div class="col-sm-5">
                        <img src="{{ thumbnail }}" class="img-responsive">
                        {{ post.description }}
                        <br /><br />
                        <div class="btn btn-green">Item Price ${{ post.Market.price|pretty }}</div>
                        <div class="btn btn-gray">Shipping ${{ post.Market.shipping_price|pretty }}</div>
                    </div>
                    <div class="col-sm-7">
                        <span class="payment-errors"></span>
                        <form role="form">
                            <div class="form-group">
                                <label for="number">Your Name</label>
                                <input type="text" name="name" value="{{ user.name }}" size="20" class="form-control name" required>
                            </div>

                            <div class="form-group">
                                <label for="number">Shipping Address</label>
                                <input type="text" name="shippingAddress" size="20" value="{{ user.Shipping.address }}" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="number">Shipping City, State, Zip</label><br />
                                <input type="text" name="shippingCity" value="{{ user.Shipping.city }}" class="form-control" style="display: inline-block; width: auto;" required>
                                <select name="shippingState" required class="form-control" style="display: inline-block; width: auto;" required>
                                    {% for st, state in states %}
                                        <option{%if st == user.Shipping.st %} selected{% endif %} value="{{ st }}">{{ state }}</option>
                                    {% endfor %}
                                </select>
                                <input type="text" name="shippingZip" value="{{ user.Shipping.zip }}" class="form-control" style="display: inline-block; width: auto;" minlength="5" maxlength="5" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Credit card number</label>
                                <div>
                                    <input type="text" name="creditCardNumber" class="form-control card-number" id="credit-card-number" value="" required minlength="16" maxlength="16" placeholder="Credit card number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">CVC</label>
                                <div>
                                    <input type="text" class="form-control card-cvc" name="cvc" id="cvc" size="4" data-stripe="cvc" placeholder="CVC" style="width: 75px;" minlength="3" maxlength="4" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="expiration-month" class="control-label">Expiration</label>
                                <div>
                                <select data-stripe="exp-month" class="form-control card-expiry-month" style="width: auto; display: inline-block" required name="expiryMonth">
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
                                <select data-stripe="exp-year" class="form-control card-expiry-year" style="width: auto; display: inline-block" required name="expiryYear">
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

                            <div class="form-group">
                                <label for="number">Billing Address</label>
                                <input type="text" name="billing-address" size="20" value="{{ user.Shipping.address }}" class="form-control billing-address" required>
                            </div>

                            <div class="form-group">
                                <label for="number">Billing City, State, Zip</label><br />
                                <input type="text" name="billingCity" value="{{ user.Shipping.city }}" class="form-control billing-city" style="display: inline-block; width: auto;" required>
                                <select name="billingState" required class="form-control billing-st" style="display: inline-block; width: auto;" required>
                                    {% for st, state in states %}
                                        <option{%if st == user.Shipping.st %} selected{% endif %} value="{{ st }}">{{ state }}</option>
                                    {% endfor %}
                                </select>
                                <input type="text" name="billingZip" value="{{ user.Shipping.zip }}" class="form-control billing-zip" style="display: inline-block; width: auto;" required minlength="5" maxlength="5">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="login-panel-footer text-right">
                <button type="submit" class="btn btn-blue"><i class="fa fa-check"></i> Buy Now and Submit Payment for ${{ (post.Market.price + post.Market.shipping_price)|pretty }}</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
  var publishableKey = '{{ config.stripe.public_key }}';
</script>