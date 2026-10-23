<?php
session_start();

// protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <!-- ✅ proper CSS link -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        /* Dashboard specific styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('dash.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }
        
        /* Optional: Add dark overlay for better text readability */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
        
        .top-bar {
            background: linear-gradient(135deg, #1a2980 0%, #26d0ce 100%);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .top-bar h2 {
            margin: 0;
            font-size: 24px;
        }
        
        .logout-btn {
            padding: 8px 20px;
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 80px auto;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            backdrop-filter: blur(5px);
            text-align: center;
        }
        
        .dashboard-container h1 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 36px;
        }
        
        .dashboard-container p {
            color: #666;
            margin-bottom: 40px;
            font-size: 18px;
        }
        
        .dashboard-cards {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 40px 60px;
            border-radius: 15px;
            font-size: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            min-width: 250px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }
        
        /* Different colors for each card */
        .card:first-child {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card:last-child {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                margin: 40px 20px;
                padding: 30px;
            }
            
            .dashboard-container h1 {
                font-size: 28px;
            }
            
            .card {
                padding: 30px 40px;
                font-size: 18px;
                min-width: 200px;
            }
        }
        
        @media (max-width: 480px) {
            .dashboard-container {
                margin: 20px 15px;
                padding: 20px;
            }
            
            .dashboard-container h1 {
                font-size: 24px;
            }
            
            .card {
                padding: 20px 30px;
                font-size: 16px;
                min-width: 100%;
            }
        }
    </style>
</head>

<body>

<!-- TOP BAR -->
<div class="top-bar">
    <h2>Patient Dashboard</h2>

    <!-- logout button -->
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<!-- DASHBOARD CONTENT -->
<div class="dashboard-container">

    <h1>Welcome to Your Dashboard</h1>
    <p>Manage your appointments easily below.</p>

    <div class="dashboard-cards">

        <!-- BOOK APPOINTMENT CARD -->
        <a href="appointment.php" class="card">
            📅 Book Appointment
        </a>

        <!-- VIEW (optional future feature) -->
        <a href="my_appointments.php" class="card">
            📋 My Appointments
        </a>

    </div>

</div>

</body>
</html>