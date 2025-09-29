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
          <div class="col-10" style="">
            <div class="py-2 my-4">
                <?php
                  session_start();
                  if (isset($_SESSION['status'])) {
                    echo "<div class='alert alert-success text-center' id='success-alert'>".$_SESSION['status']."</div>";
                    unset($_SESSION['status']);
                  }
                ?>
                <h3 class="text-center fw-bolder fs-1" style="color: #190B02 ;">CREATE USER ACCOUNT</h3>
                <hr>
            </div>
            <form name="tilowe" method="post" action="controllers/user_reg.php">
                <div class="row justify-content-center align-items-center">
                    <div class="col mb-4">
                        <label for="username" class="fw-bold fs-5">First Name</label>
                        <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname" placeholder="First Name" required>
                    </div>
                    <div class="col mb-4">
                        <label for="username" class="fw-bold fs-5">Last Name</label>
                        <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname" placeholder="Last Name" required>
                    </div>
                </div>
                <div class="row justify-content-center align-items-center">
                    <div class="col mb-4">
                        <label for="username" class="fw-bold fs-5">Email Address</label>
                        <input type="email" class="form-control my-2 fs-5 p-2" name="email" id="email" placeholder="Email Address" required>
                    </div>
                    <div class="col mb-4">
                        <label for="username" class="fw-bold fs-5">Gender</label>
                        <input type="gender" class="form-control my-2 fs-5 p-2" name="gender" id="gender" placeholder="Gender" required>
                    </div>
                </div>
                <div class="row justify-content-center align-items-center" style="">
                  <div class="col mb-4">
                      <label for="Password" class="fw-bold fs-5">Password</label>
                      <input type="text" class="form-control my-2 fs-5 p-2" name="password" id="Password" placeholder="Password" required>
                  </div>
                  <div class="col mb-4">
                      <label for="Password" class="fw-bold fs-5">Confirm Password</label>
                      <input type="text" class="form-control my-2 fs-5 p-2" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                  </div>
                </div>
                <div class="col mb-5 d-flex justify-content-center">
                  <button type="submit" name="btn_submit" class="btn px-5 fs-5 text-center" style="width: 50%; color:#F7CE5F; background-color: #190B02;">Submit</button>
                </div>
                <div class="col">
                  <p><b>Already have an account? </b><a href="index.php" style=""><b>&nbsp;Log In</b></a></p>
                </div>
            </form>
          </div>
        </div>
      </div>

    </div>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
