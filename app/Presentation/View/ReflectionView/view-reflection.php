<?php
$_title = 'Viewing Reflection';
require_once __DIR__ . '/../HomeView/header.php';


?>

<div class="main d-flex flex-column min-vh-100">
    <div class="container-fluid py-3 d-flex align-items-center">
        <a href="/reflection" class="me-3">
            <i class="bi bi-arrow-left text-black" style="font-size: 1.55rem;"></i>
        </a>
        <h4 class="mb-0">View Self-Reflection</h4>
    </div>

    <div class="container mb-5">
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
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>
