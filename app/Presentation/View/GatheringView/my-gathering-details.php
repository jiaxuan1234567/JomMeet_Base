<?php
$_title = 'Joined Gathering Details';

require_once __DIR__ . '/../HomeView/header.php';
?>


<div class="container-sm mt-4">
    <div class="d-flex align-items-center">
        <!-- back arrow -->
        <button id="backToLastPage" class="btn p-0 me-2" style="width: auto; height: auto;">
            <i class="bi bi-arrow-left h3 m-0"></i>
        </button>
        <!-- title -->
        <h2 class="h5 fw-bold mb-0">My Gathering Details</h2>
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
                <div class="row align-items-center justify-content-center text-center my-5">
                    <p class="fs-4 fw-bolder mb-1">Current Pax</p>
                    <p class="fs-7 mb-0"><?php echo $gathering['currentParticipant'] . '/' . $gathering['maxParticipant']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/displayMap.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIm3LWq0gbsblgi0kmbEscuFq9zUoERD4&v=beta&libraries=places&loading=async&callback=initMap">
</script>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>