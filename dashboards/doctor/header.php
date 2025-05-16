<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Clinic Management System'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS - Combined file -->
    <link rel="stylesheet" href="css/main.css">
    
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css_file): ?>
            <link rel="stylesheet" href="<?php echo $css_file; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Loading Overlay -->
    <div id="loading-overlay">
        <div class="loading-spinner">
            <div class="heartbeat-line">
                <svg viewBox="0 0 600 100" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0,50 L100,50 L150,20 L200,80 L250,20 L300,80 L350,50 L400,50 L450,20 L500,80 L550,50 L600,50" fill="none" stroke="#0077cc" stroke-width="3" />
                </svg>
            </div>
            <p>Loading...</p>
        </div>
    </div>
</body>
<body>
    <div class="dashboard-container">