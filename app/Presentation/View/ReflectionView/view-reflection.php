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
        <div class="container border border-2 rounded-3 m-2 align-content-center" style="background-color: rgba(222,236,255,68); border-color:#0077CC !important;">
            <div class="container d-flex align-item-center justify-content-center">
                <div class="row">
                    <div class="my-3">
                        <h4>Reflection Id: <?php echo $reflectionViewed['selfreflectID']; ?></h4>
                    </div>

                    <div class="my-3">
                        <h4>Date: <?php echo htmlspecialchars(date('j F Y g:ia', strtotime($reflectionViewed['date']))); ?></h4>
                    </div>
                        
                    <div class="my-3">
                        <h4>Title: <?php echo $reflectionViewed['title']; ?></h4>
                    </div>

                    <div class="my-3">
                        <h4>Content: <?php echo $reflectionViewed['content']; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>