<?php
session_start();

// Check if user is logged in
//if (!isset($_SESSION['user_id'])) {
   // header("Location: login.php");
  //  exit();
//}

// Database connection
$host = 'localhost';
$dbname = 'nrb'; // Change to your database name
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
                // Refresh user data
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
                // Refresh user data
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
/* ... same CSS as before ... */
.profile-container { max-width: 600px; margin: 0 auto; background: white; min-height: 100vh; }
.profile-header { text-align: center; padding: 60px 20px 40px; position: relative; background: linear-gradient(180deg,#b8b5ff 0%,#fff 100%);}
.profile-pic-wrapper { position: relative; display: inline-block; margin-bottom: 20px; }
.profile-pic { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 5px solid white; box-shadow:0 4px 12px rgba(0,0,0,0.15);}
.upload-overlay { position: absolute; bottom: 5px; right: 5px; background:#667eea; width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,0.2);}
.upload-overlay:hover { background:#5568d3; transform:scale(1.1);}
#profilePictureInput { display:none; }
.profile-name { font-size:28px; font-weight:700; color:#1a1a1a; margin-bottom:5px; }
.profile-info { background:white; padding:0 20px; }
.info-row { display:flex; justify-content:space-between; align-items:center; padding:20px 0; border-bottom:1px solid #f0f0f0; }
.info-label { font-size:16px; color:#8e8e93; font-weight:400; }
.info-value { font-size:16px; color:#1a1a1a; font-weight:500; text-align:right; max-width:60%; word-wrap:break-word; }
.form-section { background:white; padding:20px; margin-top:20px; display:none; }
.form-group { margin-bottom:20px; }
.form-group label { display:block; margin-bottom:8px; font-weight:500; color:#1a1a1a; font-size:14px; }
.form-group input { width:100%; padding:14px 16px; border:1px solid #e0e0e0; border-radius:10px; font-size:16px; transition:all 0.3s ease; background:#f9f9f9; }
.form-group input:focus { outline:none; border-color:#667eea; background:white; box-shadow:0 0 0 3px rgba(102,126,234,0.1);}
.button-group { padding:20px; display:flex; flex-direction:column; gap:12px; }
.btn { padding:16px 20px; border:none; border-radius:12px; font-size:16px; font-weight:600; cursor:pointer; text-align:center; }
.btn-primary { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; }
.btn-secondary { background:#6c757d; color:white; }
.btn-back { background:white; color:#667eea; border:2px solid #667eea; }
.alert { padding:14px 16px; border-radius:10px; margin:20px; font-size:14px; }
.alert-success { background-color:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.alert-error { background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
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
</div>

<div class="profile-info" id="profileView">
    <div class="info-row"><span class="info-label">Phone</span><span class="info-value"><?php echo htmlspecialchars($user['phone'] ?? ''); ?></span></div>
    <div class="info-row"><span class="info-label">Mail</span><span class="info-value"><?php echo htmlspecialchars($user['email'] ?? ''); ?></span></div>
    <div class="info-row"><span class="info-label">National ID</span><span class="info-value"><?php echo htmlspecialchars($user['national_id'] ?? ''); ?></span></div>
</div>

<form method="POST" id="profileForm">
    <div class="form-section" id="profileEdit">
        <h2 style="margin-bottom:20px; font-size:20px; color:#1a1a1a;">Edit Profile</h2>
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
        <div class="button-group">
            <button type="submit" name="update_profile" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
        </div>
    </div>
</form>

<div class="button-group" id="editButtonContainer">
    <button type="button" class="btn btn-primary" id="editProfileBtn">Edit Profile</button>
</div>

<script>
// Toggle edit/view
const editBtn = document.getElementById('editProfileBtn');
const profileEdit = document.getElementById('profileEdit');
const profileView = document.getElementById('profileView');
const editContainer = document.getElementById('editButtonContainer');
const cancelBtn = document.getElementById('cancelEdit');

editBtn.addEventListener('click', () => {
    profileEdit.style.display = 'block';
    profileView.style.display = 'none';
    editContainer.style.display = 'none';
});

cancelBtn.addEventListener('click', () => {
    profileEdit.style.display = 'none';
    profileView.style.display = 'block';
    editContainer.style.display = 'block';
});

// Auto-upload profile picture
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
