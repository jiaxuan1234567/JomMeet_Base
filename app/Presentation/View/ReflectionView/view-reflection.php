<?php
$_title = 'Viewing Reflection';
require_once __DIR__ . '/../HomeView/header.php';


?>

<div class="main d-flex flex-column min-vh-100">
    <div class="container-fluid py-3">
        <h4>
            <a href="/reflection">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                </svg>
            </a>
            View Self-Reflection
        </h4>
    </div>

    <div class="container mb-5">
        <?php if (!$reflectionViewed): ?>
            <section class="text-center my-5">
                <!-- Spline 3D Object -->
                <spline-viewer url="https://prod.spline.design/VDuiMvkxF4DPHkAv/scene.splinecode" class="spline-model"></spline-viewer>
                <div>
                    <h4 class="fw-bold">Sorry, the reflection you are looking for was not found.</h4>
                    <a href="/reflection" class="btn hero-btn px-4 py-2">Go Back Reflection List Page</a>
                </div>
            </section>
        <?php else: ?>
            <div class="container border border-2 rounded-3 m-2 align-content-center" style="background-color: rgba(222,236,255,68); border-color:#0077CC !important;">
                <div class="container d-flex align-items-center">
                    <div class="row">
                        <div class="my-3">
                            <h5 class="ps-2" style="font-size: 25px; color:rgb(135, 135, 135)">
                                <?php echo htmlspecialchars(date('l, j F Y g:iA', strtotime($reflectionViewed['date']))); ?>
                            </h5>
                        </div>
                        <div class="mb-3">
                            <h4 class="ps-2" style="font-size: 40px;"><b>Title: </b></h4>
                            <p class="ps-5" style="font-size: 30px; letter-spacing: -2px; color:rgb(86, 86, 86)">
                                <?php echo $reflectionViewed['title']; ?>
                            </p>
                        </div>
                        <div class="mb-3">
                            <h4 class="ps-2" style="font-size: 40px;"><b>Content: </b></h4>
                            <p class="ps-5" style="font-size: 30px; letter-spacing: -2px; color:rgb(86, 86, 86)">
                                <?php echo $reflectionViewed['content']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>
