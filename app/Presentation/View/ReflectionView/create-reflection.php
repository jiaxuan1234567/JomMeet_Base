<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
$_title = 'Create Reflection';
require_once __DIR__ . '/../HomeView/header.php';

$errors = $_SESSION['reflectionErrors'] ?? [];
$old = $_SESSION['old'] ?? [];

// Clear after use
unset($_SESSION['reflectionErrors']);
unset($_SESSION['old']);

?>


<div class="main d-flex flex-column min-vh-100">
    <div class="container-fluid py-3 d-flex align-items-center">
        <a href="/reflection" class="me-3">
            <i class="bi bi-arrow-left text-black" style="font-size: 1.55rem;"></i>
        </a>
        <h4 class="mb-0">Create Self-Reflection</h4>
    </div>
    <div class="container mb-5">
        <div class="container border border-2 rounded-3 m-2 align-content-center" style="background-color: rgba(222,236,255,68); border-color:#0077CC !important;">
            <div class="container d-flex align-item-center justify-content-center">
                <form method="post" action="/reflection/create" class="form">
                    <h1></h1>
                    <div class="my-3">
                        <h4 style="font-size: 30px;"><label for="date"><b>Date</b></label></h4>
                        <input type="text" class="ps-2 bg-light-subtle border border-black border-2 rounded-3"id="reflectionDate" name="reflectionDate" value="<?= date("Y-m-d H:i") ?>" style="width:500px;" readonly>
                    </div>
                    
                    <div class="my-3">
                        <h4 style="font-size: 30px;"><label for="title"><b>Title</b></label></h4>
                        <input type="text" class="ps-2 border border-black border-2 rounded-3" id="reflectionTitle" name="reflectionTitle" placeholder="Write Your Self-Reflection Title Here!" value="<?= htmlspecialchars($old['reflectionTitle'] ?? '') ?>" style="width:500px;">
                        <?php if (!empty($errors['reflectionTitle'])): ?>
                            <div class="text-danger"><?= $errors['reflectionTitle'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="my-3">
                        <h4 style="font-size: 30px;"><label for="content"><b>Content</b></label></h4>
                        <textarea class="ps-2 border border-black border-2 rounded-3" id="reflectionContent" name="reflectionContent" placeholder="Share how was your day!" style="width:500px;height:200px;"><?= htmlspecialchars($old['reflectionContent'] ?? '') ?></textarea>
                        <?php if (!empty($errors['reflectionContent'])): ?>
                            <div class="text-danger"><?= $errors['reflectionContent'] ?></div>
                        <?php endif; ?>
                    </div>

                    <section class="d-flex justify-content-center my-3">
                        <button class="btn btn-primary rounded-pill" type="submit" style="width:300px;">Submit</button>
                    </section>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>