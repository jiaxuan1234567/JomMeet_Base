<?php
$_title = 'My Gathering';

require_once __DIR__ . '/../HomeView/header.php';
$asset = new FileHelper('asset');
?>

<div class="container-fluid my-5 mb-5">
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
            <?php
            $tabs = array_keys($myGatherings);
            foreach ($tabs as $tab): ?>
                <li class="nav-item m-3" role="presentation">
                    <button
                        class="nav-link text-white border border-black"
                        id="<?= $tab ?>-tab"
                        data-status="<?= $tab ?>"
                        type="button"
                        role="tab"
                        style="border-radius: 10px;"><?= ucwords($tab) ?></button>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content" id="gatheringTabsContent">
            <!-- All tab pane -->
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                <div class="row g-5 rounded p-3 text-dark mt-4" style="background-color: #DEECFF;">
                </div>
            </div>

            <!-- Hosted tab pane -->
            <div class="tab-pane fade" id="hosted" role="tabpanel" aria-labelledby="hosted-tab">
                <div class="row g-5 rounded p-3 text-dark mt-4" style="background-color: #DEECFF;">
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
        const allGatherings = <?= json_encode($myGatherings) ?>;
        const $tabs = $('#gatheringTabs button');
        const $tabContent = $('#gatheringTabsContent .tab-pane');

        // Render all tabs at once based on the key
        Object.entries(allGatherings).forEach(([key, gatherings]) => {
            const $container = $(`#${key} .row`);
            renderGatheringList($container, gatherings);
        });

        // Update tab UI (active color)
        function setActiveTab(status) {
            $tabs.each(function() {
                const $btn = $(this);
                const isActive = $btn.data('status') === status;
                $btn.toggleClass('text-white', isActive)
                    .toggleClass('bg-white text-black border border-black', !isActive)
                    .css('background-color', isActive ? '#569FFF' : '');
            });
        }

        function renderGatheringList($container, gatherings) {
            if (!gatherings || !gatherings.length) {
                $container.html(`
                <img src="<?= $asset->getFilePath('iconPNG'); ?>" alt="" class="mx-auto d-block w-25">
                <p class="text-center text-black fw-semibold mt-4">No gatherings found.</p>
            `);
                return;
            }

            const html = gatherings.map(g => `
            <div class="col-6 mb-0 mt-4 pb-0">
                <div class="card border-0 rounded">
                    <div class="row g-0 align-items-center">
                        <div class="col-4 text-center p-2">
                            <img src="${g.cover}" class="img-fluid" style="max-height:100px">
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
                                    ${renderActions(g)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

            $container.html(html);
        }

        function renderActions(g) {
            if (!g.action || !g.action.length) return '';
            const actions = g.action.map(label => {
                switch (label.toLowerCase()) {
                    case 'send reminder':
                        return `<li><a class="dropdown-item fw-bold" href="#">Send Reminder</a></li>`;
                    case 'edit gathering':
                        return `<li><a class="dropdown-item fw-bold" href="/my-gathering/edit/${g.id}">Edit Gathering</a></li>`;
                    case 'cancel gathering':
                        return `<li><form method="POST" action="/my-gathering/cancel/${g.id}" onsubmit="return confirm('Confirm to cancel the gathering?')">
                                <button type="submit" class="dropdown-item fw-bold">Cancel Gathering</button>
                            </form></li>`;
                    case 'reply reminder':
                        return `<li><a class="dropdown-item fw-bold" href="#">Reply Reminder</a></li>`;
                    case 'leave gathering':
                        return `<li><form method="POST" action="/my-gathering/leave/${g.id}" onsubmit="return confirm('Confirm to leave the gathering?')">
                                <button type="submit" class="dropdown-item fw-bold">Leave Gathering</button>
                            </form></li>`;
                    case 'gathering feedback':
                        return `<li><a class="dropdown-item fw-bold" href="#">Gathering Feedback</a></li>`;
                    case 'location feedback':
                        return `<li><a class="dropdown-item fw-bold" href="#">Location Feedback</a></li>`;
                    default:
                        return '';
                }
            }).join('');

            return `
            <div class="dropdown rounded border-0">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
                    Action
                </button>
                <ul class="dropdown-menu p-0 action-dropdown" style="background-color: #F5F5F7;">
                    ${actions}
                </ul>
            </div>
        `;
        }

        // Tab switch behavior
        $tabs.on('click', function(e) {
            e.preventDefault();
            const status = $(this).data('status');
            setActiveTab(status);
            $tabContent.removeClass('show active');
            $(`#${status}`).addClass('show active');
            history.replaceState(null, '', status === 'all' ? '/my-gathering' : '#' + status);
        });

        const initialTab = window.location.hash.slice(1) || 'all';
        setActiveTab(initialTab);
        $tabContent.removeClass('show active');
        $(`#${initialTab}`).addClass('show active');
    });
</script>



<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>