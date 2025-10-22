<?php
session_start();

// Database connection (update with your credentials)
$db = new PDO('mysql:host=localhost;dbname=bazaar', 'root', '1234');

// Check if the user is allowed to post a review
$ratin_user = $_SESSION['email'];  // Corrected session variable

/// Fetch the average rating of the rated_user if there are reviews
$avg_rating_stmt = $db->prepare("SELECT AVG(user_rating) as avg_rating FROM review_table WHERE rated_user = ?");
$avg_rating_stmt->execute([$ratin_user]);
$avg_rating = $avg_rating_stmt->fetch(PDO::FETCH_ASSOC)['avg_rating'] ?? 0;

// Fetch existing reviews for the rated_user
$reviews_stmt = $db->prepare("SELECT * FROM review_table WHERE rated_user = ? ORDER BY id DESC");
$reviews_stmt->execute([$ratin_user]);
$reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Reviews | CampusBazaaR</title>
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

        .stats-card {
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
        <!-- Seller Info and Rating Summary -->
        <div class="max-w-3xl mx-auto mb-12">
            <div class="stats-card rounded-2xl shadow-xl p-8 mb-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Seller Info -->
                    <div class="text-center md:text-left">
                        <h1 class="text-2xl font-bold text-gray-800 mb-2">
                            <?= htmlspecialchars($ratin_user) ?>
                        </h1>
                        <p class="text-gray-600 mb-4">Seller Profile</p>
                        <div class="inline-flex items-center bg-indigo-50 rounded-full px-4 py-2">
                            <i class="fas fa-comments text-indigo-600 mr-2"></i>
                            <span class="text-indigo-600 font-medium">
                                <?= count($reviews) ?> reviews
                            </span>
                        </div>
                    </div>

                    <!-- Rating Summary -->
                    <div class="text-center md:text-right">
                        <div class="flex flex-col items-center md:items-end">
                            <div class="text-5xl font-bold text-indigo-600 mb-2">
                                <?= number_format($avg_rating, 1) ?>
                            </div>
                            <div class="flex gap-1 mb-3">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= round($avg_rating) ? 'text-yellow-400' : 'text-gray-200' ?> text-xl"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="text-sm text-gray-500">
                                Average rating based on <?= count($reviews) ?> reviews
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold mb-8 text-gray-800">Customer Reviews</h2>
            
            <?php if (empty($reviews)): ?>
            <div class="bg-gray-50 rounded-xl p-8 text-center">
                <i class="fas fa-star text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600">No reviews yet for this seller.</p>
            </div>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                <div class="review-card bg-white rounded-xl shadow-md p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <!-- User Avatar -->
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                <?= strtoupper(substr($review['ratin_user'], 0, 1)) ?>
                            </div>
                            <!-- User Info and Rating -->
                            <div>
                                <h4 class="font-semibold text-gray-800">
                                    <?= htmlspecialchars($review['ratin_user']) ?>
                                </h4>
                                <div class="flex gap-1 mt-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $review['user_rating'] ? 'text-yellow-400' : 'text-gray-200' ?> text-sm"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Review Date -->
                        <div class="text-sm text-gray-500">
                            <i class="far fa-clock mr-1"></i>
                            <?= date('F j, Y', strtotime($review['datetime'])) ?>
                        </div>
                    </div>
                    <!-- Review Content -->
                    <p class="text-gray-700 leading-relaxed">
                        <?= htmlspecialchars($review['user_review']) ?>
                    </p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>