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

    $stmt = $conn->prepare("INSERT INTO messages (sender_email, receiver_email, message, product_id) VALUES (?, ?, ?, ?)");
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
    <title>Messaging</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-gray-800 py-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white font-bold text-xl">CampusBazaaR</a>
            <div class="space-x-4">
                <a href="index.html" class="text-gray-300 hover:text-white">Home</a>
                <a href="profile.php" class="text-gray-300 hover:text-white">Profile</a>
                <a href="about.html" class="text-gray-300 hover:text-white">About</a>
                <a href="logout.php" class="text-gray-300 hover:text-white">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto my-8">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Messaging</h2>
            <div class="space-y-4">
                <?php foreach ($messages as $msg): ?>
                    <div class="bg-gray-200 p-4 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium"><?php echo htmlspecialchars($msg['sender_email']); ?></span>
                            <span class="text-sm text-gray-500"><?php echo $msg['created_at']; ?></span>
                        </div>
                        <p class="text-gray-700"><?php echo htmlspecialchars($msg['message']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <form method="POST" class="mt-6">
                <div class="mb-4">
                    <label for="message" class="block font-medium mb-2">Message:</label>
                    <textarea class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="message" name="message" rows="3" required></textarea>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-md">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>