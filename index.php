<!DOCTYPE html>
<html lang="en">
<head>
	<title>Bakmie Lana</title>
	<meta charset="UTF-8">
	<meta name="description" content="POS ini adalah Software Kasir dengan fitur lengkap dan support Multi cabang. POS Seniman Koding adalah software POS Kasir multi user dengan fitur yang mempuni. aplikasi kasir, software retail, software minimarket, aplikasi supermarket, aplikasi retail, aplikasi kasir online, aplikasi kasir berbasis website">
	<meta name="keyword" content="POS ini adalah Software Kasir dengan fitur lengkap dan support Multi cabang, aplikasi kasir, software retail, software minimarket, aplikasi supermarket, aplikasi retail, aplikasi kasir online, aplikasi kasir berbasis website">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta property="og:image" content="" />
	<!-- Favicon -->
	<link rel="icon" type="img/png" sizes="32x32" href="">

	<link rel="stylesheet" type="text/css" href="assets-login/vendor/bootstrap/css/bootstrap.min.css">

	<link rel="stylesheet" type="text/css" href="assets-login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="assets-login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">

	<link rel="stylesheet" type="text/css" href="assets-login/css/util.css">
	<link rel="stylesheet" type="text/css" href="assets-login/css/main.css">

</head>
<body>
	<?php  
      if (isset($_COOKIE['emailPos'])) {
        $emailSession = base64_decode($_COOKIE['emailPos']);
      } else {
        $emailSession = "";
      }

      if (isset($_COOKIE['passPos'])) {
        $passSession = base64_decode($_COOKIE['passPos']);
      } else {
        $passSession = "";
      }
  	?>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" action="aksi/login" method="post">
					<span class="login100-form-title p-b-43">
						Login <b>Bakmie Lana</b>
					</span>
					
					
					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="text" name="user_email" value="<?= $emailSession; ?>" placeholder="email">
						<span class="focus-input100"></span>
						<span class="label-input100"></span>
					</div>
					
					
					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="user_password" value="<?= $passSession; ?>" placeholder="password">
						<span class="focus-input100"></span>
						<span class="label-input100"></span>
					</div>
					
					<div style="float: right;">
						<a href="aksi/clear-cookie" style="color: #007bff;">
							<small><b>Clear</b></small>
						</a>
					</div><br>

					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn" name="submit">
							Login
						</button>
					</div>
					
					<div class="text-center p-t-46 p-b-20">
						<span class="txt2">
							<a href="http://astacode.id/">www.astacode.id</a>
						</span>
					</div>
				</form>

				<div class="login100-more" style="background-image: url('assets-login/images/bg-02.jpg');">
				</div>
			</div>
		</div>
	</div>
	
	

	
	
	<script src="assets-login/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="assets-login/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets-login/vendor/bootstrap/js/popper.js"></script>
	<script src="assets-login/js/main.js"></script>

</body>
</html>
