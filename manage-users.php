<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.html');
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "1234", "bazaar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle verification toggle
if (isset($_POST['toggle_verify'])) {
    $user_id = $_POST['user_id'];
    $current_status = $_POST['current_status'];
    $new_status = $current_status == 1 ? 0 : 1;
    
    $stmt = $conn->prepare("UPDATE login SET verify = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $user_id);
    $stmt->execute();
    $stmt->close();
    
    // Return JSON response for AJAX
    echo json_encode(['success' => true, 'new_status' => $new_status]);
    exit();
}

// Fetch user data with verification status
$query = "SELECT ud.*, l.verify 
          FROM userdata ud 
          LEFT JOIN login l ON ud.id = l.id 
          ORDER BY ud.created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusBazaaR - Manage Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #4A90E2;
            --secondary-color: #2ECC71;
            --dark-color: #2C3E50;
            --light-color: #F5F7FA;
            --gradient-start: #4A90E2;
            --gradient-end: #2ECC71;
        }

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .container {
            margin-top: 100px;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .page-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .card-body {
            padding: 2rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark-color);
            background: rgba(74, 144, 226, 0.05);
        }

        .table td {
            vertical-align: middle;
        }

        .btn-verify {
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }

        .btn-verify.verified {
            background: linear-gradient(45deg, var(--gradient-start), var(--gradient-end));
            border: none;
            color: white;
        }

        .btn-verify.unverified {
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .search-box {
            margin-bottom: 2rem;
        }

        .search-input {
            border-radius: 50px;
            padding: 1rem 1.5rem;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }

        .page-link {
            border: none;
            color: var(--dark-color);
            margin: 0 0.3rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-link:hover {
            background: var(--primary-color);
            color: white;
        }

        .user-info {
            font-size: 0.9rem;
        }

        .timestamp {
            font-size: 0.8rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .container {
                margin-top: 80px;
                padding: 1rem;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="#">
            <i class="fas fa-store mr-2"></i>CampusBazaaR Admin
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin-dashboard.php">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Manage Users</h1>
            <p>View and manage user accounts</p>
        </div>

        <div class="search-box">
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Search users...">
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User Info</th>
                                <th>Contact</th>
                                <th>Academic Details</th>
                                <th>Timestamps</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="user-info">
                                    <strong><?php echo htmlspecialchars($row['username']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($row['email']); ?></small>
                                </td>
                                <td class="user-info">
                                    <?php echo htmlspecialchars($row['phone_number']); ?><br>
                                    <small><?php echo htmlspecialchars($row['address']); ?></small>
                                </td>
                                <td class="user-info">
                                    <?php echo htmlspecialchars($row['course']); ?> - 
                                    <?php echo htmlspecialchars($row['branch']); ?><br>
                                    <small>Batch: <?php echo htmlspecialchars($row['batch']); ?> | 
                                    Adm: <?php echo htmlspecialchars($row['admission_number']); ?></small>
                                </td>
                                <td class="timestamp">
                                    Created: <?php echo date('M d, Y', strtotime($row['created_at'])); ?><br>
                                    Updated: <?php echo date('M d, Y', strtotime($row['updated_at'])); ?>
                                </td>
                                <td>
                                    <button class="btn btn-verify <?php echo $row['verify'] ? 'verified' : 'unverified'; ?>"
                                            onclick="toggleVerification(event, <?php echo $row['id']; ?>, <?php echo $row['verify']; ?>)">
                                        <?php echo $row['verify'] ? 'Verified' : 'Unverified'; ?>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <nav>
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function toggleVerification(event, userId, currentStatus) {
            $.ajax({
                url: 'manage-users.php',
                type: 'POST',
                data: {
                    toggle_verify: true,
                    user_id: userId,
                    current_status: currentStatus
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        const btn = event.target;  // Now `event.target` will be the clicked button
                        const newStatus = data.new_status;

                        // Update button text and toggle classes based on the new verification status
                        btn.textContent = newStatus ? 'Verified' : 'Unverified';
                        btn.classList.toggle('verified', newStatus);
                        btn.classList.toggle('unverified', !newStatus);
                    }
                }
            });
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');

            rows.forEach(function(row) {
                const username = row.cells[0].textContent.toLowerCase();
                if (username.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
