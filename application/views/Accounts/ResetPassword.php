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
                    <h3 class="text-center">Change Password</h3>
                </div>
                <div class="card-body">
                    <p class="card-text"></p>
                    <form method="post" action="<?php echo base_url('/accounts/submitchangepassword');?>">
                        <div id="div_id_password" class="form-group">
                            <label for="id_password" class="col-form-label requiredField">
                                New Password<span class="asteriskField">*</span></label>
                            <div class="">
                                <input type="password" name="password" class="form-control textinput textInput form-control" required="" id="id_password" minlength="8">
                            </div>
                        </div>
                        <div id="div_id_confirm_password" class="form-group">
                            <label for="id_confirm_password" class="col-form-label requiredField">
                                Confirm Password<span class="asteriskField">*</span></label>
                            <div class="">
                                <input type="password" name="confirm_password" class="form-control textinput textInput form-control" required="" id="id_confirm_password" minlength="8">
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success w100percent-xs" onclick="return validatePassword()">Done</button>
                        </div>
                    </form>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validatePassword() {
        var password = document.getElementById("id_password").value;
        var confirmPassword = document.getElementById("id_confirm_password").value;

        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }
        if (password.length<8) {
            alert("Passwords must be of 8 character");
            return false;
        }

        return true;
    }
</script>
