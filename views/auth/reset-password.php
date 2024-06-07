<?php 
  include_once 'session.php'; 
  require_once 'model/user/loginClass.php';

  $login = new Login();

  if (!$login->verifyCode($email, $code)) {
    header('Location: /404');
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Login | FMWare</title>
      <link rel="icon" href="/asset/images/store/logo.png" type="image/png">
      <?php 
        require_once 'config/load_vendors.php'; 
      ?>
  </head>
  <body class="bg-light">
    <!--Content-->
    <div class="container" style="height: 100vh">
      <div
        class="row d-flex align-items-center justify-content-center"
        style="height: 100vh"
      >
        <div
          class="col d-flex flex-column align-items-center justify-content-center"
        >
          <img src="/asset/images/store/logo.png" alt="" style="width: 300px" />
          <h1 class="text-dark">FMWARE</h1>
          <h5 class="text-dark">
            RIGHT TOOLS AT THE RIGHT PRICE.
          </h5>
        </div>
        <div class="col shadow bg-light p-4 rounded">
          <form id="reset-form">
            <div class="row mb-2">
              <h1>Reset Password</h1>
            </div><div class="container">
            <div class="form-floating mb-2">
              <input
                type="password"
                class="form-control"
                id="password"
                aria-describedby="emailHelp"
                name="password"
              />
              <div  class="form-text">Enter your new password here.</div>
              <label for="">New Password</label>
            </div>
            <div class="form-floating mb-2">
              <input
                type="password"
                class="form-control"
                id="confirm"
                aria-describedby="emailHelp"
                name="confirm"
              />
              <div  class="form-text">Confirm your new password here.</div>
              <label for="">Confirm New Password</label>
            </div>
            <div class="row mb-2">
              <div class="col">
                <div class="mb-3 form-check">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    id="show_new"
                  />
                  <label class="form-check-label" for="showPasscheck">Show password</label>
                </div>
              </div>
            </div>
            <div class="row mb-2">
              <input type="hidden" name="email" value="<?php echo $email;?>">
              <button type="submit" class="btn btn-primary mb-2">Reset Password</button>
              <a class="btn btn-danger" href="/login">Back</a>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--Content-->

    <script src="/asset/js/auth/login.js"></script>
  </body>
</html>
