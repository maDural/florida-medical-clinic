<?php
session_start();
include 'includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Handle appointment status update
if (isset($_POST['update_status'])) {
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $appointment_type = mysqli_real_escape_string($conn, $_POST['appointment_type']);
    
    if ($appointment_type == 'in_person') {
        $query = "UPDATE appointments SET status = '$status' WHERE id = '$appointment_id'";
    } else {
        $query = "UPDATE telehealth_appointments SET status = '$status' WHERE id = '$appointment_id'";
    }
    
    if ($conn->query($query)) {
        $success = "Appointment status updated successfully!";
    } else {
        $error = "Error updating status: " . $conn->error;
    }
}

// Fetch all appointments
$in_person_query = "SELECT * FROM appointments ORDER BY appointment_date DESC, appointment_time ASC";
$in_person_result = $conn->query($in_person_query);

$telehealth_query = "SELECT * FROM telehealth_appointments ORDER BY appointment_date DESC, appointment_time ASC";
$telehealth_result = $conn->query($telehealth_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Florida Medical Clinic</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        
        /* Top Bar */
        .top-bar {
            background: linear-gradient(135deg, #1a2980 0%, #26d0ce 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .top-bar h1 {
            font-size: 24px;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        
        .logout-btn:hover {
            background: #c0392b;
        }
        
        /* Stats Section */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        
        /* Appointments Section */
        .appointments-section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px 30px;
        }
        
        .section-title {
            font-size: 24px;
            color: #2c3e50;
            margin: 20px 0;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
        }
        
        .appointment-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-completed {
            background: #cce5ff;
            color: #004085;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        select {
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        button:hover {
            background: #764ba2;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .appointment-table {
                overflow-x: auto;
            }
            
            .stats-container {
                padding: 20px;
            }
            
            .appointments-section {
                padding: 0 20px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <h1>👨‍⚕️ Admin Dashboard - Florida Medical Clinic</h1>
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
    
    <?php
    // Calculate statistics
    $total_in_person = $in_person_result->num_rows;
    $total_telehealth = $telehealth_result->num_rows;
    $pending_in_person = 0;
    $confirmed_in_person = 0;
    
    if ($in_person_result) {
        $in_person_result->data_seek(0);
        while ($row = $in_person_result->fetch_assoc()) {
            if ($row['status'] == 'pending') $pending_in_person++;
            if ($row['status'] == 'confirmed') $confirmed_in_person++;
        }
        $in_person_result->data_seek(0);
    }
    ?>
    
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_in_person + $total_telehealth; ?></div>
            <div class="stat-label">Total Appointments</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $pending_in_person; ?></div>
            <div class="stat-label">Pending Approval</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $confirmed_in_person; ?></div>
            <div class="stat-label">Confirmed</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_telehealth; ?></div>
            <div class="stat-label">Telehealth Requests</div>
        </div>
    </div>
    
    <div class="appointments-section">
        <?php if (isset($success)): ?>
            <div class="success">✅ <?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        
        <h2 class="section-title">🏥 In-Person Appointments</h2>
        <div class="appointment-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Department</th>
                        <th>Doctor</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($in_person_result && $in_person_result->num_rows > 0): ?>
                        <?php while ($row = $in_person_result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['fullname'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                                <td><?php echo date('g:i A', strtotime($row['appointment_time'])); ?></td>
                                <td><?php echo ucfirst(str_replace('_', ' ', $row['department'] ?? 'N/A')); ?></td>
                                <td><?php echo ucfirst(str_replace('_', ' ', $row['doctor'] ?? 'N/A')); ?></td>
                                <td><?php echo substr(htmlspecialchars($row['reason'] ?? ''), 0, 50); ?>...</td>
                                <td>
                                    <span class="status-badge status-<?php echo $row['status'] ?? 'pending'; ?>">
                                        <?php echo ucfirst($row['status'] ?? 'Pending'); ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" style="display: flex; gap: 5px;">
                                        <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="appointment_type" value="in_person">
                                        <select name="status">
                                            <option value="pending" <?php echo ($row['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo ($row['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirm</option>
                                            <option value="completed" <?php echo ($row['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                            <option value="cancelled" <?php echo ($row['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancel</option>
                                        </select>
                                        <button type="submit" name="update_status">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" style="text-align: center;">No in-person appointments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <h2 class="section-title">💻 Telehealth Appointments</h2>
        <div class="appointment-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($telehealth_result && $telehealth_result->num_rows > 0): ?>
                        <?php while ($row = $telehealth_result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['fullname'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                                <td><?php echo date('g:i A', strtotime($row['appointment_time'])); ?></td>
                                <td><?php echo substr(htmlspecialchars($row['reason'] ?? ''), 0, 50); ?>...</td>
                                <td>
                                    <span class="status-badge status-<?php echo $row['status'] ?? 'pending'; ?>">
                                        <?php echo ucfirst($row['status'] ?? 'Pending'); ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" style="display: flex; gap: 5px;">
                                        <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="appointment_type" value="telehealth">
                                        <select name="status">
                                            <option value="pending" <?php echo ($row['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo ($row['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirm</option>
                                            <option value="completed" <?php echo ($row['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                            <option value="cancelled" <?php echo ($row['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancel</option>
                                        </select>
                                        <button type="submit" name="update_status">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center;">No telehealth appointments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>