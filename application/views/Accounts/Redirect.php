<style>
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
    background-image: url('https://www.its52.com/imgs/1443/bg_Login_Jamea.jpg?v1');
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
          <form method="post" action="<?php echo base_url('/accounts/redirect'); ?>">
            <div id="div_id_username" class="form-group"><label for="id_username"
                class="col-form-label  requiredField">
                Username<span class="asteriskField">*</span></label>
              <div class=""><input type="text" name="username" autofocus="" maxlength="254"
                  class="form-control textinput textInput form-control" required="" id="id_username">
              </div>
            </div>
            <div id="div_id_password" class="form-group mb-3"><label for="id_password"
                class="col-form-label  requiredField">
                Password<span class="asteriskField">*</span></label>
              <div class=""><input type="password" name="password"
                  class="form-control textinput textInput form-control" required="" id="id_password">
              </div>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" onclick="myFunction()" class="form-check-input" id="exampleCheck1">
              <label class="form-check-label" for="exampleCheck1">Show Password</label>
            </div>
            <div class="text-center mt-4"><button type="submit"
                class="btn btn-success w100percent-xs">Login</button></div>
            <div class="text-center"><a href="<?php echo base_url('/accounts/forgetpassword') ?>"
                class="btn btn-link">Forgot Password?</a></div>
          </form>
          <p></p>
        </div>
        <!-- <div class="card-footer text-muted">
                    <div class="text-center">New User?<a href="/accounts/signup/?reset=" class="btn btn-link">Sign
                            Up</a></div>
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
</script>