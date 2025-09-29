<?php
session_start();

// Check if user is logged in
//if (!isset($_SESSION['user_id'])) {
   // header('Location: login.php');
   // exit();
//}

// For demonstration, set some default values if not set
if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = 'John Doe';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NRB System - Apply for Renewal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        /* Sidebar Navigation */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(135deg, #2c5aa0 0%, #1e4080 100%);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .logo {
            color: white;
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .logo i {
            font-size: 3rem;
            margin-bottom: 10px;
            display: block;
        }

        .logo h2 {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .logo p {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-top: 5px;
        }

        .nav-menu {
            list-style: none;
            padding: 0 10px;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(255,255,255,0.2);
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 30px;
            margin-right: 15px;
        }

        .nav-link span {
            font-size: 1rem;
            font-weight: 500;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 260px;
            padding: 0;
            min-height: 100vh;
        }

        /* Top Header */
        .top-header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-title h1 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .header-title p {
            color: #666;
            font-size: 0.9rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 25px;
            text-decoration: none;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2c5aa0 0%, #1e4080 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .user-info h4 {
            color: #333;
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .user-info p {
            color: #666;
            font-size: 0.75rem;
        }

        /* Form Content */
        .form-content {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c5aa0;
        }

        .form-header h2 {
            color: #2c5aa0;
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .form-header p {
            color: #666;
            font-size: 1rem;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .row {
            display: flex;
            gap: 20px;
            margin-bottom: 0;
        }

        .col {
            flex: 1;
        }

        .mb-4 {
            margin-bottom: 25px;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 8px;
        }

        label i {
            font-style: italic;
            color: #666;
            font-weight: 400;
            font-size: 0.85rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9ff;
        }

        .form-control:focus {
            outline: none;
            border-color: #2c5aa0;
            background: white;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
        }

        .form-control:valid {
            border-color: #28a745;
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }

        input[type="file"].form-control {
            padding: 10px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            padding: 15px 40px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            flex: 1;
            max-width: 250px;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-submit {
            background: linear-gradient(135deg, #2c5aa0 0%, #1e4080 100%);
            color: white;
            flex: 1;
            max-width: 250px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 90, 160, 0.3);
        }

        .btn-submit:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        /* Progress Indicator */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            padding: 0 20px;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e1e8ed;
            z-index: -1;
        }

        .step:first-child::before {
            left: 50%;
        }

        .step:last-child::before {
            right: 50%;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e1e8ed;
            color: #666;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .step.active .step-circle {
            background: #2c5aa0;
            color: white;
        }

        .step-label {
            font-size: 0.85rem;
            color: #666;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background: #2c5aa0;
                color: white;
                border: none;
                width: 45px;
                height: 45px;
                border-radius: 8px;
                font-size: 1.2rem;
                cursor: pointer;
            }

            .row {
                flex-direction: column;
                gap: 0;
            }

            .form-container {
                padding: 25px 20px;
            }

            .form-header h2 {
                font-size: 1.5rem;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                max-width: 100%;
            }

            .progress-steps {
                display: none;
            }
        }

        .mobile-toggle {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <i class="fas fa-id-card"></i>
            <h2>NRB Portal</h2>
            <p>Citizen Services</p>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="citizen_dashboard.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="apply_renewal.php" class="nav-link active">
                    <i class="fas fa-file-alt"></i>
                    <span>Apply for Renewal</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="my_applications.php" class="nav-link">
                    <i class="fas fa-list-alt"></i>
                    <span>My Applications</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="notifications.php" class="nav-link">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="profile.php" class="nav-link">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li class="nav-item" style="margin-top: 20px;">
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-title">
                <h1>Apply for ID Renewal</h1>
                <p>Complete the form below to submit your renewal application</p>
            </div>

            <div class="header-actions">
                <!-- User Profile -->
                <a href="profile.php" class="user-profile">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h4><?php echo htmlspecialchars($_SESSION['full_name']); ?></h4>
                        <p>Citizen</p>
                    </div>
                </a>
            </div>
        </header>

        <!-- Form Content -->
        <div class="form-content">
            <div class="form-container">
                <!-- Form Header -->
                <div class="form-header">
                    <h2>RENEW APPLICATION FORM</h2>
                    <p>Please fill in all required fields accurately</p>
                </div>

                <!-- Progress Steps -->
              <div class="progress-steps">
                    <div class="step active">
                        <div class="step-circle">1</div>
                        <div class="step-label">Personal Info</div>
                    </div>
                    <div class="step">
                        <div class="step-circle">2</div>
                        <div class="step-label">Document Details</div>
                    </div>
                    <div class="step">
                        <div class="step-circle">3</div>
                        <div class="step-label">Upload Files</div>
                    </div>
                </div>

                <!-- Alert Messages -->
                <?php
                if (isset($_SESSION['status'])) {
                    echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i>".$_SESSION['status']."</div>";
                    unset($_SESSION['status']);
                }
                ?>

                <!-- YOUR ORIGINAL FORM - All fields kept exactly as you have them -->
                <form name="tilowe" method="post" action="controllers/renewals.php" enctype="multipart/form-data">

                    <div class="row justify-content-center align-items-center">
                        <div class="col mb-4">
                            <label for="firstname">First Name<i> (Dzina loyamba)</i></label>
                            <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name" required>
                        </div>
                        <div class="col mb-4">
                            <label for="lastname">Last Name<i> (Dzina la bambo)</i></label>
                            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name" required>
                        </div>
                    </div>

                    <div class="row justify-content-center align-items-center">
                        <div class="col mb-4">
                            <label for="village">Village<i> (Mudzi)</i></label>
                            <input type="text" class="form-control" name="village" id="village" placeholder="Village" required>
                        </div>
                        <div class="col mb-4">
                            <label for="ta">T/A<i> (Mfumu Yaikulu)</i></label>
                            <input type="text" class="form-control" name="ta" id="ta" placeholder="T/A" required>
                        </div>
                    </div>

                    <div class="row justify-content-center align-items-center">
                        <div class="col mb-4">
                            <label for="district">District <i>(Boma)</i></label>
                            <input type="text" class="form-control" name="district" id="district" placeholder="District" required>
                        </div>
                        <div class="col mb-4">
                            <label for="dob">Date of Birth <i>(Tsiku lobadwa)</i></label>
                            <input type="date" class="form-control" name="dob" id="dob" required>
                        </div>
                    </div>

                    <div class="row justify-content-center align-items-center">
                        <div class="col mb-4">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" required>
                        </div>
                        <div class="col mb-4">
                            <label for="gender">Gender <i>(Mamuna/Mkazi)</i></label>
                            <select class="form-control" name="gender" id="gender" required>
                                <option value="" selected disabled>Select Gender</option>
                                <option value="male">Male <i>(Mamuna)</i></option>
                                <option value="female">Female <i>(Mkazi)</i></option>
                            </select>
                        </div>
                    </div>

                    <div class="row justify-content-center align-items-center">
                        <div class="col mb-4">
                            <label for="document_type">ID Document <i>(Mtundu wa Chiphaso)</i></label>
                            <select class="form-control" name="document_type" id="document_type" required>
                                <option value="" selected disabled>Select Document</option>
                                <option value="national_id">National ID <i>(Cha Nzika)</i></option>
                                <option value="marriage_certificate">Marriage Certificate <i>(Cha Banja)</i></option>
                                <option value="death_certificate">Death Certificate <i>(Cha Imfa)</i></option>
                            </select>
                        </div>
                        <div class="col mb-4">
                            <label for="document_number">Document Number <i>(Nambala)</i></label>
                            <input type="text" class="form-control" name="document_number" id="document_number" placeholder="Document Number" required>
                        </div>
                    </div>

                    <div class="row justify-content-center align-items-center">
                        <div class="col mb-4">
                            <label for="date_of_issue">Date of Issue <i>(Tsiku loperekedwa)</i></label>
                            <input type="date" class="form-control" name="date_of_issue" id="date_of_issue" required>
                        </div>
                        <div class="col mb-4">
                            <label for="place_of_issue">Place of Issue <i>(Malo loperekedwa)</i></label>
                            <input type="text" class="form-control" name="place_of_issue" id="place_of_issue" placeholder="Place of Issue" required>
                        </div>
                    </div>

                    <div class="row justify-content-center align-items-center">
                        <div class="col mb-4">
                            <label for="district_of_issue">District of Issue <i>(Boma loperekedwa)</i></label>
                            <input type="text" class="form-control" name="district_of_issue" id="district_of_issue" placeholder="District of Issue" required>
                        </div>
                        <div class="col mb-4">
                            <label for="current_status">Current Status <i>(Mmene Ziliri)</i></label>
                            <select class="form-control" name="current_status" id="current_status" required>
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
                            <label for="police_report">Police Report <i>(Polisi Lipoti)</i></label>
                            <input type="file" class="form-control" name="police_report" id="police_report" accept="application/pdf" required>
                        </div>
                        <div class="col mb-4">
                            <label for="other_documents">Other Documents <i>(Zina zoonjezera)</i></label>
                            <input type="file" class="form-control" name="other_documents" id="other_documents">
                        </div>
                    </div>

                    <div class="row justify-content-center align-items-center">
                        <div class="col mb-4">
                            <label for="signature">Signature <i>(Siginecha)</i></label>
                            <input type="file" class="form-control" name="signature" id="signature" accept="application/pdf" required>
                        </div>
                        <div class="col mb-4">
                            <label for="date_of_signature">Date of Signature <i>(Tsiku losaina)</i></label>
                            <input type="date" class="form-control" name="date_of_signature" id="date_of_signature" required>
                        </div>
                    </div>

                    <div class="button-group">
                        <a href="citizen_dashboard.php" class="btn btn-back">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" name="btn_submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </main>

    <script>
        // Mobile menu toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');

        if (mobileToggle) {
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }

        // Form validation feedback
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required], select[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = '#28a745';
                }
            });

            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.style.borderColor = '#2c5aa0';
                }
            });
        });

        // Form submission loading state
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('.btn-submit');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        });
    </script>
</body>
</html>