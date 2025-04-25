<?php include('../app/Presentation/View/HomeView/header.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Gathering</title>
</head>

<?php
$myGatherings = [
    [
        "cover" => "image.jpg",
        "theme" => "Dinner",
        "date" => "20 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 5,
        "venue" => "Anna Elizabeth Park"
    ],
    [
        "cover" => "image.jpg",
        "theme" => "Dinner",
        "date" => "20 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 5,
        "venue" => "Anna Elizabeth Park"
    ],
    [
        "cover" => "image.jpg",
        "theme" => "Dinner",
        "date" => "20 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 5,
        "venue" => "Anna Elizabeth Park"
    ],
    // More gatherings...
];
?>

<body>
    <div class="container-fluid my-5">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">My Gathering</h2>
            <a href="/own/create" class="btn btn-primary px-4">Create</a>
        </div>
        <div class="container">
            <!-- Tabs -->
            <ul class="nav justify-content-center nav-pills nav-justified mb-4" id="gatheringTabs" role="tablist">
                <li class="nav-item m-3" role="presentation">
                    <button class="nav-link border bg-blue-color" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">All</button>
                </li>
                <li class="nav-item m-3" role="presentation">
                    <button class="nav-link border border-black bg-white text-black" id="hosted-tab" data-bs-toggle="tab" data-bs-target="#hosted" type="button" role="tab">Hosted</button>
                </li>
                <li class="nav-item m-3" role="presentation">
                    <button class="nav-link border border-black bg-white text-black" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">Up Coming</button>
                </li>
                <li class="nav-item m-3" role="presentation">
                    <button class="nav-link border border-black bg-white text-black" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">Completed</button>
                </li>
                <li class="nav-item m-3" role="presentation">
                    <button class="nav-link border border-black bg-white text-black" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab">Cancelled</button>
                </li>
            </ul>

            <!-- Tab Contents -->
            <div class="tab-content" id="gatheringTabsContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <div class="row g-5 rounded bg-blue-color">
                        <?php if (!empty($myGatherings)): ?>
                            <?php foreach ($myGatherings as $myGathering): ?>
                                <div class="col-6">
                                    <div class="card border-0 rounded">
                                        <div class="row g-0 align-items-center">
                                            <!-- Left image section -->
                                            <div class="col-4 text-center p-2">
                                                <img src="https://cdn-icons-png.flaticon.com/512/1161/1161388.png" class="img-fluid" alt="Event Image" style="max-height: 100px;">
                                            </div>

                                            <!-- Right content section -->
                                            <div class="col-8">
                                                <div class="card-body py-2 px-3">
                                                    <div class="bg-blue-color card-text small px-3 py-2 mb-1 rounded">
                                                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($myGathering['theme']); ?></h6>
                                                        <p class="mb-0 small">Date: <?php echo htmlspecialchars($myGathering['date']); ?></p>
                                                        <p class="mb-0 small">Time: <?php echo htmlspecialchars($myGathering['startTime']) . ' - ' . htmlspecialchars($myGathering['endTime']); ?></p>
                                                        <p class="mb-0 small">Venue: <?php echo htmlspecialchars($myGathering['venue']); ?></p>
                                                        <p class="mb-0 small">Pax: <?php echo htmlspecialchars($myGathering['pax']); ?>/5</p>
                                                    </div>

                                                    <div class="d-flex gap-2">
                                                        <a href="#" class="rounded border-0 btn btn-primary btn-sm w-100 px-3 fw-bold button-blue-color">View Details</a>
                                                        <div class="dropdown rounded border-0">
                                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="#">Send Reminder</a></li>
                                                                <li><a class="dropdown-item" href="#">Edit Gathering</a></li>
                                                                <li><a class="dropdown-item" href="#">Cancel Gathering</a></li>
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
                            <p class="text-center text-muted">No gatherings found.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Other tab panes can be added here -->
            </div>
        </div>
    </div>

    <?php include('../app/Presentation/View/HomeView/footer.php'); ?>
</body>