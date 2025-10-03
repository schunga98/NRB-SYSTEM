<?php
session_start();

// Check if user is logged in
//if (!isset($_SESSION['user_id'])) {
   // header("Location: login.php");
  //  exit();
//}

// Database connection
$host = 'localhost';
$dbname = 'nrb_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC) ?? [];

// Initialize messages
$message = '';
$error = '';

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['profile_picture']['name'] ?? '';
    $filetype = pathinfo($filename, PATHINFO_EXTENSION);

    if (in_array(strtolower($filetype), $allowed)) {
        if ($_FILES['profile_picture']['size'] < 5000000) {
            $new_filename = 'profile_' . $user_id . '.' . $filetype;
            $upload_path = 'uploads/' . $new_filename;

            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->execute([$upload_path, $user_id]);
                $message = "Profile picture updated successfully!";
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
            } else {
                $error = "Failed to upload image";
            }
        } else {
            $error = "File size too large. Maximum 5MB allowed.";
        }
    } else {
        $error = "Invalid file type. Only JPG, JPEG, PNG & GIF allowed.";
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $national_id = trim($_POST['national_id'] ?? '');

    if (empty($name) || empty($email) || empty($phone) || empty($national_id)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        $error = "Invalid phone number format";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);

        if ($stmt->rowCount() > 0) {
            $error = "Email already exists";
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, national_id = ? WHERE id = ?");
            if ($stmt->execute([$name, $email, $phone, $national_id, $user_id])) {
                $message = "Profile updated successfully!";
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
            } else {
                $error = "Failed to update profile";
            }
        }
    }
}

// Get profile picture
$profile_pic = !empty($user['profile_picture']) 
    ? $user['profile_picture'] 
    : 'https://ui-avatars.com/api/?name=' . urlencode($user['name'] ?? '') . '&size=200&background=667eea&color=fff';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile - National ID Renewal System</title>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
}

.profile-container {
    max-width: 700px;
    margin: 0 auto;
    background: white;
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.profile-header {
    text-align: center;
    padding: 50px 30px 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 15s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.profile-pic-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 24px;
    z-index: 1;
}

.profile-pic {
    width: 160px;
    height: 160px;
    border-radius: 50%;
    object-fit: cover;
    border: 6px solid white;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

.profile-pic:hover {
    transform: scale(1.05);
}

.upload-overlay {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    border: 3px solid white;
}

.upload-overlay:hover {
    transform: scale(1.1) rotate(15deg);
}

.upload-overlay svg {
    width: 20px;
    height: 20px;
    fill: white;
}

#profilePictureInput {
    display: none;
}

.profile-name {
    font-size: 32px;
    font-weight: 700;
    color: white;
    margin-bottom: 8px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 1;
    position: relative;
}

.profile-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    backdrop-filter: blur(10px);
}

.profile-info {
    padding: 30px;
}

.info-card {
    background: #f8f9fa;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 16px;
    transition: all 0.3s ease;
}

.info-card:hover {
    background: #f0f2f5;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-row:first-child {
    padding-top: 0;
}

.info-label {
    font-size: 14px;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-icon {
    width: 20px;
    height: 20px;
    opacity: 0.7;
}

.info-value {
    font-size: 16px;
    color: #212529;
    font-weight: 600;
    text-align: right;
    max-width: 60%;
    word-wrap: break-word;
}

.form-section {
    padding: 30px;
    display: none;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.form-section h2 {
    margin-bottom: 24px;
    font-size: 24px;
    color: #212529;
    font-weight: 700;
}

.form-group {
    margin-bottom: 24px;
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #495057;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-group input {
    width: 100%;
    padding: 16px 18px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f8f9fa;
    font-family: inherit;
}

.form-group input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-1px);
}

