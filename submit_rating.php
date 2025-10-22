<?php
session_start();

try {
    // Establish database connection
    $connect = new PDO("mysql:host=localhost;dbname=bazaar", "root", "1234");
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle review submission
    if (isset($_POST["rating_data"]) && isset($_POST["user_review"]) && isset($_POST["seller_email"])) {
        // Get the logged-in user's email from session
        $ratin_user = $_SESSION['email'];  // The logged-in user's email
        $rated_user = $_POST['seller_email'];   // Seller's email passed from the front-end

        // Sanitize inputs
        $user_review = htmlspecialchars($_POST["user_review"]);

        // Check if rating is valid (1 to 5)
        $user_rating = intval($_POST["rating_data"]);
        if ($user_rating < 1 || $user_rating > 5) {
            echo json_encode(['error' => 'Invalid rating. Rating should be between 1 and 5.']);
            exit;
        }

        // Prepare the data for the insertion
        $data = array(
            ':ratin_user'      => $ratin_user,
            ':rated_user'      => $rated_user,
            ':user_rating'     => $user_rating,
            ':user_review'     => $user_review
            
        );

        // Insert the review into the database
        $query = "
        INSERT INTO review_table 
        (ratin_user, rated_user, user_rating, user_review) 
        VALUES (:ratin_user, :rated_user, :user_rating, :user_review)
        ";

        $statement = $connect->prepare($query);
       
        try {
            $statement->execute($data);
            echo json_encode(['success' => true, 'message' => 'Your review and rating have been successfully submitted!']);
            exit;
        } catch (PDOException $e) {
            // Catch SQL errors and return an appropriate error message for review submission
            echo json_encode(['error' => 'Database error occurred while submitting your review: ' . $e->getMessage()]);
            exit;
        }
    }

    // Handle fetching reviews for a specific seller
    if (isset($_POST["action"]) && $_POST["action"] == "load_reviews" && isset($_POST["seller_email"])) {
        $seller_email = $_POST["seller_email"];
        $review_content = array();
        $summary = array(
            'total' => 0,
            'average' => 0,
            'five_star' => 0,
            'four_star' => 0,
            'three_star' => 0,
            'two_star' => 0,
            'one_star' => 0
        );

        // Query reviews for a specific seller
        $query = "SELECT * FROM review_table WHERE rated_user = :rated_user ORDER BY id DESC";
        $stmt = $connect->prepare($query);
        $stmt->bindParam(':rated_user', $seller_email, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            // Catch SQL errors and return an appropriate error message
            echo json_encode(['error' => 'Database error occurred while fetching reviews: ' . $e->getMessage()]);
            exit;
        }

        $reviews = array();

        // Fetch and display reviews
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Calculate the review summary
            $summary['total']++;
            $rating = $row['user_rating'];
            $summary[$rating . '_star']++;

            // Add review to the output
            $reviews[] = "<div class='user-review-card'>
                            <div class='card'>
                                <div class='card-header'>" . htmlspecialchars($row['ratin_user']) . "</div>
                                <div class='card-body'>
                                    <p>" . htmlspecialchars($row['user_review']) . "</p>
                                    <div class='review-date'>" . date('F j, Y, g:i a', $row["datetime"]) . "</div>
                                </div>
                            </div>
                          </div>";
        }

        // Calculate average rating if there are reviews
        if ($summary['total'] > 0) {
            $summary['average'] = round(($summary['five_star'] * 5 + $summary['four_star'] * 4 + $summary['three_star'] * 3 + $summary['two_star'] * 2 + $summary['one_star'] * 1) / $summary['total'], 2);
        }

        // Return reviews and summary
        echo json_encode([
            'review_data' => implode('', $reviews),
            'summary' => $summary
        ]);
        exit;
    }

    // Default case: Return error if no valid action is found
    echo json_encode(['error' => 'Invalid request']);
    exit;

} catch (PDOException $e) {
    // Catch any general PDO exceptions (database connection issues, query issues, etc.)
    echo json_encode(['error' => 'Database error occurred: ' . $e->getMessage()]);
    exit;
}
