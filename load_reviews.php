<?php
require 'config.php';

$seller_email = 'amar@gmail.com';  // Example seller email
$query = $pdo->prepare("SELECT * FROM reviews WHERE seller_email = ? ORDER BY created_at DESC");
$query->execute([$seller_email]);

$reviews = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($reviews as $review) {
    echo "<div class='review-card'>";
    echo "<div class='review-header'>";
    echo "<div class='reviewer-avatar'><i class='fas fa-user'></i></div>";
    echo "<div class='review-meta'>";
    echo "<h5 class='reviewer-name'>" . htmlspecialchars($review['email']) . "</h5>";
    echo "<p class='review-date'>" . date('F j, Y', strtotime($review['created_at'])) . "</p>";
    echo "</div>";
    echo "</div>";
    echo "<p>" . htmlspecialchars($review['review']) . "</p>";
    echo "<div class='stars'>";
    for ($i = 1; $i <= 5; $i++) {
        $starClass = $i <= $review['rating'] ? 'text-warning' : '';
        echo "<i class='fa fa-star $starClass'></i>";
    }
    echo "</div>";
    echo "</div>";
}
