<?php
$_title = 'Gathering Details';
require_once __DIR__ . '/../HomeView/header.php';

use Presentation\Controller\GatheringController\GatheringController;

//For testing purposes only
$userid = 1;
$controller = new GatheringController();
$asset = new FileHelper('asset');
$gatheringid = $gathering['gatheringID'];
error_log("Gathering ID: " . $gatheringid);
// Call the method to check if the user has joined
$notJoined = $controller->verifyUserInGathering($userid, $gatheringid);
?>
<div class="container-sm mt-4">
    <div class="row">
        <div class="col">
            <h2>Gathering Detail</h2>
        </div>
    </div>
</div>

<div class="container py-3" style="height:630px;">
    <div class="container justify-content-between p-3 mt-7 border rounded bg-blue-color border-light-blue">
        <div class="row">
            <div class="col align-self-start">
                <img src="<?= $asset->getFilePath('map') ?>" alt="" style="width: 470px; height: 470px;">
            </div>
            <div class="col align-self-start">
                <div class="row">
                    <div class="col">
                        <p class="fs-4 fw-bolder mb-1">Gathering Theme</p>
                        <p class="fs-5 mb-3"><?php echo htmlspecialchars($gathering['theme']); ?></p>
                        <p class="fs-4 fw-bolder mb-1">Address</p>
                        <p class="fs-5 mb-0"><?php echo nl2br(htmlspecialchars($gathering['address'])); ?></p>
                    </div>
                    <div class="col">
                        <p class="fs-4 fw-bolder mb-1">Date</p>
                        <p class="fs-5 mb-3"><?php echo date('d F Y', strtotime($gathering['date'])); ?></p>
                        <p class="fs-4 fw-bolder mb-1">Start Time</p>
                        <p class="fs-5 mb-0"><?php echo date('g.ia', strtotime($gathering['startTime'])); ?></p>
                        <p class="fs-4 fw-bolder mb-1">End Time</p>
                        <p class="fs-5 mb-0"><?php echo date('g.ia', strtotime($gathering['endTime'])); ?></p>
                    </div>
                </div>
                <div class="row my-3">
                </div>
                <div class="row align-items-center justify-content-center my-5">
                    <p class="fs-4 fw-bolder mb-1">Current Pax</p>
                    <p class="fs-7 mb-0"><?php echo $gathering['currentParticipant'] . '/' . $gathering['maxParticipant']; ?></p>
                </div>
                <div class="row justify-content-center">
                    <a href="/gathering" class="btn btn-light mx-1" style="height: 35px; width: 200px;">Back</a>

                    <?php if ($gathering['currentParticipant'] < $gathering['maxParticipant']): ?>
                        <?php if ($notJoined): ?> <!-- Only show the form if the user has not joined -->
                            <form method="POST" action="/gathering/join" style="width:200px;">
                                <input type="hidden" name="userid" value="<?php echo htmlspecialchars($userid); ?>">
                                <input type="hidden" name="gatheringid" value="<?php echo htmlspecialchars($gathering['gatheringID']); ?>">
                                <button data-confirm type="submit" class="btn btn-primary button-blue-color border-0 mx-1" style="height: 35px; width: 200px;">
                                    Join Gathering
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>