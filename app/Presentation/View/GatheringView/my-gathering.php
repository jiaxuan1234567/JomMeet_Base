<?php
$_title = 'My Gathering';
require_once __DIR__ . '/../../../FileHelper.php'; // adjust the path accordingly
require_once __DIR__ . '/../HomeView/header.php';
?>

<?php
// dummy data
$myGatherings = [
    [
        "cover" => "dinnerPic.png",
        "theme" => "Dinner",
        "date" => "17 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 4,
        "venue" => "Anna Elizabeth Park",
        "status" => "hosted"
    ],
    [
        "cover" => "gameboy.png",
        "theme" => "Dinner",
        "date" => "29 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 5,
        "venue" => "Anna Elizabeth Park",
        "status" => "hosted"
    ],
    [
        "cover" => "palm-tree.png",
        "theme" => "Dinner",
        "date" => "20 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 5,
        "venue" => "Anna Elizabeth Park",
        "status" => "upcoming"
    ],

    [
        "cover" => "dinnerPic.png",
        "theme" => "Dinner",
        "date" => "20 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 5,
        "venue" => "Anna Elizabeth Park",
        "status" => "ongoing"
    ],
    [
        "cover" => "dinnerPic.png",
        "theme" => "Dinner",
        "date" => "20 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 5,
        "venue" => "Anna Elizabeth Park",
        "status" => "cancelled"
    ],
    [
        "cover" => "dinnerPic.png",
        "theme" => "Dinner",
        "date" => "20 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 5,
        "venue" => "Anna Elizabeth Park",
        "status" => "completed"
    ],

    [
        "cover" => "dinnerPic.png",
        "theme" => "Dinner",
        "date" => "20 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 5,
        "venue" => "Anna Elizabeth Park",
        "status" => "completed"
    ],
    [
        "cover" => "dinnerPic.png",
        "theme" => "Dinner",
        "date" => "20 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 5,
        "venue" => "Anna Elizabeth Park",
        "status" => "upcoming"
    ],
    [
        "cover" => "dinnerPic.png",
        "theme" => "Dinner",
        "date" => "20 June 2025",
        "startTime" => "6:00pm",
        "endTime" => "8:00pm",
        "pax" => 5,
        "venue" => "Anna Elizabeth Park",
        "status" => "hosted"
    ],
    // More gatherings...
];
?>

