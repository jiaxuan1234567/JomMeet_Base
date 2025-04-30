<?php
$_title = 'My Gathering';

require_once __DIR__ . '/../HomeView/header.php';
?>

<?php if (!empty($_SESSION['flash_message'])): ?>
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
            <!-- All tab pane -->
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                <div id="gatheringList" class="row g-5 rounded p-3 text-dark mt-4" style="background-color: #DEECFF;">
                    <!-- empty: JS will render here -->
                </div>
            </div>

            <!-- Hosted tab pane -->
            <div class="tab-pane fade" id="hosted" role="tabpanel" aria-labelledby="hosted-tab">
                <div class="row g-5 rounded p-3 text-dark mt-4" style="background-color: #DEECFF;">
                    <!-- JS will render hosted gatherings into the same #gatheringList container above -->
                </div>
            </div>

            <!-- Upcoming tab pane -->
            <div class="tab-pane fade" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                <div class="row g-5 rounded p-3 text-dark mt-4" style="background-color: #DEECFF;"></div>
            </div>

            <!-- Ongoing tab pane -->
            <div class="tab-pane fade" id="ongoing" role="tabpanel" aria-labelledby="ongoing-tab">
                <div class="row g-5 rounded p-3 text-dark mt-4" style="background-color: #DEECFF;"></div>
            </div>

            <!-- Completed tab pane -->
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                <div class="row g-5 rounded p-3 text-dark mt-4" style="background-color: #DEECFF;"></div>
            </div>

            <!-- Cancelled tab pane -->
            <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                <div class="row g-5 rounded p-3 text-dark mt-4" style="background-color: #DEECFF;"></div>
            </div>
        </div>

    </div>
    <div style="height: 300px;"></div>
</div>
<script>
    $(function() {
        var allGatherings = <?= json_encode($myGatherings) ?>;
        var $container = $('#gatheringList');
        var $tabs = $('#gatheringTabs button');

        function renderGatherings(list) {
            if (!list.length) {
                return $container.html('<p class="text-center text-muted mt-4">No gatherings found.</p>');
            }
            var html = '';
            list.forEach(function(g) {




                html += `
      <div class="col-6 mb-0 mt-4 pb-0">
        <div class="card border-0 rounded">
            <div class="row g-0 align-items-center">
                <div class="col-4 text-center p-2">
                <img src="/asset/${g.cover}" class="img-fluid" style="max-height:100px"
                    onerror="this.src='https://cdn-icons-png.flaticon.com/512/1161/1161388.png'">
                </div>
                <div class="col-8">
                    <div class="card-body py-2 px-3">
                        <div class="bg-blue-color card-text small px-3 py-2 mb-1 rounded" style="background-color: #DEECFF;">
                            <h6 class="fw-bold mb-1">${g.theme}</h6>
                            <p class="mb-0 small">Date: ${g.date}</p>
                            <p class="mb-0 small">Time: ${g.startTime}–${g.endTime}</p>
                            <p class="mb-0 small text-truncate">Venue: ${g.venue}</p>
                            <p class="mb-0 small">Pax: ${g.pax}/${g.maxPax}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/my-gathering/view/${g.id}" class="btn btn-sm w-100 px-3 fw-bold text-white" style="background-color: #569FFF; border: none; border-radius: 20px;">View Details</a>

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
    </div>`;
            });
            $container.html(html);
        }

        function setActiveTab(status) {
            $tabs.each(function() {
                var $b = $(this);
                if ($b.data('status') === status) {
                    $b
                        .removeClass('bg-white text-black')
                        .addClass('text-white')
                        .css('background-color', '#569FFF');
                } else {
                    $b
                        .removeClass('text-white')
                        .addClass('bg-white text-black')
                        .css('background-color', '');
                }
                // $b.toggleClass('active', $b.data('status') === status)
                //     .toggleClass('bg-white text-black', $b.data('status') !== status);
            });
        }

        function filterByTab(tab) {
            switch (tab) {
                case 'all':
                    return allGatherings;
                    //return allGatherings.filter(g => g.isHost || g.isJoined);
                case 'hosted':
                    return allGatherings.filter(g => g.isHost);
                case 'upcoming':
                    return allGatherings.filter(g => g.status === 'new');
                case 'ongoing':
                    return allGatherings.filter(g => g.status === 'start');
                case 'completed':
                    return allGatherings.filter(g => g.status === 'end');
                case 'cancelled':
                    return allGatherings.filter(g => g.isHost && g.status === 'cancelled');
                default:
                    return [];
            }
        }

        var init = window.location.hash.slice(1) || 'all';
        setActiveTab(init);
        renderGatherings(filterByTab(init));

        $tabs.on('click', function(e) {
            e.preventDefault();
            var st = $(this).data('status');
            setActiveTab(st);
            renderGatherings(filterByTab(st));
            history.replaceState(null, '', st === 'all' ? '/my-gathering' : '#' + st);
        });
    });
</script>



<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>