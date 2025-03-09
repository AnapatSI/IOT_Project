<?php
include 'connect.php';
$result = mysqli_query($conn, "SELECT * FROM SensorData1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Data Dashboard</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-bg: #f8f9fa;
            --card-bg: #ffffff;
            --accent-color: #6366f1;
            --hover-bg: #f1f5f9;
            --border-radius: 1rem;
            --box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: #1f2937;
            line-height: 1.6;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--accent-color), #818cf8);
            padding: 2rem 0;
            margin-bottom: 2rem;
            color: white;
        }

        .table-container {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }

        .custom-table td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.95rem;
        }

        .custom-table tbody tr:hover {
            background-color: var(--hover-bg);
        }

        .sensor-value {
            font-weight: 500;
            color: var(--accent-color);
        }

        .search-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 0.95rem;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            .table-container {
                padding: 1rem;
            }
            
            .custom-table th,
            .custom-table td {
                padding: 0.75rem;
            }
            
            .dashboard-header {
                padding: 1.5rem 0;
            }
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #64748b;
        }

        /* Pagination styling */
        .pagination {
            margin-top: 1.5rem;
        }

        .page-link {
            color: var(--accent-color);
            border-radius: 0.375rem;
            margin: 0 0.25rem;
        }

        .page-link:hover {
            background-color: var(--accent-color);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="dashboard-header">
        <div class="container">
            <h1 class="h3 mb-0">Sensor Data Dashboard</h1>
            <!-- <p class="mb-0 opacity-75">Real-time monitoring and analysis</p> -->
        </div>
    </header>

    <div class="container">
        <!-- Search Bar
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Search sensor data...">
        </div> -->

        <!-- Table Section -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sensor DHT22</th>
                            <th>Soil Moisture Sensor</th>
                            <th>Ultrasonic Sensor</th>
                            <!-- <th>Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Assuming $result is the query result from database
                        if ($result && mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_array($result)) : 
                        ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td class="sensor-value"><?php echo $row['value1']; ?></td>
                                <td class="sensor-value"><?php echo $row['value2']; ?></td>
                                <td class="sensor-value"><?php echo $row['value3']; ?></td>
                                <!-- <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </td> -->
                            </tr>
                        <?php 
                            endwhile;
                        } else {
                        ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-database fa-3x mb-3"></i>
                                        <h5>No sensor data available</h5>
                                        <p>Check your sensor connections or try again later.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination
            <nav aria-label="Page navigation" class="d-flex justify-content-center">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav> -->
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple search functionality
        document.querySelector('.search-input').addEventListener('keyup', function(e) {
            const searchText = e.target.value.toLowerCase();
            const tableRows = document.querySelectorAll('.custom-table tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });
    </script>
</body>
</html>


