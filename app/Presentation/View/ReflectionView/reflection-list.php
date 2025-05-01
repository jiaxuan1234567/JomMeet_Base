<?php
$_title = 'My Gathering';
require_once __DIR__ . '/../HomeView/header.php';
?>

<div class="main">
    <section class="page-header page-header-classic page-header-sm">
        <div class="container">
            <div class="row">
                <div class="col-md-10 order-2 order-md-1 align-self-center p-static my-3">
                    <h2 data-title-border>Self-Reflection</h2>
                </div>
                <div class="col-md-2 order-1 order-md-2 align-self-center my-3 justify-content-center">
                    <ul class="breadcrumb d-block text-md-end">
                        <li><a href="/reflection/create" class="btn btn-outline-secondary rounded-pill"> + Create </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="row">
            <div class="container">
                <?php if (!empty($reflections)): ?>
                    <?php foreach ($reflections as $reflection): ?>
                        <div class="row container border border-2 rounded-3 m-2" style="background-color: rgba(222,236,255,68); border-color:#0077CC !important;">
                            <div class="col-md-10">
                                <a href="/reflection/view/<?= $reflection['selfreflectID'] ?>" class="text-decoration-none text-reset">
                                <div>
                                    <h4 class="font-weight-medium mb-0" data-asw-orgfontsize="24" style="font-size: 24px;padding: 10px 0;"><?php echo htmlspecialchars(date('j F Y g:ia', strtotime($reflection['date']))); ?></h4>
                                    <p><b><?php echo htmlspecialchars($reflection['title']); ?></b></p>
                                </div>
                                <div>
                                    <p><?php echo htmlspecialchars($reflection['content']); ?></p>
                                </div>
                                </a>
                            </div>
                            <div class="col-md-2 align-content-center">
                                <div class="row">
                                    <div class="col-md-1 w-50">
                                        <a href="/reflection/edit/<?= $reflection['selfreflectID'] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="col-md-1 w-50">
                                        <a href="/reflection/delete/<?= $reflection['selfreflectID'] ?>" onclick="return confirm('Are you sure you want to delete this reflection?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="red" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                            </svg>
                                        </a>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div>no reflection found...</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>