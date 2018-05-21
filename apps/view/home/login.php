<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EraPOS | Login</title>

    <!-- Bootstrap -->
    <link href="<?php print($helper->path("assets/vendors/bootstrap/dist/css/bootstrap.min.css"));?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php print($helper->path("assets/vendors/font-awesome/css/font-awesome.min.css"));?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php print($helper->path("assets/vendors/nprogress/nprogress.css"));?>" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?php print($helper->path("assets/vendors/animate.css/animate.min.css"));?>" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php print($helper->path("assets/build/css/custom.min.css"));?>" rel="stylesheet">
</head>

<body class="login">
<div>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <div class="row" align="center">
                <img src="<?php print(base_url('public/images/agpg-logo.jpg')); ?>" width="200" height="150">
            </div>
            <section class="login_content">
                <form id="login_form" action="<?php echo site_url("home/login"); ?>" method="post" autocomplete="off">
                    <h1>SELAMAT DATANG</h1>
                    <div>
                        <input type="email" class="form-control" name="u_email" placeholder="Email Adress" required/>
                        <input type="hidden" name="user_email" value="">
                    </div>
                    <div>
                        <input type="password" class="form-control" name="u_paswd" placeholder="Password" required/>
                        <input type="hidden" name="user_paswd" value="">
                    </div>
                    <div>
                        <button class="btn btn-default btn-block" type="submit">Login</button>
                    </div>
                    <div class="clearfix"></div>
                    <?php if (isset($error)) { ?>
                        <p class="alert alert-warning" align="center">
                            <?php print($error); ?>
                        </p>
                    <?php } ?>
                    <div class="separator">
                        <div>
                            <p>© 2018 Erasystem Inc - All Rights Reserved</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="<?php print($helper->path("assets/vendors/jquery/dist/jquery.min.js"));?>"></script>
<!-- Bootstrap -->
<script src="<?php print($helper->path("assets/vendors/bootstrap/dist/js/bootstrap.min.js"));?>"></script>
<script>
    $(document).ready( function () {
        $( "#login_form" ).submit(function( event ) {
            $('[name="user_email"]').val($('[name="u_email"]').val());
            $('[name="u_mail"]').val('');
            $('[name="user_paswd"]').val($('[name="u_paswd"]').val());
            $('[name="u_paswd"]').val('');
        });
    });
</script>
</body>
</html>
