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

    <div class="row m-0 px-0 py-3" style="background-color: white; justify-content: center; align-items: center;">
      <div class="col-6 d-flex justify-content-center align-items-center" style="">
        <img class="me-5" style="width: 100px;" src="images/logo.jpg" alt="">
        <p class="text-center fs-1" style=""><b>NATIONAL REGISTRATION BUREAU</b></p>
      </div>
    </div>

    <div class="row m-0 px-0 py-5" style="background-color: #E0E0E0; justify-content: center; align-items: center;">
      <div class="col-6" style="background-color: white; border-radius: 20px;">
        <div class="row px-5 py-4 justify-content-center align-items-center" style="">
           <div class="col-11" style="">
            <div class="py-2 my-4">
                <?php
                  session_start();
                  if (isset($_SESSION['status'])) {
                    //echo "<div class='alert alert-success text-center' id='success-alert'>".$_SESSION['status']."</div>";
                    unset($_SESSION['status']);
                  }
                ?>
                <h3 class="text-center fw-bolder fs-1" style="color: #190B02 ;">RENEW APPLICATION FORM</h3>
                <hr>
            </div>

            <!-- <form name="tilowe" method="post" action="controllers/renewals.php">
                <div class="row justify-content-center align-items-center">
                    <div class="col mb-4">
                        <label for="username" class="fw-bold fs-5">First Name<i> (Dzina loyamba)</i></label>
                        <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname" placeholder="First Name" required>
                    </div>
                    <div class="col mb-4">
                        <label for="username" class="fw-bold fs-5">Last Name<i> (Dzina la bambo)</i></label>
                        <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname" placeholder="Last Name" required>
                    </div>
                </div>

                <div class="row justify-content-center align-items-center">
                    <div class="col mb-4">
                        <label for="username" class="fw-bold fs-5">Village<i> (Mudzi)</i></label>
                        <input type="email" class="form-control my-2 fs-5 p-2" name="email" id="email" placeholder="Village" required>
                    </div>
                    <div class="col mb-4">
                        <label for="username" class="fw-bold fs-5">T/A<i> (Mfumu Yaikulu)</i></label>
                        <input type="gender" class="form-control my-2 fs-5 p-2" name="gender" id="gender" placeholder="T/A" required>
                    </div>
                </div>

                <div class="row justify-content-center align-items-center">
                    <div class="col mb-4">
                        <label for="district" class="fw-bold fs-5">District <i>(Boma)</i></label>
                        <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district" placeholder="District" required>
                    </div>
                    <div class="col mb-4">
                        <label for="dob" class="fw-bold fs-5">Date of Birth <i>(Tsiku lobadwa)</i></label>
                        <input type="date" class="form-control my-2 fs-5 p-2" name="dob" id="dob" required>
                    </div>
                </div>

                <div class="row justify-content-center align-items-center">
                    <div class="col mb-4">
                        <label for="email" class="fw-bold fs-5">Email Address</label>
                        <input type="email" class="form-control my-2 fs-5 p-2" name="email" id="email" placeholder="Email Address" required>
                    </div>
                    <div class="col mb-4">
                        <label for="gender" class="fw-bold fs-5">Gender <i>(Mamuna/Mkazi)</i></label>
                        <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                            <option value="" selected disabled>Select Gender</option>
                            <option value="male">Male <i>(Mamuna)</i></option>
                            <option value="female">Female <i>(Mkazi)</i></option>
                        </select>
                    </div>
                </div>

                <div class="row justify-content-center align-items-center">
                    <div class="col mb-4">
                        <label for="gender" class="fw-bold fs-5">ID Document <i>(Mtundu wa Chiphaso)</i></label>
                        <select class="form-control my-2 fs-5 p-2" name="document" id="document" required>
                            <option value="" selected disabled>Select Document</option>
                            <option value="male">National ID <i>(Cha Nzika)</i></option>
                            <option value="female">Marriage Certificate <i>(Cha Banja)</i></option>
                            <option value="male">Death Certificate <i>(Cha Imfa)</i></option>
                        </select>
                    </div>
                    <div class="col mb-4">
                        <label for="email" class="fw-bold fs-5">Document Number <i>(Nambala)</i></label>
                        <input type="email" class="form-control my-2 fs-5 p-2" name="email" id="email" placeholder="Document Number" required>
                    </div>
                </div>
                
                <div class="row justify-content-center align-items-center">
                    <div class="col mb-4">
                        <label for="dob" class="fw-bold fs-5">Date of Issue <i>(Tsiku loperekedwa)</i></label>
                        <input type="date" class="form-control my-2 fs-5 p-2" name="dob" id="dob" required>
                    </div>
                    <div class="col mb-4">
                        <label for="district" class="fw-bold fs-5">Place of Issue <i>(Malo loperekedwa)</i></label>
                        <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district" placeholder="District" required>
                    </div>
                </div>

                <div class="row justify-content-center align-items-center" style="">
                  <div class="col mb-4">
                        <label for="district" class="fw-bold fs-5">District of Issue <i>(Boma loperekedwa)</i></label>
                        <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district" placeholder="District" required>
                    </div>
                  <div class="col mb-4">
                      <label for="Password" class="fw-bold fs-5">Current Status <i>(Mmene Ziliri)</i></label>
                      <select class="form-control my-2 fs-5 p-2" name="document" id="document" required>
                            <option value="" selected disabled>Select status</option>
                            <option value="male">Lost <i>(Chinasowa)</i></option>
                            <option value="female">Destroyed <i>(Chinaonongeka)</i></option>
                            <option value="male">Defaced <i>(Chinafufutika)</i></option>
                            <option value="male">Incorrect Particulars <i>(Mbiri yosalondola)</i></option>
                            <option value="female">No longer Recognizable <i>(chithunzi sichikuzindikirika)</i></option>
                        </select>
                  </div>
                </div>   
                
                <div class="row justify-content-center align-items-center">
                    <div class="col mb-4">
                        <label for="police_report" class="fw-bold fs-5">Police Report <i>(Polisi Lipoti)</i></label>
                        <input type="file" class="form-control my-2 fs-5 p-2" name="police_report" id="police_report" accept="application/pdf" required>
                    </div>
                    <div class="col mb-4">
                        <label for="other_documents" class="fw-bold fs-5">Other Documents <i>(Zina zoonjezera)</i></label>
                        <input type="file" class="form-control my-2 fs-5 p-2" name="other_documents" id="other_documents">
                    </div>
                </div>

                 <div class="row justify-content-center align-items-center">
                    <div class="col mb-4">
                        <label for="police_report" class="fw-bold fs-5">Signature <i>(Siginecha)</i></label>
                        <input type="file" class="form-control my-2 fs-5 p-2" name="police_report" id="police_report" accept="application/pdf" required>
                    </div>
                    <div class="col mb-4">
                        <label for="dob" class="fw-bold fs-5">Date of Signature <i>(Tsiku losaina)</i></label>
                        <input type="date" class="form-control my-2 fs-5 p-2" name="dob" id="dob" required>
                    </div>
                </div>

                <div class="col mb-5 d-flex justify-content-center">
                    <a href="home.php" class="btn px-5 py-2 fs-5 text-center mx-3" style="width: 50%; color:#F7CE5F; background-color: #190B02;">Back</a>
                    <button type="submit" name="btn_submit" class="btn px-5 py-2 fs-5 text-center mx-3" style="width: 50%; color:#F7CE5F; background-color: #190B02;">Submit</button>
                </div>


            </form>
          </div>

        </div>
      </div>
    </div>

    <script src="js/bootstrap.min.js"></script>
  </body>
