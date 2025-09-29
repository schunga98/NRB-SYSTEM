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
          <div class="py-2 mt-4 mb-0">
              <?php
                session_start();
                if (isset($_SESSION['status'])) {
                  //echo "<div class='alert alert-success text-center' id='success-alert'>".$_SESSION['status']."</div>";
                  unset($_SESSION['status']);
                }
              ?>
              <h3 class="text-center fw-bolder fs-1" style="color: #190B02 ;">NEW APPLICATION FORM</h3>
              <hr>
          </div>

          <form class="" name="tilowe" method="post" action="controllers/applications.php">
            <h3 class="mb-3" style=""><b><u>1. APPLICANT'S DETAILS</u></b></h3>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Nationality</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Nationality" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Second Nationality</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="Second Nationality" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="dob" class="fw-bold fs-5">Date of Birth</i></label>
                <input type="date" class="form-control my-2 fs-5 p-2" name="dob" id="dob" required>
              </div>

              <div class="col mb-4">
                <label for="gender" class="fw-bold fs-5">Gender</i></label>
                <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                  <option value="" selected disabled>Select Gender</option>
                  <option value="male">Male <i>(Mamuna)</i></option>
                  <option value="female">Female <i>(Mkazi)</i></option>
                  <option value="trans">Trans <i>(trans)</i></option>
                </select>
              </div>

            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Place of Birth</label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Place of Birth" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">District</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="District" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Village</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="Village" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Traditional Authority</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Traditional Authority" required>
              </div>
              
            </div>

            <div class="row justify-content-center align-items-center">

             <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">First Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="First Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Surname</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Surname" required>
              </div>
             
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Other Names</i></label>
                <input type="email" class="form-control my-2 fs-5 p-2" name="email" id="email" placeholder="Other Names"
                  required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Birth Certificate No.</i></label>
                <input type="gender" class="form-control my-2 fs-5 p-2" name="gender" id="gender" placeholder="Certificate No."
                  required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="email" class="fw-bold fs-5">Marital Status</label>
                <input type="email" class="form-control my-2 fs-5 p-2" name="email" id="email"
                  placeholder="Marital Status" required>
              </div>
              <div class="col mb-4">
                <label for="email" class="fw-bold fs-5">Color of Eyes</label>
                <input type="email" class="form-control my-2 fs-5 p-2" name="email" id="email"
                  placeholder="Color of Eyes" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="email" class="fw-bold fs-5">Height(CM)</i></label>
                <input type="email" class="form-control my-2 fs-5 p-2" name="email" id="email"
                  placeholder="Height" required>
              </div>
              <div class="col mb-4">
                <label for="email" class="fw-bold fs-5">Mobile Phone</i></label>
                <input type="email" class="form-control my-2 fs-5 p-2" name="email" id="email"
                  placeholder="Mobile Phone" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="district" class="fw-bold fs-5">Passport Number(if Available)</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district"
                  placeholder="Passport Number" required>
              </div>
              <div class="col mb-4">
                <label for="district" class="fw-bold fs-5">Disability</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district"
                  placeholder="Disability" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center" style="">
              <div class="col mb-4">
                <label for="district" class="fw-bold fs-5">Residential Area</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district"
                  placeholder="Residential Area" required>
              </div>
              <div class="col mb-4">
                <label for="district" class="fw-bold fs-5">Residential Village</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district"
                  placeholder="Residential District" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center" style="">
              <div class="col mb-4">
                <label for="district" class="fw-bold fs-5">Residential T/A</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district"
                  placeholder="Residential Area" required>
              </div>
              <div class="col mb-4">
                <label for="district" class="fw-bold fs-5">Residential District</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district"
                  placeholder="Residential District" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center" style="">
              <div class="col mb-4">
                <label for="district" class="fw-bold fs-5">Permanent Home Area</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district"
                  placeholder="Permanent Home Area" required>
              </div>
              <div class="col mb-4">
                <label for="district" class="fw-bold fs-5">Permanent Home Village</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district"
                  placeholder="Permanent Home District" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center" style="">
              <div class="col mb-4">
                <label for="district" class="fw-bold fs-5">Permanent Home T/A</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district"
                  placeholder="Permanent Home Area" required>
              </div>
              <div class="col mb-4">
                <label for="district" class="fw-bold fs-5">Permanent Home District</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="district" id="district"
                  placeholder="Permanent Home District" required>
              </div>
            </div>
            <hr>

            <h3 class="mb-3 mt-4" style=""><b><u>2. PARENTAL DETAILS</u></b></h3>
            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Mother's ID Number</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Mother's ID Number" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Mother's Fullname</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="Mother's Fullname" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Mother's Nationality</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Mother's Nationality" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Mother's Home District</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="Mother's Home District" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Mother's Home T/A</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="Mother's Home T/A" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Mother's Home Village</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="Mother's Home Village" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Father's ID Number</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Father's ID Number" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Father's Fullname</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="Father's Fullname" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Father's Nationality</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Father's Nationality" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Father's Home District</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="Father's Home District" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Father's Home T/A</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="Father's Home T/A" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Father's Home Village</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="Father's Home Village" required>
              </div>
            </div>
            <hr>

            <h3 class="mb-3 mt-4" style=""><b><u>3. SPOUSE'S DETAILS (If Married)</u></b></h3>
            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Spouse's Receipt Number</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Spouse's Receipt Number" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Spouse's Mobile Number</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Spouse's Mobile Number" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Spouse's First Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="First Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Spouse's Last Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="lastname" id="lastname"
                  placeholder="Last Name" required>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Spouse's Other Names</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Other Names" required>
              </div>
              <div class="col mb-4">
                <label for="dob" class="fw-bold fs-5">Spouse's Date of Birth</i></label>
                <input type="date" class="form-control my-2 fs-5 p-2" name="dob" id="dob" required>
              </div>
            </div>
            <hr>

            <h3 class="mb-3 mt-4" style=""><b><u>4. CHILDREN'S DETAILS (0 - 15 Years)</u></b></h3>
            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 1 First Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 1 First Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 1 Last Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 1 Last Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 1 Other Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 1 Other Name" required>
              </div>
            </div>
            <div class="row justify-content-center align-items-center mb-4">
              <div class="col mb-4">
                <label for="dob" class="fw-bold fs-5">Child 1 Date of Birth</i></label>
                <input type="date" class="form-control my-2 fs-5 p-2" name="dob" id="dob" required>
              </div>
              <div class="col mb-4">
                <label for="gender" class="fw-bold fs-5">Child 1 Gender</i></label>
                <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                  <option value="" selected disabled>Select Gender</option>
                  <option value="male">Male</i></option>
                  <option value="female">Female</i></option>
                </select>
              </div>
              <div class="col mb-4">
                <label for="gender" class="fw-bold fs-5">Child 1 Relationship</i></label>
                <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                  <option value="" selected disabled>Select type</option>
                  <option value="male">Biological Child</i></option>
                  <option value="female">Not-Biological Child</i></option>
                </select>
              </div>
            </div>
            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 2 First Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 2 First Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 2 Last Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 2 Last Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 2 Other Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 2 Other Name" required>
              </div>
            </div>
            <div class="row justify-content-center align-items-center mb-4">
              <div class="col mb-4">
                <label for="dob" class="fw-bold fs-5">Child 2 Date of Birth</i></label>
                <input type="date" class="form-control my-2 fs-5 p-2" name="dob" id="dob" required>
              </div>
              <div class="col mb-4">
                <label for="gender" class="fw-bold fs-5">Child 2 Gender</i></label>
                <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                  <option value="" selected disabled>Select Gender</option>
                  <option value="male">Male</i></option>
                  <option value="female">Female</i></option>
                </select>
              </div>
              <div class="col mb-4">
                <label for="gender" class="fw-bold fs-5">Child 2 Relationship</i></label>
                <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                  <option value="" selected disabled>Select type</option>
                  <option value="male">Biological Child</i></option>
                  <option value="female">Not-Biological Child</i></option>
                </select>
              </div>
            </div>
            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 3 First Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 3 First Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 3 Last Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 3 Last Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 3 Other Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 3 Other Name" required>
              </div>
            </div>
            <div class="row justify-content-center align-items-center mb-4">
              <div class="col mb-4">
                <label for="dob" class="fw-bold fs-5">Child 3 Date of Birth</i></label>
                <input type="date" class="form-control my-2 fs-5 p-2" name="dob" id="dob" required>
              </div>
              <div class="col mb-4">
                <label for="gender" class="fw-bold fs-5">Child 3 Gender</i></label>
                <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                  <option value="" selected disabled>Select Gender</option>
                  <option value="male">Male</i></option>
                  <option value="female">Female</i></option>
                </select>
              </div>
              <div class="col mb-4">
                <label for="gender" class="fw-bold fs-5">Child 3 Relationship</i></label>
                <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                  <option value="" selected disabled>Select type</option>
                  <option value="male">Biological Child</i></option>
                  <option value="female">Not-Biological Child</i></option>
                </select>
              </div>
            </div>
            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 4 First Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 4 First Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 4 Last Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 4 Last Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 4 Other Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 4 Other Name" required>
              </div>
            </div>
            <div class="row justify-content-center align-items-center mb-4">
              <div class="col mb-4">
                <label for="dob" class="fw-bold fs-5">Child 4 Date of Birth</i></label>
                <input type="date" class="form-control my-2 fs-5 p-2" name="dob" id="dob" required>
              </div>
              <div class="col mb-4">
                <label for="gender" class="fw-bold fs-5">Child 4 Gender</i></label>
                <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                  <option value="" selected disabled>Select Gender</option>
                  <option value="male">Male</i></option>
                  <option value="female">Female</i></option>
                </select>
              </div>
              <div class="col mb-4">
                <label for="gender" class="fw-bold fs-5">Child 4 Relationship</i></label>
                <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                  <option value="" selected disabled>Select type</option>
                  <option value="male">Biological Child</i></option>
                  <option value="female">Not-Biological Child</i></option>
                </select>
              </div>
            </div>
            <div class="row justify-content-center align-items-center">
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 5 First Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 5 First Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 5 Last Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 5 Last Name" required>
              </div>
              <div class="col mb-4">
                <label for="username" class="fw-bold fs-5">Child 5 Other Name</i></label>
                <input type="text" class="form-control my-2 fs-5 p-2" name="firstname" id="firstname"
                  placeholder="Child 5 Other Name" required>
              </div>
            </div>
            <div class="row justify-content-center align-items-center mb-4">
              <div class="col mb-4">
                <label for="dob" class="fw-bold fs-5">Child 5 Date of Birth</i></label>
                <input type="date" class="form-control my-2 fs-5 p-2" name="dob" id="dob" required>
              </div>
              <div class="col mb-4">
                <label for="gender" class="fw-bold fs-5">Child 5 Gender</i></label>
                <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                  <option value="" selected disabled>Select Gender</option>
                  <option value="male">Male</i></option>
                  <option value="female">Female</i></option>
                </select>
              </div>
              <div class="col mb-4">
                <label for="gender" class="fw-bold fs-5">Child 5 Relationship</i></label>
                <select class="form-control my-2 fs-5 p-2" name="gender" id="gender" required>
                  <option value="" selected disabled>Select type</option>
                  <option value="male">Biological Child</i></option>
                  <option value="female">Not-Biological Child</i></option>
                </select>
              </div>
            </div>

            <div class="row justify-content-center align-items-center">
                <div class="col mb-4">
                    <label for="police_report" class="fw-bold fs-5">Letter from Village Head</i></label>
                    <input type="file" class="form-control my-2 fs-5 p-2" name="police_report" id="police_report" accept="application/pdf" required>
                </div>
                <div class="col mb-4">
                    <label for="police_report" class="fw-bold fs-5">Your Signature</i></label>
                    <input type="file" class="form-control my-2 fs-5 p-2" name="police_report" id="police_report" accept="application/pdf" required>
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

</html>