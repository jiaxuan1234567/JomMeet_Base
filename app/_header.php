<!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>JomMeet</title>
            
            <!-- Jquery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="/js/app.js"></script>

            <!-- Spline Viewer for 3D Model -->
            <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.79/build/spline-viewer.js"></script>

            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

            <!-- External Stylesheet -->
            <link rel="stylesheet" href="<?php echo getRoutePath("AppCSS") ?>">
        </head>

        <body>
            <!-- Bootstrap JS -->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
            <header>
                <!-- Navigation Bar -->
                <nav class="navbar navbar-expand-lg navbar-light px-3">
                    <a class="navbar-brand" href="#">
                        <img src="<?php echo getRoutePath("iconPNG") ?>" alt="Logo" width="40" height="40" />
                    </a>
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link fw-bold" href="#">Home</a></li>
                            <li class="nav-item"><a class="nav-link fw-bold" href="#">Reflection</a></li>
                            <li class="nav-item"><a class="nav-link fw-bold" href="#">Gathering</a></li>
                            <li class="nav-item"><a class="nav-link fw-bold" href="#">My Gathering</a></li>
                        </ul>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-secondary rounded-pill">Profile</a>
                            <a href="#" class="btn btn-light rounded-pill">Log out</a>
                        </div>
                    </div>
                </nav>
            </header>