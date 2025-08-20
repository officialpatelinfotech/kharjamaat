<!-- <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet"> -->

<style>
    .main {
        height: 100vh;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    h1 {
        color: red;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-weight: 900;
        font-size: 40px;
        margin-bottom: 10px;
    }

    p {
        color: #404F5E;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-size: 20px;
        margin: 0;
    }

    i {
        color: #ff0000;
        font-size: 100px;
        line-height: 200px;
    }

    .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
    }
</style>
<div class="main margintopcontainer">
    <div class="card">
        <div
            style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto; display:flex;align-items:center;justify-content:center;">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <h1 class="mt-4">Error</h1>
        <p>OOPS ! Something Wrong Happened;<br /> Please Try Again!</p>
    </div>
</div>
<script>
    setTimeout(function () {
        window.location.href = '<?php echo base_url('amilsaheb/') . $redirect ?>';
    }, 1000);
</script>