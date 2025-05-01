<?php
$_title = 'Gathering Details';
require_once __DIR__ . '/../HomeView/header.php';

//use Presentation\Controller\GatheringController\GatheringController;
// //For testing purposes only
// $userid = 1;
// $controller = new GatheringController();
// $gatheringid = $gathering['gatheringID'];
// // Call the method to check if the user has joined
// $notJoined = $controller->verifyUserInGathering($userid, $gatheringid);

$userid = $_SESSION['profile']['profileID'];
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
                <div
                    id="map"
                    data-lat="<?= htmlspecialchars($gathering['latitude']) ?>"
                    data-lng="<?= htmlspecialchars($gathering['longitude']) ?>"
                    style="width: 100%; height: 470px; border-radius: 10px;">
                </div>
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

                    <?php if (!$isHost && !$isJoined && ($gathering['currentParticipant'] < $gathering['maxParticipant']) && $isBeforeStartTime): ?>
                        <form method="POST" action="/gathering/join" style="width:200px;">
                            <input type="hidden" name="userid" value="<?php echo htmlspecialchars($userid); ?>">
                            <input type="hidden" name="gatheringid" value="<?php echo htmlspecialchars($gathering['gatheringID']); ?>">
                            <button data-confirm type="submit" class="btn btn-primary button-blue-color border-0 mx-1" style="height: 35px; width: 200px;">Join Gathering</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/displayMap.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIm3LWq0gbsblgi0kmbEscuFq9zUoERD4&v=beta&libraries=places&loading=async&callback=initMap">
</script>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>