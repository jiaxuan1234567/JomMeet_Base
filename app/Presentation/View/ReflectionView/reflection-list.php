<?php
$_title = 'My Gathering';
require_once __DIR__ . '/../HomeView/header.php';
?>



<div class="main d-flex flex-column" style="min-height: 70vh;">
    <?php if (!empty($_SESSION['flash_message'])): ?>
        <div id="flashMessage" class="flash-message" data-type="<?= $_SESSION['flash_type'] ?? '' ?>" data-msg="<?= $_SESSION['flash_message'] ?>">
        </div>
        <?php unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']) ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mt-5 mb-4 px-6" style="padding-left: 3rem; padding-right: 6rem;">
        <h2 class="fw-bold" style="padding-left: 2rem;">Self-Reflection</h2>
        <a href="/reflection/create" class="btn btn-outline-dark d-flex align-items-center py-1 px-2 rounded">
            <span class="d-inline-block bg-dark text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 30px; height: 30px;">
                <i class="bi bi-plus" style="font-size: 1.25rem;"></i>
            </span>
            <span class="fw-bold me-1">Create</span>
        </a>
    </div>

    <div class="container">
        <div class="row">
            <div class="container">
                <?php if (!empty($reflections)): ?>
                    <?php foreach ($reflections as $reflection): ?>
                        <div class="row m-1 container border border-2 rounded-3 my-3" style="background-color: rgba(222,236,255,68); border-color:#0077CC !important;">
                            <div class="col-md-10">
                                <a href="/reflection/view/<?= $reflection['selfreflectID'] ?>" class="text-decoration-none text-reset">
                                    <div class="card my-4 col-md-4" style="background-color: rgb(86,159,255);">
                                        <h4 class="card-content font-weight-medium m-2 align-self-center" style="font-size: 18px;"><?php echo htmlspecialchars(date('j F Y g:ia', strtotime($reflection['date']))); ?></h4>
                                    </div>
                                    <div class="mb-3">
                                        <h3 class="ps-3" style="font-size: 25px; "><?php echo htmlspecialchars($reflection['title']); ?></h3>
                                    </div>
                                    <div class="mb-4">
                                        <p class="ps-3" style="font-size: 18px; line-height: 1.22;">
                                            <?php
                                            $words = explode(' ', strip_tags($reflection['content']));
                                            $preview = implode(' ', array_slice($words, 0, 10));
                                            echo htmlspecialchars($preview) . (count($words) > 10 ? '...' : '');
                                            ?>
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-2 align-content-center">
                                <div class="row">
                                    <div class="col-md-1 w-50">
                                        <a href="/reflection/edit/<?= $reflection['selfreflectID'] ?>">
                                            <i class="bi bi-pencil-square" style="font-size: 3.0rem;"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-1 w-50">
                                        <form method="POST" action="/reflection/delete/<?= $reflection['selfreflectID'] ?>" style="display: inline;">
                                            <button type="submit" style="background: none; border: none;" data-confirm-deleteReflection>
                                                <i class="bi bi-trash" style="font-size: 3.0rem; color:red;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <section class="text-center my-5">
                        <img src="/asset/bubble.png">
                        <div class="mt-5">
                            <h4 class="fw-bold">No self-reflection record</h4>
                            <h4 class="fw-bold">Please create a self-reflection.</h4>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) alert.remove();
    }, 3000);
</script>


<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>