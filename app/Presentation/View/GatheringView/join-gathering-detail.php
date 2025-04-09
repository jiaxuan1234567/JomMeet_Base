<!DOCTYPE html>
<html lang="en">
<?php
require('../app/_header.php');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join a Gathering</title>
</head>

<!-- Searchbar container -->

<body>
    <div class="container-sm mt-4">
        <div class="row">
            <div class="col">
                <h2>Gathering Details</h2>
            </div>
        </div>
    </div>

    <div class="container py-3">
        <div class="container justify-content-between p-3 mt-7 border rounded bg-blue-color border-light-blue">
            <div class="row">
                <div class="col align-self-start">
                    <img src="Presentation\View\GatheringView\images\map.png" alt="" style="width: 470px; height: 470px;">
                </div>
                <div class="col align-self-start">
                    <div class="row">
                        <div class="col">
                            <p class="fs-4 fw-bolder mb-1">Gathering Theme</p>
                            <p class="fs-5 mb-3">Dinner</p>
                            <p class="fs-4 fw-bolder mb-1">Address</p>
                            <p class="fs-5 mb-0">1, Jalan Manis 4,<br> Taman Bukit Segar, <br> 56100 Kuala Lumpur,<br> Wilayah Persekutuan Kuala Lumpur</p>
                        </div>
                        <div class="col">
                            <p class="fs-4 fw-bolder mb-1">Date</p>
                            <p class="fs-5 mb-3">25 March 2025</p>
                            <p class="fs-4 fw-bolder mb-1">Start Time</p>
                            <p class="fs-5 mb-0">6.00pm</p>
                            <p class="fs-4 fw-bolder mb-1">End Time</p>
                            <p class="fs-5 mb-0">8.00pm</p>
                        </div>
                    </div>
                    <div class="row my-3">

                    </div>
                    <div class="row align-items-center justify-content-center my-5 ">
                        <p class="fs-4 fw-bolder mb-1">Current Pax</p>
                        <p class="fs-7 mb-0">1/5</p>
                    </div>
                    <div class="row justify-content-center">
                        <button class="btn btn-light mx-1" style="height: 35px; width: 200px;" data-get="join-gathering">Cancel</button>
                        <button class="btn btn-primary button-blue-color border-0 mx-1" style="height: 35px; width: 200px;">Join</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    require('../app/_footer.php');
    ?>
</body>

</html>