<?php

require_once './fileRegister.php';

class HomeView
{
    public function index()
    {
        $this->header();
        $this->render();
        $this->footer();
    }

    public function render()
    {
?>
        <section class="text-center my-5">
            <h1 class="fw-bold display-5 mb-4">JomMeet A NewFriend</h1>
            <img src="mascot.png" alt="Mascot" class="img-fluid mb-4" style="max-width: 250px;" />
            <div>
                <a href="#" class="btn hero-btn px-4 py-2">Let's get started</a>
            </div>
        </section>
    <?php
    }

    public function header()
    {
    ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>JomMeet</title>

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
                            <li class="nav-item"><a class="nav-link fw-bold text-primary" href="#">My Gathering</a></li>
                        </ul>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-secondary rounded-pill">Profile</a>
                            <a href="#" class="btn btn-light rounded-pill">Log out</a>
                        </div>
                    </div>
                </nav>
            </header>
        <?php
    }

    public function footer()
    {
        ?>
        </body>
        <!-- Footer -->
        <footer>
            <div class="footer-top">
                <div class="container">
                    <div class="row text-start">
                        <div class="col-md-3">
                            <img src="logo.png" alt="Logo" width="50" />
                            <h5 class="mt-2">JomMeet</h5>
                        </div>
                        <div class="col-md-3 footer-link">
                            <h6>Home</h6>
                            <a href="#">Reflection</a>
                            <a href="#">Gathering</a>
                            <a href="#">My Gathering</a>
                        </div>
                        <div class="col-md-3 footer-link">
                            <h6>About Us</h6>
                            <a href="#">About Us</a>
                            <a href="#">Contact Us</a>
                        </div>
                        <div class="col-md-3 footer-link">
                            <h6>Help</h6>
                            <a href="#">FAQ</a>
                            <a href="#">Terms of use</a>
                            <a href="#">Private Policy</a>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12 text-end">
                            <p class="mb-0">JomMeet Sdn. Bhd.<br>
                                Unit C, Level 10<br>
                                Menara KLCC<br>
                                No. 5, Jalan Bumi 1,<br>
                                59000 Kuala Lumpur<br>
                                03-1000-1111<br>
                                malaysiajommeet@gmail.com
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center py-2">
                © 2025 JomMeet (Malaysia) Sdn. Bhd. Reg. No: 200901018343 (081640-H) (AJL931760).
            </div>
        </footer>
<?php
    }
}
