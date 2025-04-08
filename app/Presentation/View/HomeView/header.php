<?php
require_once "./fileRegister.php"
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JomMeet</title>

    <!-- Spline Viewer for 3D Model -->
    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.79/build/spline-viewer.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <!-- External Stylesheet -->
    <link rel="stylesheet" href="<?php echo getRoutePath("AppCSS") ?>">
    <link rel="stylesheet" href="<?php echo getRoutePath("StylesCSS") ?>">
</head>

<body>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
    <header>
        <!-- Navigation Bar -->
        <nav>
            <img src="<?php echo getRoutePath("iconPNG") ?>" alt="JomMeet Logo" class="bubble" width="80" height="80">
            <a href="index.php" class="mainpage">JomMeet</a>
            <div class="nav-links">
                <a href="gathering.php">Gathering</a>
                <a href="about.php">About Us</a>
                <a href="help.php">Help</a>
                <button class="accountbtn"><a href="account.php">Log In</a></button>
                <button class="accountbtn"><a href="signup.php">Sign Up</a></button>
            </div>
        </nav>
    </header>