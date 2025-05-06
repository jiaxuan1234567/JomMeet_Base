<?php
$_title = 'Jom Meet';
require_once __DIR__ . '/../HomeView/header.php';
?>

<section class="text-center my-5" style="min-height: 60vh;">
    <!-- Search Bar -->
    <div class="row justify-content-center mb-4">
        <div class="col-9">
            <form class="d-flex" id="search" action="/gathering/search" method="POST">
                <input class="form-control me-1" name="searchTerm" type="search"
                    placeholder="Search by theme, date, time, or preference"
                    aria-label="Search"
                    value="<?= htmlspecialchars($_POST['searchTerm'] ?? '') ?>"
                    style="width: 100%;">
                <button type="submit" class="btn btn-outline-primary ms-1">Search</button>
            </form>
        </div>
    </div>

    <!-- Welcome Section with Background and Centering -->
    <div class="container my-5">
        <div class="justify-content-center text-center p-5 mb-5" style="background-color: #fff; border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">

            <!-- Welcome Message -->
            <?php
            $profileName = $_SESSION['profile']['nickname'] ?? 'Guest';
            ?>
            <h2 class="fw-bold">Hello, <span style="color: #569FFF;"><?= htmlspecialchars($profileName) ?></span> 👋</h2>
            <h3 class="fw-bold">Welcome to JomMeet</h3>
            <p class="fw-bold">JomMeet A New Friend</p>

            <!-- Spline 3D Object -->
            <div style="position: relative; height: 400px;" class="mb-5">
                <spline-viewer
                    url="https://prod.spline.design/VDuiMvkxF4DPHkAv/scene.splinecode"
                    class="spline-model w-100 h-100"
                    style="pointer-events: none;">
                </spline-viewer>
            </div>
        </div>
    </div>

    <!-- Centered Buttons -->
    <div class="d-flex justify-content-center gap-4 mb-5">
        <!-- Create Gathering Button -->
        <a href="/my-gathering/create" class="btn btn-primary px-4 py-2" style="background-color: #569FFF; border-color: #569FFF;">Create Gathering</a>

        <!-- Join Gathering Button -->
        <a href="/gathering" class="btn" style="background-color: #569FFF; color: white; border-color: #569FFF;">Join Gathering</a>
    </div>
</section>


<div class="row g-5 rounded p-3 text-dark mt-4" style="background-color: #DEECFF; margin: 100px 200px 50px 200px;">

    <h3 class="fw-bold m-0">Up Coming Gathering</h3>

    <?php if (!empty($gatherings)): ?>
        <?php foreach ($gatherings as $g): ?>

            <div class="col-6 mb-0 mt-4 pb-0">
                <div class="card border-0 rounded">
                    <div class="row g-0 align-items-center">
                        <div class="col-4 text-center p-2">
                            <img src="<?php echo htmlspecialchars($g['cover']); ?>" class="img-fluid" style="max-height:100px">
                        </div>
                        <div class="col-8">
                            <div class="card-body py-2 px-3">
                                <div class="bg-blue-color card-text small px-3 py-2 mb-1 rounded" style="background-color: #DEECFF;">
                                    <h6 class="fw-bold mb-1">Theme: <?php echo htmlspecialchars($g['theme']); ?></h6>
                                    <p class="mb-0 small">Date: <?php echo htmlspecialchars($g['date']); ?></p>
                                    <p class="mb-0 small">Time: <?php echo htmlspecialchars($g['startTime']); ?>-<?php echo htmlspecialchars($g['endTime']); ?></p>
                                    <p class="mb-0 small text-truncate">Venue: <?php echo htmlspecialchars($g['venue']); ?></p>
                                    <p class="mb-0 small">Pax: <?php echo htmlspecialchars($g['pax']); ?>/<?php echo htmlspecialchars($g['maxPax']); ?></p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="/my-gathering/view/<?php echo htmlspecialchars($g['id']); ?>" class="btn btn-sm w-100 px-3 fw-bold text-white" style="background-color: #569FFF; border: none; border-radius: 20px;">View Details</a>

                                    <div class="dropdown rounded border-0">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu p-0 action-dropdown" style="background-color: rgb(245, 245, 247);">
                                            <li><a class="dropdown-item fw-bold" href="/my-gathering/reminder/view/<?php echo htmlspecialchars($g['id']); ?>">Send Reminder</a></li>
                                            <li><a class="dropdown-item fw-bold" href="/my-gathering/edit/<?php echo htmlspecialchars($g['id']); ?>">Edit Gathering</a></li>
                                            <li>
                                                <form method="POST" action="/my-gathering/cancel/<?php echo htmlspecialchars($g['id']); ?>" onsubmit="return confirm('Confirm to cancel the gathering?')">
                                                    <button type="submit" class="dropdown-item fw-bold">Cancel Gathering</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <h1 class="mb-4 text-center">
            <a href="/" class="text-decoration-none">
                <img src="<?php echo (new FileHelper('asset'))->getFilePath('iconPNG') ?>" class="img-fluid mx-auto d-block" alt="Logo" width="200" />
                <h4 class="mt-2 text-black">No upcoming gathering record.</h4>
            </a>
        </h1>
    <?php endif; ?>

</div>


<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>