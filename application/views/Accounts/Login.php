<style>
  .cursor-pointer {
    cursor: pointer;
  }

  .sj-card {
    width: 400px;
    margin-top: 50px;

    @media screen and (max-width: 768px) {
      width: 98%;
    }
  }

  .pull-center {
    display: table;
    margin-left: auto;
    margin-right: auto;
  }

  @media only screen and (max-width: 767px) {
    .w100percent-xs {
      width: 100% !important;
    }
  }

  .background {
    background-image: url('<?php echo base_url("/assets/login_page.jpg") ?>');
    height: 100vh;
    width: 100%;
    background-position: center;
  }
</style>
<div class="content container margintopcontainer">
  <div class="row">
    <div class="col-12">
      <div class="card pull-center bg-light sj-card">
        <div class="card-header">
          <h3 class="text-center">Login</h3>
        </div>
        <div class="card-body">
          <?php if (!empty($status)) {
            echo '<p class="card-text text-danger">Invalid Credential</p>';
          } ?>
          <form method="post" action="<?php echo base_url('/accounts/login'); ?>" id="login-form">
            <div id="div_id_username" class="form-group"><label for="id_username"
                class="col-form-label  requiredField">
                Username<span class="asteriskField">*</span></label>
              <div class="" data-step="1" data-intro="Enter your username here."><input type="text" name="username" autofocus="" maxlength="254"
                  class="form-control textinput textInput form-control" required="" id="id_username">
              </div>
            </div>
            <div id="div_id_password" class="form-group mb-3"><label for="id_password"
                class="col-form-label  requiredField">
                Password<span class="asteriskField">*</span></label>
              <div class="" data-step="2" data-intro="Enter your password here."><input type="password" name="password"
                  class="form-control textinput textInput form-control" required="" id="id_password">
              </div>
            </div>
            <div class="mb-3 form-check" data-step="3" data-intro="To see your password click here.">
              <input type="checkbox" onclick="myFunction()" class="form-check-input" id="exampleCheck1">
              <label class="form-check-label" for="exampleCheck1">Show Password</label>
            </div>
            <div class="text-center mt-4"><button type="submit"
                class="btn btn-success w100percent-xs" data-step="4" data-intro="Then click Login button to go to the dashboard.">Login</button></div>
            <div class="text-center"><a href="<?php echo base_url('/accounts/forgetpassword') ?>"
                class="btn btn-link" data-step="5" data-intro="If you have forgotten your password click here.">Forgot Password?</a></div>
          </form>
          <p></p>
        </div>
        <!-- <div class="card-footer text-muted">
          <div class="text-center"><strong onclick="startTour()" class="cursor-pointer">💬 Need help?</strong></div>
        </div> -->
      </div>
    </div>
  </div>
</div>
<script>
  function myFunction() {
    var x = document.getElementById("id_password");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }

  function startTour() {
    introJs().start();
  }
</script>