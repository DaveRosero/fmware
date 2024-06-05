<?php 
  include_once 'session.php';
  require_once 'model/user/addressClass.php';

  $address = new Address();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register | FMWare</title>
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
        <div class="col bg-light p-4 rounded">
          <form id="register">
            <div class="row mb-2">
              <h1>Register</h1>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-floating mb-2">
                  <input
                    type="text"
                    class="form-control"
                    id="fname"
                    placeholder=""
                    aria-describedby="nameHelp"
                    name="fname"
                    required
                  />
                  <label for="fname">First Name</label>
                </div>
              </div>
              <div class="col">
                <div class="form-floating mb-2">
                  <input
                    type="text"
                    class="form-control"
                    id="lname"
                    placeholder=""
                    aria-describedby="nameHelp"
                    name="lname"
                    required
                  />
                  <label for="lname">Last Name</label>
                </div>
              </div>
            </div>
            <div class="form-floating mb-2">
              <input
                type="email"
                class="form-control"
                id="email"
                placeholder=""
                aria-describedby="emailHelp"
                name="email"
                required
              />
              <label for="email">Email address</label>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-floating mb-2">
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    placeholder=""
                    aria-describedby="passwordHelp"
                    name="password"
                    required
                  />
                  <label for="password">Password</label>
                </div>
              </div>
              <div class="col">
                <div class="form-floating mb-2">
                  <input
                    type="password"
                    class="form-control"
                    id="confirm"
                    placeholder=""
                    aria-describedby="passwordHelp"
                    name="confirm"
                    required
                  />
                  <label for="password">Confirm Password</label>
                </div>
              </div>
            </div>
            <div class="form-floating mb-2">
              <input
                type="number"
                class="form-control"
                id="phone"
                placeholder=""
                aria-describedby="phoneHelp"
                name="phone"
                required
              />
              <label for="phone">Mobile Number</label>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-floating mb-2">
                  <input
                    type="text"
                    class="form-control"
                    id="house"
                    placeholder=""
                    aria-describedby="houseHelp"
                    name="house"
                    required
                  />
                  <label for="home">House No.</label>
                </div>
              </div>
              <div class="col">
                <div class="form-floating mb-2">
                  <input
                    type="text"
                    class="form-control"
                    id="street"
                    placeholder=""
                    aria-describedby="homeHelp"
                    name="street"
                    required
                  />
                  <label for="home">Street</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-floating mb-2" id="brgy-select">
                  <select class="form-select" name="brgy" id="brgy">
                    <option selected disabled>Select baranggay</option>
                    <?php $address->getBrgys(); ?>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-floating mb-2">
                  <input
                    type="text"
                    class="form-control"
                    id="municipality"
                    placeholder=""
                    aria-describedby="homeHelp"
                    name="municipality"
                    required
                    readonly
                  />
                  <label for="home">Municipality</label>
                </div>
              </div>
            </div>
            <div class="form-floating mb-2">
              <textarea id="description" name="description" class="form-control"></textarea>
              <label for="home">Description</label>
            </div>
            <div class="row mb-2">
              <div class="col">
                <div class="mb-3 form-check">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    id="show_password"
                  />
                  <label class="form-check-label" for="showPasscheck"
                    >Show password</label
                  >
                </div>
              </div>
              <div class="col-auto text-end">
                <p class="text-secondary">Already have an account? <a href="/login">Login</a></p>
              </div>
            </div>
            <div class="row mb-2">
              <input type="hidden" name="action" value="register">
              <button type="submit" class="btn btn-primary mb-2">Register</button>
              <a class="btn btn-danger" href="/">Back</a>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--Content-->

  <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white;">
        Loading...
    </div>
  </div>

    <script src="/vendor/NotifyJS/js/notify.js"></script>
    <script src="/asset/js/auth/register.js"></script>
  </body>
</html>