</html> -->
<form name="tilowe" method="post" action="controllers/renewals.php" enctype="multipart/form-data">

    <div class="row justify-content-center align-items-center">
        <div class="col mb-4">
            <label for="firstname" class="fw-bold fs-5">First Name<i> (Dzina loyamba)</i></label>
            <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname" placeholder="First Name" required>
        </div>
        <div class="col mb-4">
            <label for="lastname" class="fw-bold fs-5">Last Name<i> (Dzina la bambo)</i></label>
            <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname" placeholder="Last Name" required>
        </div>
    </div>

    <div class="row justify-content-center align-items-center">
        <div class="col mb-4">
            <label for="village" class="fw-bold fs-5">Village<i> (Mudzi)</i></label>
            <input type="text" class="form-control my-2 fs-5 p-2" name="village" id="village" placeholder="Village" required>
        </div>
        <div class="col mb-4">
            <label for="ta" class="fw-bold fs-5">T/A<i> (Mfumu Yaikulu)</i></label>
            <input type="text" class="form-control my-2 fs-5 p-2" name="ta" id="ta" placeholder="T/A" required>
        </div>
    </div>

    <div class="row justify-content-center align-items-center">
        <div class="col mb-4">
            <label for="district" class="fw-bold fs-5">District <i>(Boma)</i></label>
            <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district" placeholder="District" required>
        </div>
        <div class="col mb-4">
            <label for="dob" class="fw-bold fs-5">Date of Birth <i>(Tsiku lobadwa)</i></label>
            <input type="date" class="form-control my-2 fs-5 p-2" name="dob" id="dob" required>
        </div>
    </div>

    <div class="row justify-content-center align-items-center">
        <div class="col mb-4">
            <label for="email" class="fw-bold fs-5">Email Address</label>
            <input type="email" class="form-control my-2 fs-5 p-2" name="email" id="email" placeholder="Email Address" required>
        </div>
        <div class="col mb-4">
            <label for="gender" class="fw-bold fs-5">Gender <i>(Mamuna/Mkazi)</i></label>
            <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                <option value="" selected disabled>Select Gender</option>
                <option value="male">Male <i>(Mamuna)</i></option>
                <option value="female">Female <i>(Mkazi)</i></option>
            </select>
        </div>
    </div>

    <div class="row justify-content-center align-items-center">
        <div class="col mb-4">
            <label for="document_type" class="fw-bold fs-5">ID Document <i>(Mtundu wa Chiphaso)</i></label>
            <select class="form-control my-2 fs-5 p-2" name="document_type" id="document_type" required>
                <option value="" selected disabled>Select Document</option>
                <option value="national_id">National ID <i>(Cha Nzika)</i></option>
                <option value="marriage_certificate">Marriage Certificate <i>(Cha Banja)</i></option>
                <option value="death_certificate">Death Certificate <i>(Cha Imfa)</i></option>
            </select>
        </div>
        <div class="col mb-4">
            <label for="document_number" class="fw-bold fs-5">Document Number <i>(Nambala)</i></label>
            <input type="text" class="form-control my-2 fs-5 p-2" name="document_number" id="document_number" placeholder="Document Number" required>
        </div>
    </div>

    <div class="row justify-content-center align-items-center">
        <div class="col mb-4">
            <label for="date_of_issue" class="fw-bold fs-5">Date of Issue <i>(Tsiku loperekedwa)</i></label>
            <input type="date" class="form-control my-2 fs-5 p-2" name="date_of_issue" id="date_of_issue" required>
        </div>
        <div class="col mb-4">
            <label for="place_of_issue" class="fw-bold fs-5">Place of Issue <i>(Malo loperekedwa)</i></label>
            <input type="text" class="form-control my-2 fs-5 p-2" name="place_of_issue" id="place_of_issue" placeholder="Place of Issue" required>
        </div>
    </div>

    <div class="row justify-content-center align-items-center">
        <div class="col mb-4">
            <label for="district_of_issue" class="fw-bold fs-5">District of Issue <i>(Boma loperekedwa)</i></label>
            <input type="text" class="form-control my-2 fs-5 p-2" name="district_of_issue" id="district_of_issue" placeholder="District of Issue" required>
        </div>
        <div class="col mb-4">
            <label for="current_status" class="fw-bold fs-5">Current Status <i>(Mmene Ziliri)</i></label>
            <select class="form-control my-2 fs-5 p-2" name="current_status" id="current_status" required>
                <option value="" selected disabled>Select status</option>
                <option value="lost">Lost <i>(Chinasowa)</i></option>
                <option value="destroyed">Destroyed <i>(Chinaonongeka)</i></option>
                <option value="defaced">Defaced <i>(Chinafufutika)</i></option>
                <option value="incorrect">Incorrect Particulars <i>(Mbiri yosalondola)</i></option>
                <option value="unrecognizable">No longer Recognizable <i>(chithunzi sichikuzindikirika)</i></option>
            </select>
        </div>
    </div>

    <div class="row justify-content-center align-items-center">
        <div class="col mb-4">
            <label for="police_report" class="fw-bold fs-5">Police Report <i>(Polisi Lipoti)</i></label>
            <input type="file" class="form-control my-2 fs-5 p-2" name="police_report" id="police_report" accept="application/pdf" required>
        </div>
        <div class="col mb-4">
            <label for="other_documents" class="fw-bold fs-5">Other Documents <i>(Zina zoonjezera)</i></label>
            <input type="file" class="form-control my-2 fs-5 p-2" name="other_documents" id="other_documents">
        </div>
    </div>

    <div class="row justify-content-center align-items-center">
        <div class="col mb-4">
            <label for="signature" class="fw-bold fs-5">Signature <i>(Siginecha)</i></label>
            <input type="file" class="form-control my-2 fs-5 p-2" name="signature" id="signature" accept="application/pdf" required>
        </div>
        <div class="col mb-4">
            <label for="date_of_signature" class="fw-bold fs-5">Date of Signature <i>(Tsiku losaina)</i></label>
            <input type="date" class="form-control my-2 fs-5 p-2" name="date_of_signature" id="date_of_signature" required>
        </div>
    </div>

    <div class="col mb-5 d-flex justify-content-center">
        <a href="home.php" class="btn px-5 py-2 fs-5 text-center mx-3" style="width: 50%; color:#F7CE5F; background-color: #190B02;">Back</a>
        <button type="submit" name="btn_submit" class="btn px-5 py-2 fs-5 text-center mx-3" style="width: 50%; color:#F7CE5F; background-color: #190B02;">Submit</button>
    </div>

</form>
