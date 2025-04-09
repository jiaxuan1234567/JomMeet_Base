<!DOCTYPE html>
<html lang="en">
<?php
$homeView = new HomeView();
$homeView->include_header();
$homeView->header();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join a Gathering</title>
</head>

<body>
    <div class="container-sm mt-4">
        <div class="row">
            <div class="col">
                <h2>Gathering List</h2>
            </div>
            <div class="col">
                <form class="d-flex" role="search" id="search" style="width: 750px;">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                </form>
            </div>
            <div class="col">
                <button type="button" class="btn btn-light border border-secondary d-flex align-items-center gap-2" id="create-gathering">
                    <img src="Presentation/View/GatheringView/images/Random.png" alt="Icon" style="width: 20px; height: 20px;">
                    <span>Match</span>
                </button>
            </div>
        </div>
    </div>

        <div class="container py-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex border rounded shadow-sm p-2 bg-white">
                        <img src="Presentation/View/GatheringView/images/dinnerpic.png" alt="Dinner" class="rounded" style="width: 120px; height: auto; object-fit: cover;">
                        <div class="ms-3 d-flex flex-column justify-content-between flex-grow-1">
                            <div>
                                <strong>Dinner</strong><br>
                                <small>Date: 25 March 2025</small><br>
                                <small>Time: 6.00pm–8.00pm</small><br>
                                <small>Venue: Anne Elizabeth</small><br>
                                <small>Pax: 1/5</small>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-primary w-100">View Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</body>

</html>