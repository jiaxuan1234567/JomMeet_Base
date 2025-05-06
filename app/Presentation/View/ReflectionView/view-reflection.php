<?php
$_title = 'Viewing Reflection';
require_once __DIR__ . '/../HomeView/header.php';
?>

<div class="main d-flex flex-column min-vh-100">
    <div class="d-flex mb-4 align-items-center border-bottom border-2 px-2 py-3" style="margin-bottom: 2rem !important;">
        <div class="col-2">
            <a href="/reflection">
                <i class="bi bi-arrow-left text-black h3 m-0" style="cursor: pointer;"></i>
            </a>
        </div>
        <div class="col-8 text-center">
            <h2 class="fw-bold mb-0 h5">View Self-Reflection</h2>
        </div>
        <div class="col-2"></div>
    </div>

    <div class="container mb-5">
        <div class="container border border-2 rounded-3 m-2 align-content-center" style="background-color: rgba(222,236,255,68); border-color:#0077CC !important;">
            <div class="container d-flex align-items-center">
                <div class="row">
                    <div class="my-3">
                        <h5 class="ps-2" style="font-size: 18px; color:rgb(135, 135, 135)">
                            <?php echo htmlspecialchars(date('l, j F Y g:iA', strtotime($reflectionViewed['date']))); ?>
                        </h5>
                    </div>
                    <div class="mb-3">
                        <h4 class="ps-2" style="font-size: 25px;"><b>Title: </b></h4>
                        <p class="ps-5" style="font-size: 20px; color:rgb(86, 86, 86)">
                           <?php echo $reflectionViewed['title']; ?>
                        </p>
                    </div>
                    <div class="mb-3">
                        <h4 class="ps-2" style="font-size: 25px;"><b>Content: </b></h4>
                        <p class="ps-5" style="font-size: 20px; color:rgb(86, 86, 86)">
                            <?php echo $reflectionViewed['content']; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>
