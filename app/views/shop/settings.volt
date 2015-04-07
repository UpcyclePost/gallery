{{ content() }}

<div class="account-settings-container">
    <div class="login-panel">
        <div class="login-panel-header">
            <h1>Shop Settings</h1>
        </div>
        <form class="form-horizontal" role="form" method="post">
        <div class="login-panel-body">
                 <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Name</label>
                    <div class="col-xs-6 col-sm-5">
                        <font class="btn btn-gray">{{ shop.name }}</font>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">First Name</label>
                    <div class="col-xs-6 col-sm-5">
                        <input type="text" name="firstName" class="form-control" id="first-name" value="{{ shop.first_name }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Last Name</label>
                    <div class="col-xs-6 col-sm-5">
                        <input type="text" name="lastName" class="form-control" id="last-name" value="{{ shop.last_name }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Phone Number</label>
                    <div class="col-xs-6 col-sm-5">
                        <input type="text" name="phoneNumber" class="form-control" id="phone-number" value="{{ shop.phone_number }}" required minlength="10" maxlength="13">
                    </div>
                </div>
                <div class="form-group">
                    <label for="last-4" class="col-sm-2 control-label">Last 4 of SSN</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="last-4" name="last4" value="{{ shop.last4 }}" required maxlength="4" minlength="4">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Country</label>
                    <div class="col-xs-6 col-sm-5">
                        <font class="btn btn-gray">United States</font>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Address</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="address" name="address" value="{{ shop.address }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">City</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="city" name="city" value="{{ shop.city }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">State</label>
                    <div class="col-sm-10">
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
                        <select name="st" required class="form-control col-sm-6">
                            {% for st, state in states %}
                                <option{%if st == shop.st %} selected{% endif %} value="{{ st }}">{{ state }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Zip</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="zip" name="zip" value="{{ shop.zip }}" required minlength="5" maxlength="5">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Ship To</label>
                    <div class="col-xs-6 col-sm-5">
                        <font class="btn btn-gray">{{ shop.ships_to }}</font>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Language</label>
                    <div class="col-xs-6 col-sm-5">
                        <font class="btn btn-gray">{{ shop.preferred_language }}</font>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 control-label">Currency</label>
                    <div class="col-xs-6 col-sm-5">
                        <font class="btn btn-gray">{{ shop.preferred_currency }}</font>
                    </div>
                </div>
        </div>
        <div class="login-panel-footer text-right">
            <button type="submit" class="btn btn-green"><i class="fa fa-check"></i> Save Settings</button>
        </div>
        </form>
    </div>
</div>