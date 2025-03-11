<?php
include 'connect.php';
$result = mysqli_query($conn, "SELECT * FROM damage_box ORDER BY created_at DESC");

// สำหรับการแบ่งหน้า
$records_per_page = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// คำสั่ง SQL สำหรับแบ่งหน้า
$query = "SELECT * FROM damage_box ORDER BY created_at DESC LIMIT $start_from, $records_per_page";
$result = mysqli_query($conn, $query);

// คำนวณจำนวนหน้าทั้งหมด
$total_records_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM damage_box");
$total_row = mysqli_fetch_assoc($total_records_query);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $records_per_page);

// สำหรับการกรอง
$filter_status = isset($_GET['status']) ? $_GET['status'] : 'all';
if ($filter_status != 'all') {
    $status_value = ($filter_status == 'accepted') ? 1 : 0;
    $query = "SELECT * FROM damage_box WHERE box_status = $status_value ORDER BY created_at DESC LIMIT $start_from, $records_per_page";
    $result = mysqli_query($conn, $query);
    
    // คำนวณจำนวนหน้าใหม่
    $total_records_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM damage_box WHERE box_status = $status_value");
    $total_row = mysqli_fetch_assoc($total_records_query);
    $total_records = $total_row['total'];
    $total_pages = ceil($total_records / $records_per_page);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Box Image Dashboard</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Prompt (Thai) -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Prompt', Arial, sans-serif;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #0a2463, #1e3a8a);
            padding: 2rem 0;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-title {
            font-weight: 600;
            letter-spacing: 0.5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .dashboard-subtitle {
            opacity: 0.9;
            font-weight: 300;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.07);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 20px;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .card-title {
            font-weight: 500;
            color: #333;
            margin-bottom: 0;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .image-preview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s;
            cursor: pointer;
        }
        
        .image-preview:hover {
            transform: scale(1.05);
        }
        
        .status-badge {
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .status-0 { 
            background-color: #fee2e2; 
            color: #b91c1c; 
        }
        
        .status-1 { 
            background-color: #dcfce7; 
            color: #15803d; 
        }
        
        .percentage-indicator {
            height: 10px;
            border-radius: 5px;
            background-color: #e5e7eb;
            overflow: hidden;
            margin-top: 5px;
        }
        
        .percentage-fill {
            height: 100%;
            background: linear-gradient(90deg, #4f46e5, #818cf8);
            border-radius: 5px;
        }
        
        .percentage-text {
            font-weight: 600;
            color: #4f46e5;
        }
        
        .table th {
            background-color: #f3f4f6;
            color: #4b5563;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table td {
            vertical-align: middle;
            padding: 15px 10px;
        }
        
        .table tr {
            border-bottom: 1px solid #f3f4f6;
        }
        
        .table tr:hover {
            background-color: #f9fafb;
        }
        
        .pagination .page-item .page-link {
            color: #4f46e5;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 0 3px;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #4f46e5;
            color: white;
        }
        
        .filter-btn {
            padding: 8px 16px;
            border-radius: 8px;
            margin-right: 5px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .filter-btn.active {
            background-color: #4f46e5;
            color: white;
        }
        
        .filter-btn:not(.active) {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
        }
        
        .stats-number {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .stats-label {
            color: #6b7280;
            font-size: 14px;
        }
        
        /* Modal styles for image preview */
        .modal-img {
            max-width: 100%;
            border-radius: 8px;
        }
        
        /* สำหรับรองรับการแสดงผลบนมือถือ */
        @media (max-width: 768px) {
            .table-responsive {
                border-radius: 12px;
                overflow: hidden;
            }
            
            .image-preview {
                width: 80px;
                height: 80px;
            }
            
            .stats-card {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="dashboard-header">
        <div class="container text-center">
            <h1 class="dashboard-title"><i class="fas fa-box-open me-2"></i>ระบบตรวจสอบความเสียหาย</h1>
            <p class="dashboard-subtitle">แสดงข้อมูลภาพถ่ายและสถานะของกล่องสินค้า</p>
        </div>
    </header>

    <div class="container">
        <!-- หน้าแรก/รายงานสรุป -->
        <div class="row mb-4">
            <?php
            // คำนวณสถิติสำหรับแดชบอร์ด
            $total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM damage_box");
            $accepted_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM damage_box WHERE box_status = 1");
            $rejected_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM damage_box WHERE box_status = 0");
            $avg_query = mysqli_query($conn, "SELECT AVG(box_percentage) as avg_percentage FROM damage_box");
            
            $total = mysqli_fetch_assoc($total_query)['total'];
            $accepted = mysqli_fetch_assoc($accepted_query)['total'];
            $rejected = mysqli_fetch_assoc($rejected_query)['total'];
            $avg_percentage = round(mysqli_fetch_assoc($avg_query)['avg_percentage'], 2);
            ?>
            
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stats-number"><?php echo $total; ?></div>
                    <div class="stats-label">จำนวนทั้งหมด</div>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number"><?php echo $accepted; ?></div>
                    <div class="stats-label">ยอมรับ</div>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stats-number"><?php echo $rejected; ?></div>
                    <div class="stats-label">ปฏิเสธ</div>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stats-number"><?php echo $avg_percentage; ?>%</div>
                    <div class="stats-label">เปอร์เซ็นต์เฉลี่ย</div>
                </div>
            </div>
        </div>
        
        <!-- ตัวกรองข้อมูล -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-3 mb-md-0"><i class="fas fa-filter me-2"></i>กรองข้อมูล</h5>
                    <div>
                        <a href="?status=all" class="btn filter-btn <?php echo $filter_status == 'all' ? 'active' : ''; ?>">
                            ทั้งหมด
                        </a>
                        <a href="?status=accepted" class="btn filter-btn <?php echo $filter_status == 'accepted' ? 'active' : ''; ?>">
                            <i class="fas fa-check-circle me-1"></i>ยอมรับ
                        </a>
                        <a href="?status=rejected" class="btn filter-btn <?php echo $filter_status == 'rejected' ? 'active' : ''; ?>">
                            <i class="fas fa-times-circle me-1"></i>ปฏิเสธ
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ตารางข้อมูล -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title"><i class="fas fa-table me-2"></i>ข้อมูลกล่องสินค้า</h5>
                <span class="badge bg-primary"><?php echo $total_records; ?> รายการ</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>รหัส</th>
                                <th>รูปภาพ</th>
                                <th>เปอร์เซ็นต์</th>
                                <th>สถานะ</th>
                                <th>วันที่อัปโหลด</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">#<?php echo $row['boxID']; ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $imageData = base64_encode($row['box_image']);
                                            $imageSrc = "data:image/jpeg;base64," . $imageData;
                                            ?>
                                            <img src="<?php echo $imageSrc; ?>" class="image-preview" alt="Box Image" 
                                                 data-bs-toggle="modal" data-bs-target="#imageModal<?php echo $row['boxID']; ?>">
                                        </td>
                                        <td>
                                            <div class="percentage-text"><?php echo $row['box_percentage']; ?>%</div>
                                            <div class="percentage-indicator">
                                                <div class="percentage-fill" style="width: <?php echo $row['box_percentage']; ?>%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo $row['box_status']; ?>">
                                                <?php if ($row['box_status'] == 1): ?>
                                                    <i class="fas fa-check-circle"></i> ยอมรับ
                                                <?php else: ?>
                                                    <i class="fas fa-times-circle"></i> ปฏิเสธ
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                                $date = new DateTime($row['created_at']);
                                                echo $date->format('d/m/Y H:i'); 
                                            ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" data-bs-target="#imageModal<?php echo $row['boxID']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    <!-- Modal for image preview -->
                                    <div class="modal fade" id="imageModal<?php echo $row['boxID']; ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">รายละเอียดกล่อง #<?php echo $row['boxID']; ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="<?php echo $imageSrc; ?>" class="modal-img img-fluid" alt="Box Image">
                                                    <div class="mt-3">
                                                        <p class="mb-1"><strong>เปอร์เซ็นต์ความเสียหาย:</strong> <?php echo $row['box_percentage']; ?>%</p>
                                                        <p class="mb-1"><strong>สถานะ:</strong> 
                                                            <span class="status-badge status-<?php echo $row['box_status']; ?>">
                                                                <?php echo ($row['box_status'] == 1) ? "ยอมรับ ✅" : "ปฏิเสธ ❌"; ?>
                                                            </span>
                                                        </p>
                                                        <p class="mb-1"><strong>วันที่อัปโหลด:</strong> <?php echo $row['created_at']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-info-circle me-2"></i>ไม่พบข้อมูล
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page-1; ?>&status=<?php echo $filter_status; ?>" aria-label="Previous">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $filter_status; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page+1; ?>&status=<?php echo $filter_status; ?>" aria-label="Next">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="mt-5 mb-4 text-center text-muted">
            <p>&copy; <?php echo date('Y'); ?> Box Image Dashboard | ระบบตรวจสอบความเสียหายกล่องสินค้า</p>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>