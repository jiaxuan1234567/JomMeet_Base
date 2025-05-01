<?php
$_title = 'My Gathering';

require_once __DIR__ . '/../HomeView/header.php';
$asset = new FileHelper('asset');
?>
<style>
    .action-dropdown a:hover,
    .action-dropdown button:hover {
        background-color: #569FFF;
        color: #F5F5F7;
    }
</style>

<div class="container-fluid my-5 mb-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
        <h2 class="fw-bold">My Gathering</h2>
        <a href="/my-gathering/create" class="btn btn-outline-dark d-flex align-items-center py-1 px-2 rounded ">
            <span class="d-inline-block bg-dark text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 30px; height: 30px;">
                <i class="bi bi-plus" style="font-size: 1.25rem;"></i>
            </span>
            <span class="fw-bold me-1">Create</span>
        </a>
    </div>
    <div class="container">
        <ul class="nav justify-content-center nav-pills nav-justified mb-4 fw-semibold" id="gatheringTabs" role="tablist">
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link text-white border border-black"
                    id="all-tab"
                    data-status="all"
                    type="button"
                    role="tab"
                    style="background-color: #569FFF; border-radius: 10px;">All</button>
            </li>
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link bg-white text-black border border-black"
                    id="hosted-tab"
                    data-status="hosted"
                    type="button"
                    role="tab"
                    style="border-radius: 10px;">Hosted</button>
            </li>
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link bg-white text-black border border-black"
                    id="upcoming-tab"
                    data-status="upcoming"
                    type="button"
                    role="tab"
                    style="border-radius: 10px;">Up Coming</button>
            </li>
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link bg-white text-black border border-black"
                    id="ongoing-tab"
                    data-status="ongoing"
                    type="button"
                    role="tab"
                    style="border-radius: 10px;">On Going</button>
            </li>
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link bg-white text-black border border-black"
                    id="completed-tab"
                    data-status="completed"
                    type="button"
                    role="tab"
                    style="border-radius: 10px;">Completed</button>
            </li>
            <li class="nav-item m-3" role="presentation">
                <button
                    class="nav-link bg-white text-black border border-black"
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
</div>


<script>
    $(function() {
        var allGatherings = <?= json_encode($myGatherings) ?>;
        var $container = $('#gatheringList');
        var $tabs = $('#gatheringTabs button');

        function renderPostList(url, btnTitle, cfmTitle) {
            return `
                <li>
                    <form method="POST" action="${url}" onsubmit="return confirm('${cfmTitle}')" style="margin:0;">
                        <button type="submit" class="dropdown-item fw-bold">${btnTitle}</button>
                    </form>
                </li>
            `;
        }

        function buildActionMenu(g) {
            if (g.status === 'cancelled') return ''; // No actions

            let items = [];

            switch (g.status) {
                case 'new':
                    if (g.isHost) {
                        items.push(actionLink('Send Reminder'));
                        items.push(actionLink('Edit Gathering'));
                        items.push(renderPostList(`my-gathering/cancel/${g.id}`, 'Cancel Gathering', 'Confirm to cancel the gathering?'));
                    } else {
                        items.push(actionLink('Reply Reminder'));
                        items.push(renderPostList(`gathering/leave/${g.id}`, 'Leave Gathering', 'Confirm to leave the gathering?'));
                    }
                    break;

                case 'start':
                    if (g.isHost) {
                        items.push(actionLink('Send Reminder'));
                    } else {
                        items.push(actionLink('Reply Reminder'));
                    }
                    break;

                case 'end':
                    items.push(actionLink('Gathering Feedback'));
                    items.push(actionLink('Location Feedback'));
                    break;
            }

            if (!items.length) return '';

            return `
                <div class="dropdown rounded border-0">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
                        Action
                    </button>
                    <ul class="dropdown-menu p-0 action-dropdown" style="background-color: #F5F5F7;">
                        ${items.join('')}
                    </ul>
                </div>`;
        }

        // Helper to build a <li><a></a></li>
        function actionLink(label) {
            return `<li><a class="dropdown-item fw-bold" href="#">${label}</a></li>`;
        }

        function renderGatherings(list) {
            if (!list.length) {
                return $container.html(`
                <img src="<?= $asset->getFilePath('iconPNG'); ?>" alt="" class="mx-auto d-block w-25">
                <p class="text-center text-black fw-semibold mt-4">No gatherings found.</p>
                `);
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
            ${buildActionMenu(g)}
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
                        .removeClass('bg-white text-black border border-black')
                        .addClass('text-white')
                        .css('background-color', '#569FFF');
                } else {
                    $b
                        .removeClass('text-white')
                        .addClass('bg-white text-black border border-black')
                        .css('background-color', '');
                }
            });
        }

        function filterByTab(tab) {
            switch (tab) {
                case 'all':
                    return allGatherings;
                    //return allGatherings.filter(g => g.isHost || g.isJoined);
                case 'hosted':
                    return allGatherings.filter(g => g.isHost && g.status !== 'cancelled');
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