<div class="container-fluid my-5 mb-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">My Gathering</h2>
        <a href="/my-gathering/create" class="btn btn-primary px-4"
            style="background-color: #fff; color: #000; border: 2px solid #fff; transition: background-color 0.3s, color 0.3s, border 0.3s;"
            onmouseover="this.style.backgroundColor='#569FFF'; this.style.color='#fff'; this.style.border='2px solid #569FFF';"
            onmouseout="this.style.backgroundColor='#fff'; this.style.color='#000'; this.style.border='2px solid #fff';">Create</a>
    </div>
    <div class="container">
        <ul class="nav justify-content-center nav-pills nav-justified mb-4" id="gatheringTabs" role="tablist">
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link text-white"
                    id="all-tab"
                    data-status="all"
                    type="button"
                    role="tab"
                    style="background-color: #569FFF; border-radius: 10px;">All</button>
            </li>
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link bg-white text-black border"
                    id="hosted-tab"
                    data-status="hosted"
                    type="button"
                    role="tab"
                    style="border-radius: 10px;">Hosted</button>
            </li>
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link bg-white text-black border"
                    id="upcoming-tab"
                    data-status="upcoming"
                    type="button"
                    role="tab"
                    style="border-radius: 10px;">Up Coming</button>
            </li>
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link bg-white text-black border"
                    id="ongoing-tab"
                    data-status="ongoing"
                    type="button"
                    role="tab"
                    style="border-radius: 10px;">On Going</button>
            </li>
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link bg-white text-black border"
                    id="completed-tab"
                    data-status="completed"
                    type="button"
                    role="tab"
                    style="border-radius: 10px;">Completed</button>
            </li>
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link bg-white text-black border"
                    id="cancelled-tab"
                    data-status="cancelled"
                    type="button"
                    role="tab"
                    style="border-radius: 10px;">Cancelled</button>
            </li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content" id="gatheringTabsContent">
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">


                <div id="gatheringList" class="row g-5 rounded p-3 text-dark mt-4" style="background-color: #DEECFF;">
                    <?php
                    // Filter gatherings based on the selected status
                    $filteredGatherings = $myGatherings;
                    if (isset($_GET['status'])) {
                        $status = $_GET['status'];
                        if ($status !== 'all') {
                            $filteredGatherings = array_filter($myGatherings, function ($gathering) use ($status) {
                                return $gathering['status'] === $status;
                            });
                        }
                    }
                    ?>

                    <?php if (!empty($filteredGatherings)): ?>
                        <?php foreach ($filteredGatherings as $myGathering): ?>
                            <div class="col-6 mb-0 mt-4 pb-0">
                                <div class="card border-0 rounded">
                                    <div class="row g-0 align-items-center">
                                        <!-- Left image section -->
                                        <div class="col-4 text-center p-2">
                                            <img src="<?php echo htmlspecialchars('../../../public/asset/' . $myGathering['cover'], ENT_QUOTES, 'UTF-8'); ?>"
                                                class="img-fluid"
                                                alt="Event Image"
                                                style="max-height: 100px;"
                                                onerror="this.onerror=null;this.src='https://cdn-icons-png.flaticon.com/512/1161/1161388.png';">
                                        </div>

                                        <!-- Right content section -->
                                        <div class="col-8">
                                            <div class="card-body py-2 px-3">

                                                <div class="bg-blue-color card-text small px-3 py-2 mb-1 rounded"
                                                    style="background-color: #DEECFF;">

                                                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($myGathering['theme']); ?></h6>
                                                    <p class="mb-0 small">Date: <?php echo htmlspecialchars($myGathering['date']); ?></p>
                                                    <p class="mb-0 small">Time: <?php echo htmlspecialchars($myGathering['startTime']) . ' - ' . htmlspecialchars($myGathering['endTime']); ?></p>
                                                    <p class="mb-0 small">Venue: <?php echo htmlspecialchars($myGathering['venue']); ?></p>
                                                    <p class="mb-0 small">Pax: <?php echo htmlspecialchars($myGathering['pax']); ?>/5</p>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <a href="my-gathering-details.php" class="btn btn-sm w-100 px-3 fw-bold text-white" style="background-color: #569FFF; border: none; border-radius: 20px;">View Details</a>
                                                    <div class="dropdown rounded border-0">
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
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

            <!-- Other tab panes can be added here, similarly filtered -->
        </div>
    </div>
    <div style="height: 300px;"></div>
</div>

