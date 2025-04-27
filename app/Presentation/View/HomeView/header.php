<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Spline Viewer for 3D Model -->
    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.79/build/spline-viewer.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>

    <!-- Global CSS -->
    <link rel="stylesheet" href="<?php echo (new FileHelper('asset'))->getFilePath('AppCSS') ?>">

    <!-- Global JS -->
    <script src="/js/app.js"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/Presentation/View/HomeView/images/bubble.png">

    <!-- Tab Title -->
    <title><?php echo $_title ?? 'Jom Meet' ?></title>
</head>

<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light px-3 bg-blue-color">
            <a class="navbar-brand" href="#">
                <img src="<?php echo (new FileHelper('asset'))->getFilePath('iconPNG') ?>" alt="Logo" width="40" height="40" />
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link fw-bold" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="#">Reflection</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="/gathering">Gathering</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="/own">My Gathering</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-secondary rounded-pill">Profile</a>
                    <a href="#" class="btn btn-light rounded-pill">Log out</a>
                </div>
            </div>
        </nav>
    </header>
    <main>