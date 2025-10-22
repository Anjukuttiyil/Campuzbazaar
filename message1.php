<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "<h2>Please log in to send messages.</h2>";
    exit;
}

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '1234';
$database = 'bazaar';

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user's email
$user_email = $_SESSION['email'];

// Retrieve buyer's email and product ID from URL if available
$receiver_email = isset($_GET['buyer_email']) ? $_GET['buyer_email'] : '';
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';

// Handle sending a message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO messages (sender_email,receiver_email, message, product_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user_email, $receiver_email, $message, $product_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch messages involving the user and the buyer, filtered by product ID
$sql = "SELECT * FROM messages  
        ORDER BY created_at ASC";

$result = $conn->query($sql);
$messages = [];
if ($result) {
    $messages = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging Application</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="navbar bg-base-100 shadow-md">
        <div class="navbar-start">
            <a class="btn btn-ghost normal-case text-xl" href="#">CampusBazaaR</a>
        </div>
        <div class="navbar-end">
            <a class="btn btn-ghost" href="index.html">Home</a>
            <a class="btn btn-ghost" href="profile.php">Profile</a>
            <a class="btn btn-ghost" href="about.html">About</a>
            <a class="btn btn-ghost" href="logout.php">Logout</a>
        </div>
    </div>

    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white shadow-xl rounded-lg w-full max-w-4xl p-8">
            <h2 class="text-2xl font-bold mb-4">Messaging Application</h2>

            <div class="mb-4">
                <h3 class="text-xl font-medium mb-2">Your Messages</h3>
                <div class="bg-base-200 rounded-lg p-4 max-h-80 overflow-y-auto">
                    <?php foreach ($messages as $msg): ?>
                        <div class="mb-3 p-4 bg-base-100 rounded-lg">
                            <div class="flex items-center mb-1">
                                <span class="font-medium"><?php echo htmlspecialchars($msg['sender_email']); ?>:</span>
                                <span class="text-xs text-gray-500 ml-2"><?php echo $msg['created_at']; ?></span>
                            </div>
                            <p class="text-gray-700"><?php echo htmlspecialchars($msg['message']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="message" class="block font-medium mb-1">Message:</label>
                    <textarea class="textarea textarea-bordered w-full" id="message" name="message" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>