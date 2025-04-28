<?php
require '../_base.php';
$_title = 'Create Reflection';
require_once __DIR__ . '/../HomeView/header.php';
$GLOBALS['date'] = date("Y-m-d h:i:sa");
?>

<div class="main">
    <div class="container-fluid py-3">
        <h4>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
            </svg>
            Create Self-Reflection
        </h4>
    </div>
    <div class="container mb-5">
        <div class="container border border-2 rounded-3 m-2 align-content-center" style="background-color: rgba(222,236,255,68); border-color:#0077CC !important;">
            <div class="container d-flex align-item-center justify-content-center">
                <form method="post" class="form">
                    <div class="my-3">
                        <h4><label for="date">Date</label></h4>
                        <?= html_text('date', 'readonly style="width:500px;"') ?>

                    </div>
                    
                    <div class="my-3">
                        <h4><label for="title">Title</label></h4>
                        <?= html_text('title','maxlength="256" placeholder="Write Your Self-Reflection Title Here!" style="width:500px;"') ?>
                    </div>

                    <div class="my-3">
                    <h4><label for="content">Content</label></h4>
                    <?= html_textarea('title','placeholder="Share how was your day!" style="width:500px;height:200px;"') ?>
                    </div>

                    <section class="d-flex justify-content-center">
                        <button class="btn btn-primary rounded-pill" type="submit" style="width:300px;">Submit</button>
                    </section>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>