<style>
    .center {
        display: flex;
        height: 100vh;
        width: 100vw;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
</style>
<div class="center">
    <h4>Your Password has been reset and Sent To Your Register Email <a
            href="mailto:<?php echo $user_email ?>"><?php echo $user_email ?></a> . IF you haven't Received an Email
        Contact at Jamaat office </h4>
    <p>or</p>
    <h4>E-mail at <a href="mailto:info@kharjamaat.in">info@kharjamaat.in</a></h4>
</div>
<script>
    setTimeout(() => {
        window.location.href = '<?php echo base_url('accounts') ?>';
    }, 8000);
</script>