<?php
session_start(); // Start the session
if (isset($_SESSION['email'])) {
    // Database connection parameters
    $host = 'localhost';
    $username = 'root';
    $password = '1234';
    $database = 'bazaar';

    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch product details based on the product ID
    $productId = $_GET['id'];
    $stmt = $conn->prepare("SELECT p.name, p.price, p.image, p.description, u.username, u.email FROM products p JOIN userdata u ON p.email = u.email WHERE p.id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<h2>Product not found!</h2>";
        exit;
    }

    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Now - <?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.16/tailwind.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>

:root {
            --primary-color: #2C3E50;
            --secondary-color: #3498DB;
            --accent-color: #E74C3C;
            --success-color: #2ECC71;
            --warning-color: #F1C40F;
            --danger-color: #E74C3C;
            --background-color: #F7F9FC;
            --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }
        .navbar {
            background: var(--primary-color)
            padding: 1rem 2rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s ease;
            margin: 0 0.5rem;
            position: relative;
            font-weight: 500;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: white;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .button-container {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .custom-button {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
            flex: 1;
        }

        .pay-button {
            background-color: #4F46E5;
            color: white;
        }

        .pay-button:hover {
            background-color: #4338CA;
            transform: translateY(-2px);
        }

        .rate-button {
            background-color: #10B981;
            color: white;
        }

        .rate-button:hover {
            background-color: #059669;
            transform: translateY(-2px);
        }

        /* Centering the buy now section */
        .buy-now-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .buy-now-content {
            max-width: 1200px;
            width: 100%;
            padding: 1rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#"><i class="fas fa-store mr-2"></i>CampusBazaaR</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="sellmenu.html"><i class="fas fa-home mr-1"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html"><i class="fas fa-info-circle mr-1"></i>About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="buy-now-container">
        <div class="buy-now-content">
            <h2 class="text-2xl font-bold mb-6 text-center">Buy Now</h2>
            <div class="bg-white rounded-lg shadow-md p-6 sm:flex items-center">
                <div class="sm:w-1/2 mb-6 sm:mb-0">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full rounded-lg">
                </div>
                <div class="sm:w-1/2 sm:pl-6">
                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="text-gray-600 mb-4">Price: ₹<?php echo number_format($product['price'], 2); ?></p>
                    <p class="text-gray-600 mb-4"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    <p class="text-gray-600 mb-4">Seller: <?php echo htmlspecialchars($product['username']); ?></p>

                    <div class="button-container">
                        <button id="rzp-button1" class="custom-button pay-button">
                            Pay Now
                        </button>
                        <a href="rating1.php?email=<?php echo urlencode($product['email']); ?>" class="custom-button rate-button">
                            Rate Seller
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var options = {
            "key": "rzp_test_uGeI4kIzxIZHZA",
            "amount": <?php echo $product['price'] * 100; ?>,
            "currency": "INR",
            "name": "CampusBazaaR",
            "description": "<?php echo htmlspecialchars($product['name']); ?>",
            "image": "https://example.com/your_logo.jpg",
            "order_id": "",
            "handler": function (response) {
                var paymentId = response.razorpay_payment_id;
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "save_payment.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        window.location.href = 'buyerorder.php';
                    } else {
                        alert('Payment not saved: ' + xhr.responseText);
                    }
                };
                var sellerEmail = "<?php echo htmlspecialchars($product['email']); ?>";
                var buyerEmail = "<?php echo htmlspecialchars($_SESSION['email']); ?>";
                xhr.send("product_id=<?php echo $productId; ?>&payment_id=" + paymentId + 
                    "&amount=<?php echo $product['price']; ?>&" +
                    "seller_email=" + encodeURIComponent(sellerEmail) + 
                    "&buyer_email=" + encodeURIComponent(buyerEmail));
            },
            "prefill": {
                "name": "",
                "email": "",
                "contact": ""
            },
            "theme": {
                "color": "#4F46E5"
            }
        };

        document.getElementById('rzp-button1').onclick = function(e) {
            var rzp = new Razorpay(options);
            rzp.open();
            e.preventDefault();
        }
    </script>
</body>
</html>
<?php
} else {
    echo "Not logged in";
}
?>
