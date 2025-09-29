<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/mine.css">
    <link rel="" href="/css/master.css">

    
  </head>
  <body>
    
    <div class="row m-0 p-0" style="justify-content: center; align-items: center; height: 100vh;">
      <div class="col-7" style="background: linear-gradient(rgba(25, 11, 2, 0.96), rgba(25, 11, 2, 0.96)), url('images/bg2.jpg'); background-size: cover; background-position: center; height: 100%;">
        <div class="row" style="justify-content: center; align-items: center; height: 100%;">
          <div class="col-6 d-flex flex-column align-items-center">
            <img class="my-4" style="width: 250px;" src="images/emblem.png" alt="">
            <p class="fs-5 my-0" style="color: white;"><b>NATIONAL REGISTRATION BUREAU</b></p>
            <p class="display-3 my-2" style="color: #F7CE5F;"><b>IDENTITY CARD</b></p>
            <p class="fs-5 my-0" style="color: white;"><b>APPLICATION MANAGEMENT SYSTEM</b></p>
          </div>
        </div>
      </div>


      <div class="col-5" style="height: 100%;">
        <div class="row" style="justify-content: center; align-items: center;height: 100%;">
          <div class="col-8" style="">
            <div class="py-2 my-2">
                <?php
                  session_start();
                  if (isset($_SESSION['status'])) {
                    echo "<div class='alert alert-success text-center' id='success-alert'>".$_SESSION['status']."</div>";
                    unset($_SESSION['status']);
                  }
                ?>
                <h3 class="text-center fw-bolder fs-1" style="color: #190B02 ;">LOG IN</h3>
                <hr>
            </div>
            <form name="tilowe" method="post" action="controllers/auth.php">
              <div class="col mb-4">
                  <label for="username" class="fw-bold fs-5">Email Address</label>
                  <input type="email" class="form-control my-2 fs-5 p-2" name="email" id="email" placeholder="email" required>
              </div>

              <div class="col mb-4">
                  <label for="Password" class="fw-bold fs-5">Password</label>
                  <input type="password" class="form-control my-2 fs-5 p-2" name="password" id="Password" placeholder="Password" required>
              </div>

              <div class="col mb-5">
                  <!-- <button type="submit" class="btn btn-primary">Login</button> -->
                  <button type="submit" name="btn_login" class="btn px-5 fs-5" style="background-color: #190B02; color: #F7CE5F;"><b>Log In</b></button>
              </div>

              <div class="col d-flex justify-content-between">
                <p><b>Forgot Password? </b><a href="reset.php" style=""><b>Reset Here</b></a></p>
                <a href="registration.php" style=""><b>Create User Account</b></a> 
              </div>
          </form>
            
          </div>
        </div>
      </div>

    </div>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
