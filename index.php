<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="assets/login/fonts/icomoon/style.css">

  <link rel="stylesheet" href="assets/login/css/owl.carousel.min.css">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="assets/login/css/bootstrap.min.css">

  <!-- Style -->
  <link rel="stylesheet" href="assets/login/css/style.css">

  <title>Login Aplikasi Laundry APP</title>
</head>

<body>


  <div class="d-lg-flex half">
    <div class="bg order-1 order-md-2" style="background-image: url('assets/login/images/bg_1.jpg');"></div>
    <div class="contents order-2 order-md-1">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7">
            <h3>Login to <strong>Laundry APP</strong></h3>
            <form action="ceklogin.php" method="post">
              <div class="form-group first">
                <input type="text" class="form-control" placeholder="Username" name="username">
              </div>
              <div class="form-group last mb-3">
                <input type="password" class="form-control" placeholder="Password" name="password">
              </div>

              <div class="d-flex mb-5 align-items-center">
                <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                  <input type="checkbox" checked="checked" />
                  <div class="control__indicator"></div>
                </label>
                <span class="ml-auto"><a href="#" class="forgot-pass">Forgot Password</a></span>
              </div>

              <input type="submit" value="Log In" class="btn btn-block btn-primary">

            </form>
          </div>
        </div>
      </div>
    </div>


  </div>



  <script src="assets/login/js/jquery-3.3.1.min.js"></script>
  <script src="assets/login/js/popper.min.js"></script>
  <script src="assets/login/js/bootstrap.min.js"></script>
  <script src="assets/login/js/main.js"></script>
</body>

</html>