.button-group {
    padding: 0 30px 30px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.btn {
    padding: 16px 24px;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-family: inherit;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.btn:active {
    transform: translateY(0);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-primary:hover {
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
}

.btn-secondary {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-secondary:hover {
    background: #667eea;
    color: white;
}

.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin: 20px 30px;
    font-size: 15px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert::before {
    content: '';
    width: 24px;
    height: 24px;
    flex-shrink: 0;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-success::before {
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2328a745'%3E%3Cpath d='M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z'/%3E%3C/svg%3E") no-repeat center;
    background-size: contain;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.alert-error::before {
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23dc3545'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z'/%3E%3C/svg%3E") no-repeat center;
    background-size: contain;
}

@media (max-width: 768px) {
    body {
        padding: 10px;
    }
    
    .profile-container {
        border-radius: 16px;
    }
    
    .profile-header {
        padding: 40px 20px 30px;
    }
    
    .profile-pic {
        width: 130px;
        height: 130px;
    }
    
    .profile-name {
        font-size: 26px;
    }
    
    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .info-value {
        text-align: left;
        max-width: 100%;
    }
}
</style>
</head>
<body>
<div class="profile-container">
<?php if ($message): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="profile-header">
    <div class="profile-pic-wrapper">
        <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture" class="profile-pic" id="profileImage">
        <form method="POST" enctype="multipart/form-data" id="uploadForm">
            <label for="profilePictureInput" class="upload-overlay">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
            </label>
            <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*">
        </form>
    </div>
    <h1 class="profile-name"><?php echo htmlspecialchars($user['name'] ?? ''); ?></h1>
    <div class="profile-badge">Verified User</div>
</div>

<div class="profile-info" id="profileView">
    <div class="info-card">
        <div class="info-row">
            <span class="info-label">
                <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                Email
            </span>
            <span class="info-value"><?php echo htmlspecialchars($user['email'] ?? ''); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">
                <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56-.35-.12-.74-.03-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/>
                </svg>
                Phone
            </span>
            <span class="info-value"><?php echo htmlspecialchars($user['phone'] ?? ''); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">
                <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
                National ID
            </span>
            <span class="info-value"><?php echo htmlspecialchars($user['national_id'] ?? ''); ?></span>
        </div>
    </div>
</div>

<form method="POST" id="profileForm">
    <div class="form-section" id="profileEdit">
        <h2>Edit Profile</h2>
        
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="national_id">National ID Number</label>
            <input type="text" id="national_id" name="national_id" value="<?php echo htmlspecialchars($user['national_id'] ?? ''); ?>" required>
        </div>
    </div>
</form>

<div class="button-group" id="editButtonContainer">
    <button type="submit" name="update_profile" form="profileForm" class="btn btn-primary" style="display:none;" id="saveBtn">Save Changes</button>
    <button type="button" class="btn btn-secondary" id="cancelEdit" style="display:none;">Cancel</button>
    <button type="button" class="btn btn-primary" id="editProfileBtn">Edit Profile</button>
</div>

</div>

<script>
const editBtn = document.getElementById('editProfileBtn');
const saveBtn = document.getElementById('saveBtn');
const profileEdit = document.getElementById('profileEdit');
const profileView = document.getElementById('profileView');
const cancelBtn = document.getElementById('cancelEdit');

editBtn.addEventListener('click', () => {
    profileEdit.style.display = 'block';
    profileView.style.display = 'none';
    editBtn.style.display = 'none';
    saveBtn.style.display = 'block';
    cancelBtn.style.display = 'block';
});

cancelBtn.addEventListener('click', () => {
    profileEdit.style.display = 'none';
    profileView.style.display = 'block';
    editBtn.style.display = 'block';
    saveBtn.style.display = 'none';
    cancelBtn.style.display = 'none';
});

document.getElementById('profilePictureInput').addEventListener('change', function() {
    if(this.files && this.files[0]){
        const reader = new FileReader();
        reader.onload = e => document.getElementById('profileImage').src = e.target.result;
        reader.readAsDataURL(this.files[0]);
        document.getElementById('uploadForm').submit();
    }
});
</script>
</body>
</html>