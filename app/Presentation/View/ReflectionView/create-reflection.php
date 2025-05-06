<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
$_title = 'Create Reflection';
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
            <h2 class="fw-bold mb-0 h5">Create Self-Reflection</h2>
        </div>
        <div class="col-2"></div>
    </div>

    <div class="container mb-5">
        <div class="container border border-2 rounded-3 m-2 align-content-center" style="background-color: rgba(222,236,255,68); border-color:#0077CC !important;">
            <div class="container d-flex align-item-center justify-content-center">
                <form method="post" action="/reflection/create" class="form" id="selfReflectionForm" style="width: 500px;">
                    <div class="my-3">
                        <h4 style="font-size: 25px;"><label for="date">Date</label></h4>
                        <input type="text" class="w-100 ps-2 bg-light-subtle border border-black border-2 rounded-3" id="reflectionDate" name="reflectionDate" value="<?= date("Y-m-d H:i") ?>" readonly>
                    </div>

                    <div class="my-3">
                        <h4 style="font-size: 25px;"><label for="title">Title</label></h4>
                        <input type="text" class="w-100 ps-2 border border-black border-2 rounded-3" id="reflectionTitle" name="reflectionTitle" placeholder="Write Your Self-Reflection Title Here!">
                        <div class="invalid-reflection text-danger" id="errorReflectionTitle"></div>
                    </div>

                    <div class="my-3">
                        <h4 style="font-size: 25px;"><label for="content">Content</label></h4>
                        <textarea class="w-100 ps-2 border border-black border-2 rounded-3" id="reflectionContent" name="reflectionContent" placeholder="Share how was your day!" style="height: 200px;"></textarea>
                        <div class="invalid-reflection text-danger" id="errorReflectionContent"></div>
                    </div>

                    <section class="d-flex justify-content-center my-3">
                        <button class="btn btn-primary rounded-pill" type="submit" style="width:300px;">Submit</button>
                    </section>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="/js/selfReflection.js"></script>
<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>