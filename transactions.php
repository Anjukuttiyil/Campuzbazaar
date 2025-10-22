<?php
                    session_start();  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Transactions - CampusBazaaR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --text-light: #ecf0f1;
            --hover-color: #2980b9;
            --background-color: #f5f6fa;
            --card-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--background-color);
            min-height: 100vh;
        }

        /* Navbar Styles */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            padding: 0.8rem 2rem;
            box-shadow: var(--card-shadow);
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            font-size: 1.5rem;
            font-weight: 600;
            transition: transform 0.3s ease;
        }

        .brand:hover {
            transform: translateY(-2px);
        }

        .brand i {
            font-size: 1.8rem;
            margin-right: 0.8rem;
            color: var(--secondary-color);
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-item {
            position: relative;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-light);
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .nav-link.active {
            background-color: var(--secondary-color);
            color: white;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-light);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Main Content Styles */
        .main-content {
            margin-top: 80px;
            padding: 2rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .page-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .page-title {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
            padding-bottom: 0.5rem;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--secondary-color);
            border-radius: 2px;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions-table th {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 1rem;
            text-align: left;
            font-weight: 500;
        }

        .transactions-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        .transactions-table tr:last-child td {
            border-bottom: none;
        }

        .transactions-table tr:hover td {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .btn-view {
            background-color: var(--secondary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background-color: var(--hover-color);
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--primary-color);
                padding: 1rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .nav-links.active {
                display: flex;
            }

            .main-content {
                padding: 1rem;
                margin-top: 70px;
            }

            .table-container {
                padding: 1rem;
            }

            .transactions-table {
                font-size: 0.9rem;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }

        /* Responsive table */
        @media (max-width: 600px) {
            .table-container {
                overflow-x: auto;
            }
            
            .transactions-table th,
            .transactions-table td {
                min-width: 120px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="brand">
                <i class="fas fa-store"></i>
                CampusBazaaR
            </a>

            <button class="mobile-toggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="nav-links">
                <li class="nav-item">
                    <a href="sellmenu.html" class="nav-link">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                </li>
               
               
                <li class="nav-item">
                    <a href="about.html" class="nav-link">
                        <i class="fas fa-info-circle"></i>
                        About
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Your Transactions</h1>
        </div>

        <div class="table-container">
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag mr-2"></i> Transaction ID</th>
                        <th><i class="fas fa-box mr-2"></i> Product ID</th>
                        <th><i class="fas fa-dollar-sign mr-2"></i> Amount</th>
                        <th><i class="fas fa-user-tie mr-2"></i> Seller</th>
                        <th><i class="fas fa-user mr-2"></i> Buyer</th>
                        <th><i class="fas fa-cogs mr-2"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                  

                    if (!isset($_SESSION['email'])) {
                        echo '<tr>
                                <td colspan="6" class="empty-state">
                                    <i class="fas fa-user-lock"></i>
                                    <p>You need to log in to view your transactions.</p>
                                </td>
                            </tr>';
                        exit;
                    }

                    $userEmail = $_SESSION['email'];
                    $servername = "localhost";
                    $username = "root";
                    $password = "1234";
                    $dbname = "bazaar";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $stmt = $conn->prepare("SELECT id, product_id, payment_id, amount, seller, buyer FROM payments WHERE buyer = ? OR seller = ?");
                    $stmt->bind_param("ss", $userEmail, $userEmail);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['payment_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['product_id']) . '</td>';
                            echo '<td>$' . number_format($row['amount'], 2) . '</td>';
                            echo '<td>' . htmlspecialchars($row['seller']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['buyer']) . '</td>';
                            echo '<td>
                                    <a href="generate-pdf.php?id=' . htmlspecialchars($row['payment_id']) . '" class="btn-view">
                                        <i class="fas fa-eye"></i>
                                        View Details
                                    </a>
                                  </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr>
                                <td colspan="6" class="empty-state">
                                    <i class="fas fa-receipt"></i>
                                    <p>You have no transaction history yet.</p>
                                </td>
                              </tr>';
                    }

                    $stmt->close();
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function toggleMenu() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
        }

        // Add active class to current nav item
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>