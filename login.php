<?php
// PHP code remains the same
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "bazaar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    
    $sql = "SELECT * FROM login WHERE email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows === 0) {
        header("Location: login.php?error=Invalid email or password.");
        exit();
    }
    
    $user = $result->fetch_assoc();
   
    if (password_verify($password, $user['password_hash'])) {
        if($user['verify'] != 1)
         {

            header("Location: login.php?error=User not verified.");
            exit();
         }
         else
          {
        $_SESSION['email'] = $email;
        header("Location: " . ($user['user_type'] === 'admin' ? 'admin.php' : 'menu.php'));
        exit();
          }}
     else {
        header("Location: login.php?error=Invalid email or password.");
        exit();
    }

}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CampusBazaaR</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%);
            min-height: 100vh;
        }
        
        .navbar {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .logo-text {
            background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .main-container {
            margin-top: 4rem;
            padding: 2rem;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .form-input {
            transition: all 0.3s ease;
        }

        .form-input:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .login-button {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            transition: all 0.3s ease;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
        }

        .image-container img {
            border-radius: 1rem 0 0 1rem;
            object-fit: cover;
            height: 100%;
            width: 100%;
        }

        @media (max-width: 1024px) {
            .image-container img {
                border-radius: 1rem 1rem 0 0;
                max-height: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar fixed-top py-4 px-6">
        <div class="container mx-auto flex justify-between items-center">
            <a class="flex items-center space-x-2 text-2xl font-bold logo-text" href="#">
                <i class="fas fa-store"></i>
                <span>CampusBazaaR</span>
            </a>
            <div class="flex space-x-6">
                <a class="text-white hover:text-blue-200 transition-colors" href="signup.html">
                    <i class="fas fa-user-plus mr-2"></i>Sign up
                </a>
                <a class="text-white hover:text-blue-200 transition-colors" href="login.php">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container container mx-auto px-4">
        <div class="login-card max-w-5xl mx-auto">
            <div class="flex flex-col lg:flex-row">
                <!-- Image Section -->
                <div class="lg:w-1/2 image-container">
                    <img src="view.jpg" alt="CampusBazaaR" class="w-full">
                </div>

                <!-- Form Section -->
                <div class="lg:w-1/2 p-8 lg:p-12">
                    <div class="max-w-md mx-auto">
                        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Welcome Back!</h2>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                                <?php echo htmlspecialchars($_GET['error']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['success'])): ?>
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                                <?php echo htmlspecialchars($_GET['success']); ?>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" class="space-y-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <input type="password" name="password" id="password" 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                            <button type="submit" 
                                class="login-button w-full py-3 px-4 text-white text-lg font-semibold rounded-lg">
                                Login
                            </button>
                        </form>

                        <p class="mt-6 text-center text-gray-600">
                            Don't have an account? 
                            <a href="signup.html" class="text-blue-600 hover:text-blue-800 font-medium">Sign up</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>