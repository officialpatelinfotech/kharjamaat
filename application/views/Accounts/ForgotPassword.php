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
                    <h3 class="text-center">Forgot Password</h3>
                </div>
                <div class="card-body">
                    <p class="card-text"></p>
                    <form method="post" action="<?php echo base_url('/accounts/submitForgotPassword');?>">
                        <div id="div_id_username" class="form-group"><label for="id_username"
                                class="col-form-label  requiredField">
                                Username<span class="asteriskField">*</span></label>
                            <div class=""><input type="text" name="username" autofocus="" maxlength="254"
                                    class="form-control textinput textInput form-control" required="" id="id_username">
                            </div>
                        </div>
                        <div class="text-center mt-4"><button type="submit"
                                class="btn btn-success w100percent-xs">Submit</button></div>
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