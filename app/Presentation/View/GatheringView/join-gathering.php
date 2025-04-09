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

    <!-- Gathering Listing -->
    <?php
    // Sample data array
    $dinners = [
        [
            'gatheringID' => 1,
            'title' => 'Dinner',
            'date' => '25 March 2025',
            'time' => '6.00pm–8.00pm',
            'venue' => 'Anne Elizabeth',
            'pax' => '1/5',
            'image' => 'Presentation/View/GatheringView/images/dinnerpic.png'
        ],
        [
            'gatheringID' => 2,
            'title' => 'Dinner 2',
            'date' => '25 March 2025',
            'time' => '6.00pm–8.00pm',
            'venue' => 'Anne Elizabeth',
            'pax' => '4/5',
            'image' => 'Presentation/View/GatheringView/images/dinnerpic.png'
        ],

        [
            'gatheringID' => 3,
            'title' => 'Dinner 3',
            'date' => '25 March 2025',
            'time' => '6.00pm–8.00pm',
            'venue' => 'Anne Elizabeth',
            'pax' => '4/5',
            'image' => 'Presentation/View/GatheringView/images/dinnerpic.png'
        ],

        [
            'gatheringID' => 4,
            'title' => 'Dinner 4',
            'date' => '25 March 2025',
            'time' => '6.00pm–8.00pm',
            'venue' => 'Anne Elizabeth',
            'pax' => '4/5',
            'image' => 'Presentation/View/GatheringView/images/dinnerpic.png'
        ],

        [
            'gatheringID' => 3,
            'title' => 'Dinner 3',
            'date' => '25 March 2025',
            'time' => '6.00pm–8.00pm',
            'venue' => 'Anne Elizabeth',
            'pax' => '4/5',
            'image' => 'Presentation/View/GatheringView/images/dinnerpic.png'
        ],

        [
            'gatheringID' => 4,
            'title' => 'Dinner 4',
            'date' => '25 March 2025',
            'time' => '6.00pm–8.00pm',
            'venue' => 'Anne Elizabeth',
            'pax' => '4/5',
            'image' => 'Presentation/View/GatheringView/images/dinnerpic.png'
        ],
        [
            'gatheringID' => 3,
            'title' => 'Dinner 3',
            'date' => '25 March 2025',
            'time' => '6.00pm–8.00pm',
            'venue' => 'Anne Elizabeth',
            'pax' => '4/5',
            'image' => 'Presentation/View/GatheringView/images/dinnerpic.png'
        ],

        [
            'gatheringID' => 4,
            'title' => 'Dinner 4',
            'date' => '25 March 2025',
            'time' => '6.00pm–8.00pm',
            'venue' => 'Anne Elizabeth',
            'pax' => '4/5',
            'image' => 'Presentation/View/GatheringView/images/dinnerpic.png'
        ],
    ];
    ?>

    <div class="container py-4">
        <div class="row g-4">
            <?php foreach ($dinners as $dinner): ?>
                <div class="col-md-6">
                    <div class="d-flex border rounded shadow-sm p-2 bg-white">
                        <img src="<?php echo $dinner['image']; ?>" alt="Dinner" class="rounded" style="width: 120px; height: auto; object-fit: cover;">
                        <div class="ms-3 d-flex flex-column justify-content-between flex-grow-1">
                            <div>
                                <strong><?php echo htmlspecialchars($dinner['title']); ?></strong><br>
                                <small>Date: <?php echo htmlspecialchars($dinner['date']); ?></small><br>
                                <small>Time: <?php echo htmlspecialchars($dinner['time']); ?></small><br>
                                <small>Venue: <?php echo htmlspecialchars($dinner['venue']); ?></small><br>
                                <small>Pax: <?php echo htmlspecialchars($dinner['pax']); ?></small>
                            </div>
                            <div class="mt-2">
                                <button data-get="join-gathering-detail" class="btn btn-primary w-100">View Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php
    require('../app/_footer.php');
    ?>
</body>

</html>