<script>
    $(function() {
        // 1) grab the data
        var allGatherings = <?php echo json_encode($myGatherings); ?>;
        var $container = $('.row.g-5'); // where we inject cards
        var $tabs = $('#gatheringTabs button'); // status tabs

        // 2) render function
        function renderGatherings(list) {
            var html = '';
            if (!list.length) {
                html = '<p class="text-center text-muted">No gatherings found.</p>';
            } else {
                $.each(list, function(_, g) {
                    html += '' +
                        '<div class="col-6 mb-0 mt-4 pb-0">' +
                        '  <div class="card border-0 rounded">' +
                        '    <div class="row g-0 align-items-center">' +
                        '      <div class="col-4 text-center p-2">' +
                        '        <img src="/asset/' + g.cover + '"' +
                        '             class="img-fluid"' +
                        '             alt="Event Image"' +
                        '             style="max-height:100px;"' +
                        '             onerror="this.src=\'https://cdn-icons-png.flaticon.com/512/1161/1161388.png\'">' +
                        '      </div>' +
                        '      <div class="col-8">' +
                        '        <div class="card-body py-2 px-3">' +
                        '          <div class="bg-blue-color card-text small px-3 py-2 mb-1 rounded" style="background-color:#DEECFF;">' +
                        '            <h6 class="fw-bold mb-1">' + g.theme + '</h6>' +
                        '            <p class="mb-0 small">Date: ' + g.date + '</p>' +
                        '            <p class="mb-0 small">Time: ' + g.startTime + ' - ' + g.endTime + '</p>' +
                        '            <p class="mb-0 small">Venue: ' + g.venue + '</p>' +
                        '            <p class="mb-0 small">Pax: ' + g.pax + '/5</p>' +
                        '          </div>' +
                        '          <div class="d-flex gap-2">' +
                        '            <a href="/my-gathering/view/' + (g.id || 0) + '"' +
                        '               class="btn btn-sm w-100 px-3 fw-bold text-white"' +
                        '               style="background-color:#569FFF;border:none;border-radius:20px;">' +
                        '              View Details' +
                        '            </a>' +
                        (g.status === 'cancelled' ?
                            '' :
                            '<div class="dropdown">' +
                            '  <button class="btn btn-outline-secondary btn-sm dropdown-toggle fw-bold"' +
                            '          type="button" data-bs-toggle="dropdown" aria-expanded="false"' +
                            '          style="border-radius:20px;">Action</button>' +
                            '  <ul class="dropdown-menu">' +
                            (g.status === 'hosted' ?
                                '<li><a class="dropdown-item">Send Reminder</a></li>' +
                                '<li><a class="dropdown-item">Edit Gathering</a></li>' +
                                '<li><a class="dropdown-item text-danger">Cancel Gathering</a></li>' :
                                g.status === 'upcoming' ?
                                '<li><a class="dropdown-item">Reply Reminder</a></li>' +
                                '<li><a class="dropdown-item text-danger">Leave Gathering</a></li>' :
                                g.status === 'ongoing' ?
                                '<li><a class="dropdown-item">Reply Reminder</a></li>' :
                                g.status === 'completed' ?
                                '<li><a class="dropdown-item" href="gathering-feedback.php?status=completed">Gathering Feedback</a></li>' +
                                '<li><a class="dropdown-item" href="location-feedback.php?status=completed">Location Feedback</a></li>' :
                                '') +
                            '  </ul>' +
                            '</div>') +
                        '          </div>' +
                        '        </div>' +
                        '      </div>' +
                        '    </div>' +
                        '  </div>' +
                        '</div>';
                });
            }
            $container.html(html);
        }

        // 3) switch‐tab helper
        function setActiveTab(status) {
            $tabs.each(function() {
                var $b = $(this);
                if ($b.data('status') === status) {
                    $b.removeClass('bg-white text-black')
                        .addClass('text-white')
                        .css('background-color', '#569FFF');
                } else {
                    $b.removeClass('text-white')
                        .addClass('bg-white text-black')
                        .css('background-color', '');
                }
            });
        }

        // 4) on load: pick hash or all, then render + style
        var initStatus = window.location.hash.slice(1) || 'all';
        setActiveTab(initStatus);
        renderGatherings(
            initStatus === 'all' ?
            allGatherings :
            $.grep(allGatherings, function(g) {
                return g.status === initStatus;
            })
        );

        // 5) tab click binding
        $tabs.on('click', function(e) {
            e.preventDefault();
            var st = $(this).data('status');
            setActiveTab(st);

            var subset = (st === 'all') ?
                allGatherings :
                $.grep(allGatherings, function(g) {
                    return g.status === st;
                });
            renderGatherings(subset);

            if (st === 'all') {
                history.replaceState(null, '', '/my-gathering');
            } else {
                history.replaceState(null, '', '#' + st);
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>