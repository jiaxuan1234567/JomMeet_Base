<?php
$_title = 'Jom Meet';
require_once __DIR__ . '/../HomeView/header.php';
?>

<section class="text-center my-5" style="height: 600px;">
    <h1 class="fw-bold display-5 mb-4">JomMeet A NewFriend</h1>
    <!-- Spline 3D Object -->
    <spline-viewer url="https://prod.spline.design/VDuiMvkxF4DPHkAv/scene.splinecode" class="spline-model"></spline-viewer>
    <div>
        <a href="/gathering" class="btn hero-btn px-4 py-2">Let's get started</a>
    </div>
</section>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>