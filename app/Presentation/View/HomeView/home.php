<?php
$_title = 'Jom Meet';
require_once __DIR__ . '/../HomeView/header.php';
?>

<section class="text-center my-5 position-relative" style="min-height: 60vh;">
    <h1 class="fw-bold display-5 mb-4">JomMeet A NewFriend</h1>

    <!-- Spline 3D Object -->
    <div style="position: relative; height: 400px;">
        <spline-viewer
            url="https://prod.spline.design/VDuiMvkxF4DPHkAv/scene.splinecode"
            class="spline-model w-100 h-100"
            style="pointer-events: none;">
        </spline-viewer>
    </div>

    <div>
        <a href="/gathering" class="btn hero-btn px-4 py-2 mt-4">Let's get started</a>
    </div>
</section>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>