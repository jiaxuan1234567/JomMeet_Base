<?php
$_title = "Join a Gathering";
require_once __DIR__ . '/../HomeView/header.php';

$asset = new FileHelper('asset');
//For testing purposes only
$userid = $_SESSION['profile']['profileID'];

?>

<script src="/js/gatheringlist.js"></script>

<div class="container-fluid my-5 mb-5">
    <!-- Flash Message -->
    <?php
    if (!empty($_SESSION['flash_message'])):
    ?>
        <div id="flashMessage"
            class="flash-message"
            data-type="<?= $_SESSION['flash_type'] ?? '' ?>"
            data-msg="<?= $_SESSION['flash_message'] ?>">
        </div>
        <?php
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        ?>
    <?php endif; ?>

    <!-- Content -->
    <div class="container-sm mt-4">
        <div class="row">
            <div class="col">
                <h2>Gathering List</h2>
            </div>
            <div class="col">
                <form class="d-flex" id="search" style="width: 750px;" action="/gathering/search" method="POST">
                    <input class="form-control me-4" name="searchTerm" type="search" placeholder="Search by theme, date, time, or preference" aria-label="Search" value="<?php echo htmlspecialchars($_POST['searchTerm'] ?? ''); ?>">
                    <button type="submit" class="btn btn-outline-primary">Search</button>
                </form>
            </div>
            <div class="col">
                <a data-post="/gathering/match" class="btn btn-light border border-secondary d-flex align-items-center gap-2" style="width:100px;">
                    <input type="hidden" name="userid" value="<?= htmlspecialchars($userid) ?>">
                    <img src="<?= $asset->getFilePath('match') ?>" alt="Icon" style="width: 20px; height: 20px;">
                    <span>Match</span>
                </a>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <?php if (empty($gatherings)): ?>
            <div style="height: 575px;">
                <div class="alert alert-info">
                    No gatherings available at the moment.
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($gatherings as $gathering): ?>
                    <div class="col-md-6">
                        <div class="d-flex border rounded shadow-sm p-2 bg-white">
                            <img src="<?= $asset->getFilePath('dinner') ?>" alt="Dinner" class="rounded" style="width: 120px; height: auto; object-fit: cover;">
                            <div class="ms-3 d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <strong><?php echo htmlspecialchars($gathering['theme']); ?></strong><br>
                                    <small>Date: <?php echo htmlspecialchars($gathering['date']); ?></small><br>
                                    <small>Time: <?php echo date('g:i A', strtotime($gathering['startTime'])); ?></small><br>
                                    <small>End Time: <?php echo date('g:i A', strtotime($gathering['endTime'])); ?></small><br>
                                    <small>Venue: <?php echo htmlspecialchars($gathering['preference']); ?></small><br>
                                    <p class="fs-7 fw-bold mb-1 mt-2">Current Pax</p>
                                    <p class="fs-7 mb-0"><?php echo htmlspecialchars($gathering['currentParticipant']) . '/' . htmlspecialchars($gathering['maxParticipant']); ?></p>
                                </div>
                                <div class="mt-2">
                                    <a data-get="/gathering/view/<?= htmlspecialchars($gathering['gatheringID']) ?>" class="btn btn-primary w-100">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>