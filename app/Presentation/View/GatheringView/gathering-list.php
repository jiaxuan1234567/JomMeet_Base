<?php
$_title = "Join a Gathering";
require_once __DIR__ . '/../HomeView/header.php';

$asset = new FileHelper('asset');
//For testing purposes only
$userid = $_SESSION['profile']['profileID'];

?>

<script src="/js/gatheringlist.js"></script>


<div class="container-fluid px-3 mt-5">
    <div class="row align-items-center">
        <div class="col-2">
            <h2 class="fw-bold">Gathering List</h2>
        </div>
        <div class="col-9">
            <form class="d-flex" id="search" action="/gathering/search" method="POST">
                <input class="form-control me-1" name="searchTerm" type="search"
                    placeholder="Search by theme, date, time, or preference"
                    aria-label="Search"
                    value="<?php echo htmlspecialchars($_POST['searchTerm'] ?? ''); ?>"
                    style="width: 100%;">
                <button type="submit" class="btn btn-outline-primary ms-1">Search</button>
            </form>
        </div>
        <div class="col-1 ps-1">
            <a href="/gathering/match"
                class="btn btn-light border border-secondary d-inline-flex align-items-center gap-1"
                style="width:100px;">
                <img src="<?= $asset->getFilePath('match') ?>"
                    alt="Icon"
                    style="width: 20px; height: 20px;">
                <span>Match</span>
            </a>
        </div>
    </div>
</div>

<div class="container-fluid my-5 mb-5" style="min-height: 70vh;">
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
    <div class="container py-4">
        <?php if (empty($gatherings)): ?>
            <div style="height: 575px;">
                <div class="alert alert-info">
                    The current gathering list is empty, wait for someone to host a gathering or host one yourself.
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($gatherings as $gathering): ?>
                    <div class="col-6 mb-0 mt-4 pb-0">
                        <div class="card border-0 rounded">
                            <div class="row g-0 align-items-center">
                                <div class="col-4 text-center p-2">
                                    <img src="<?= htmlspecialchars($asset->getFilePath(strtolower($gathering['preference'] ?? 'default'))) ?>" class="img-fluid" style="max-height:100px;">
                                </div>
                                <div class="col-8">
                                    <div class="card-body py-2 px-3">
                                        <div class="bg-blue-color card-text small px-3 py-2 mb-1 rounded" style="background-color: #DEECFF;">
                                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($gathering['theme']) ?></h6>
                                            <p class="mb-0 small">Date: <?= htmlspecialchars($gathering['date']) ?></p>
                                            <p class="mb-0 small">Time: <?= date('g:i A', strtotime($gathering['startTime'])) ?>–<?= date('g:i A', strtotime($gathering['endTime'])) ?></p>
                                            <p class="mb-0 small text-truncate">Venue: <?= htmlspecialchars(ucfirst(strtolower($gathering['locationName']))) ?></p>
                                            <p class="mb-0 small">Pax: <?= htmlspecialchars($gathering['currentParticipant']) ?>/<?= htmlspecialchars($gathering['maxParticipant']) ?></p>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="/my-gathering/view/<?= htmlspecialchars($gathering['gatheringID']) ?>" class="btn btn-sm w-100 px-3 fw-bold text-white" style="background-color: #569FFF; border: none; border-radius: 20px;">View Details</a>
                                            <!-- Optional extra action button if needed -->
                                        </div>
                                    </div>
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