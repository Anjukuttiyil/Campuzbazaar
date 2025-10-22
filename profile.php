<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.html');
    exit();
}

$email = $_SESSION['email'];

// Database configuration
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "bazaar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch user data
$sql = "SELECT * FROM userdata WHERE email='$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - CampusBazaaR</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #4A90E2;
            --secondary-color: #F5F7FA;
            --text-color: #2C3E50;
            --border-radius: 15px;
        }

        body {
            background: url('background.jpg') no-repeat center center fixed;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .container {
            margin-top: 100px;
            margin-bottom: 50px;
        }

        .form-container {
            background: white;
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .profile-header h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin: 0 auto 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #E3E8ED;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        }

        .form-control:disabled {
            background-color: var(--secondary-color);
            cursor: not-allowed;
        }

        .btn {
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background-color: #357ABD;
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: #2ECC71;
            border: none;
        }

        .btn-success:hover {
            background-color: #27AE60;
            transform: translateY(-2px);
        }

        .form-section {
            background: var(--secondary-color);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
        }

        .form-section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .container {
                margin-top: 80px;
            }
            
            .form-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">CampusBazaaR</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="menu.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php"><i class="fas fa-user"></i> Profile</a>
                </li>
              
                <li class="nav-item">
                    <a class="nav-link" href="reviews.php"><i class="fas fa-sign-out-alt"></i> Review and ratingt</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="form-container">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h2>Profile Details</h2>
                    </div>
                    
                    <form id="profileForm">
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-user-circle"></i> Personal Information
                            </div>
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" value="<?php echo htmlspecialchars($user['phone_number']); ?>" pattern="[0-9]{10}" required>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-graduation-cap"></i> Academic Details
                            </div>
                            <div class="form-group">
                                <label for="course">Course</label>
                                <input type="text" class="form-control" id="course" value="<?php echo htmlspecialchars($user['course']); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="branch">Branch</label>
                                <input type="text" class="form-control" id="branch" value="<?php echo htmlspecialchars($user['branch']); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="batch">Batch</label>
                                <input type="text" class="form-control" id="batch" value="<?php echo htmlspecialchars($user['batch']); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="admissionNumber">Admission Number</label>
                                <input type="text" class="form-control" id="admissionNumber" value="<?php echo htmlspecialchars($user['admission_number']); ?>" disabled>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-map-marker-alt"></i> Contact Details
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary btn-block" id="editProfileButton">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                        <button type="submit" class="btn btn-success btn-block d-none" id="saveProfileButton">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('editProfileButton').addEventListener('click', function() {
            const allFields = ['name', 'phone', 'address', 'course', 'branch', 'batch', 'admissionNumber'];
            allFields.forEach(field => {
                const input = document.getElementById(field);
                input.disabled = !input.disabled;
                if (!input.disabled) {
                    input.focus();
                }
            });
            
            document.getElementById('editProfileButton').classList.toggle('d-none');
            document.getElementById('saveProfileButton').classList.toggle('d-none');
        });

        document.getElementById('profileForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = {
                name: document.getElementById('name').value,
                address: document.getElementById('address').value,
                phone_number: document.getElementById('phone').value,
                course: document.getElementById('course').value,
                branch: document.getElementById('branch').value,
                batch: document.getElementById('batch').value,
                admission_number: document.getElementById('admissionNumber').value
            };

            fetch('update_profile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred.');
            });
        });
    </script>
</body>
</html>
