<div class="login-container">
      <form method="post" id="login-form">
      <div class="login-panel">
          <div class="login-panel-header">
              <h1>Please complete a brief survey</h1>
          </div>
          <div class="upload-panel-body">
              {{ content() }}
              <form role="form">
                  <div class="form-group">
                      <label for="email">What are you trying to accomplish selling on UpcyclePost?</label><br />
                      <input type="radio" checked value="Reach additional buyers" name="survey-q-1"> Reach additional buyers<br />
                      <input type="radio" value="I already sell full time" name="survey-q-1"> I already sell full time<br />
                      <input type="radio" value="Quit my day job and sell full time" name="survey-q-1"> Quit my day job and sell full time<br />
                      <input type="radio" value="Sell in my spare time" name="survey-q-1"> Sell in my spare time<br />
                      <input type="radio" value="Other" name="survey-q-1"> Other
                  </div>
                  <button type="submit" class="btn btn-md btn-blue btn-collapse login-register" tabindex="5"><i class="fa fa-sign-in"></i> Thank you</button>
              </form>

          </div>
      </div>
      </form>
  </div>