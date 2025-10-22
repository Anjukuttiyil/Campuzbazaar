<?php
session_start();

// Database connection (update with your credentials)
$db = new PDO('mysql:host=localhost;dbname=bazaar', 'root', '1234');

// Check if the user is allowed to post a review
$rated_user = $_GET['seller_email'];
$ratin_user = $_SESSION['email'];  // Corrected session variable

// Check if there is already a review from the ratin_user for the rated_user
$stmt = $db->prepare("SELECT * FROM review_table WHERE ratin_user = ? AND rated_user = ?");
$stmt->execute([$ratin_user, $rated_user]);
$existing_review = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$existing_review) {
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $review = filter_input(INPUT_POST, 'review', FILTER_SANITIZE_STRING);

    if ($rating && $review && $rated_user && $ratin_user) {  // Added missing checks
        $stmt = $db->prepare("INSERT INTO review_table (ratin_user, rated_user, user_rating, user_review) VALUES (?, ?, ?, ?)");
        $stmt->execute([$ratin_user, $rated_user, $rating, $review]);  // Corrected insertion
        $_SESSION['message'] = "Thank you for your review!";
        header("Location: " . $_SERVER['PHP_SELF']);
        echo"setTimeout(() => window.location.href = 'menu.php', 2000);";

        exit;
    } else {
        $_SESSION['message'] = "Please fill in all fields.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch the average rating of the rated_user if there are reviews
$avg_rating_stmt = $db->prepare("SELECT AVG(user_rating) as avg_rating FROM review_table WHERE rated_user = ?");
$avg_rating_stmt->execute([$rated_user]);
$avg_rating = $avg_rating_stmt->fetch(PDO::FETCH_ASSOC)['avg_rating'] ?? 0;

// Fetch existing reviews for the rated_user
$reviews_stmt = $db->prepare("SELECT * FROM review_table WHERE rated_user = ? ORDER BY id DESC");
$reviews_stmt->execute([$rated_user]);
$reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews | CampusBazaaR</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f6f9fc 0%, #eef2ff 100%);
        }

        .nav-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }

        .review-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .review-card:hover {
            transform: translateY(-2px);
        }

        .star-rating label {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .star-rating label:hover {
            transform: scale(1.2);
        }

        .review-form {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Navigation -->
    <nav class="nav-gradient fixed w-full z-50 shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <a class="flex items-center space-x-2 text-white text-xl font-bold" href="#">
                    <i class="fas fa-store"></i>
                    <span>CampusBazaaR</span>
                </a>
                <div class="hidden md:flex space-x-8">
                    <a href="sellmenu.html" class="text-white hover:text-indigo-200 transition">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="about.html" class="text-white hover:text-indigo-200 transition">
                        <i class="fas fa-info-circle mr-2"></i>About
                    </a>
                    <a href="logout.php" class="text-white hover:text-indigo-200 transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pt-24 pb-12">
        <!-- Success Message -->
        <?php if (isset($_SESSION['message'])): ?>
        <div class="max-w-2xl mx-auto mb-8">
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center">
                <i class="fas fa-check-circle text-emerald-500 mr-3 text-xl"></i>
                <p class="text-emerald-700">
                    <?php 
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    ?>
                </p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Review Form -->
        <?php if (!$existing_review): ?>
        <div class="max-w-2xl mx-auto mb-16">
            <div class="review-form rounded-2xl shadow-xl p-8">
                <h2 class="text-3xl font-bold mb-8 text-center bg-gradient-to-r from-indigo-600 to-purple-600 text-transparent bg-clip-text">
                    Share Your Experience
                </h2>
                <form method="POST" action="" class="space-y-8">
                    <div>
                        <label class="block text-gray-700 mb-4 font-medium text-lg">Rate your experience</label>
                        <div class="star-rating flex gap-4 justify-center">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" class="hidden" required>
                            <label for="star<?= $i ?>" class="transform hover:scale-110 transition-transform">
                                <i class="fas fa-star text-4xl text-gray-300 hover:text-yellow-400"
                                   onmouseover="highlightStars(<?= $i ?>)"
                                   onmouseout="resetStars()"></i>
                            </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-3 font-medium text-lg">Your Review</label>
                        <textarea name="review" rows="4" required
                                class="w-full px-5 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none text-gray-700"
                                placeholder="Tell us about your experience..."></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-xl font-semibold hover:opacity-90 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Submit Review
                    </button>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="max-w-2xl mx-auto mb-16">
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
                <i class="fas fa-info-circle text-amber-500 mr-2"></i>
                <span class="text-amber-700">You've already shared your review for this seller.</span>
            </div>
        </div>
        <?php endif; ?>

        <!-- Average Rating -->
        <div class="max-w-2xl mx-auto mb-12 bg-white rounded-2xl shadow-lg p-8 text-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Overall Rating</h3>
            <div class="flex justify-center gap-2 items-center">
                <span class="text-4xl font-bold text-indigo-600"><?= round($avg_rating, 1) ?></span>
                <div class="flex ml-3">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star <?= $i <= round($avg_rating) ? 'text-yellow-400' : 'text-gray-200' ?> text-2xl"></i>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold mb-8 text-gray-800">Customer Reviews</h3>
            <?php foreach ($reviews as $review): ?>
            <div class="review-card bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                            <?= strtoupper(substr($review['ratin_user'], 0, 1)) ?>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800"><?= htmlspecialchars($review['ratin_user']) ?></h4>
                            <div class="flex gap-1 mt-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $review['user_rating'] ? 'text-yellow-400' : 'text-gray-200' ?> text-sm"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        <i class="far fa-clock mr-1"></i>
                        <?= date('F j, Y', strtotime($review['datetime'])) ?>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed"><?= htmlspecialchars($review['user_review']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    function highlightStars(num) {
        for (let i = 1; i <= 5; i++) {
            const star = document.querySelector(`label[for="star${i}"] i`);
            if (i <= num) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        }
    }

    function resetStars() {
        const checkedStar = document.querySelector('input[name="rating"]:checked');
        const checkedValue = checkedStar ? parseInt(checkedStar.value) : 0;
        highlightStars(checkedValue);
    }
    </script>
</body>
</html>