<?php
$_title = 'My Gathering';

require_once __DIR__ . '/../HomeView/header.php';
$asset = new FileHelper('asset');

// Determine active status and filter gatherings in PHP
$status = $_GET['status'] ?? 'all';
$currentStatus = strtolower($status);
$filteredGatherings = array_filter($myGatherings, function ($g) use ($currentStatus) {
    switch ($currentStatus) {
        case 'hosted':
            return $g['isHost'] && $g['status'] !== 'cancelled';
        case 'upcoming':
            return $g['status'] === 'new';
        case 'ongoing':
            return $g['status'] === 'start';
        case 'completed':
            return $g['status'] === 'end';
        case 'cancelled':
            return $g['isHost'] && $g['status'] === 'cancelled';
        case 'all':
        default:
            return true;
    }
});

?>

<div class="container-fluid my-5 mb-5">
    <?php if (!empty($_SESSION['flash_message'])): ?>
        <div id="flashMessage"
            class="flash-message"
            data-type="<?= $_SESSION['flash_type'] ?? '' ?>"
            data-msg="<?= $_SESSION['flash_message'] ?>">
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
        <h2 class="fw-bold">My Gathering</h2>
        <a href="/my-gathering/create" class="btn btn-outline-dark d-flex align-items-center py-1 px-2 rounded">
            <span class="d-inline-block bg-dark text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 30px; height: 30px;"><i class="bi bi-plus" style="font-size: 1.25rem;"></i></span>
            <span class="fw-bold me-1">Create</span>
        </a>
    </div>

    <!-- Tabs -->
    <div class="container">
        <ul class="nav justify-content-center nav-pills nav-justified mb-4 fw-semibold" role="tablist">
            <?php
            $tabs = ['all' => 'All', 'hosted' => 'Hosted', 'upcoming' => 'Up Coming', 'ongoing' => 'On Going', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
            foreach ($tabs as $key => $label):
                $active = $currentStatus === $key ? 'text-white' : 'bg-white text-black';
                $bg    = $currentStatus === $key ? "style=\"background-color: #569FFF; border-radius: 10px;\"" : 'style="border-radius: 10px;"';
            ?>
                <li class="nav-item m-3" role="presentation">
                    <a
                        class="nav-link <?= $active ?> border border-black"
                        id="<?= $key ?>-tab"
                        href="?status=<?= $key ?>"
                        role="tab"
                        <?= $bg ?>>
                        <?= $label ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Gatherings List -->
        <div id="gatheringList" class="row g-5 rounded p-3 text-dark mt-4" style="background-color: #DEECFF;"></div>
    </div>

    <!-- Cancel Gathering Modal -->
    <div class="modal fade" id="cancelGatheringModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content custom-modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title modal-title-custom" id="cancelModalLabel">JomMeet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    Confirm to cancel the gathering?
                </div>
                <div class="modal-footer justify-content-end border-0">
                    <form method="POST" action="/my-gathering/cancel" id="cancelGatheringForm" class="d-flex gap-3">
                        <input type="hidden" name="gatheringID" id="modalCancelGatheringID">
                        <button type="button" class="btn btn-outline-primary custom-cancel-btn" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn custom-leave-btn text-white" style="background-color: #FF5C56;">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Gathering Modal -->
    <div class="modal fade" id="leaveGatheringModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content custom-modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title modal-title-custom" id="leaveModalLabel">JomMeet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    Confirm to leave the gathering?
                </div>
                <div class="modal-footer justify-content-end border-0">
                    <form method="POST" action="/my-gathering/leave" id="leaveGatheringForm" class="d-flex gap-3">
                        <input type="hidden" name="gatheringID" id="modalLeaveGatheringID">
                        <button type="button" class="btn btn-outline-primary custom-cancel-btn" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn custom-leave-btn text-white" style="background-color: #FF5C56;">Leave</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



</div>

<script>
    $(function() {
        var gatherings = <?= json_encode(array_values($filteredGatherings)) ?>;
        var $container = $('#gatheringList');

        function renderPostList(url, btnTitle, cfmTitle = '', hidden = {}) {
            const inputs = Object.entries(hidden).map(([name, val]) =>
                `<input type=\"hidden\" name=\"${name}\" value=\"${val}\">`
            ).join('');
            const onsub = cfmTitle ? `onsubmit=\"return confirm('${cfmTitle.replace(/'/g,"\\'")}')\"` : '';
            return `
            <li>
                <form method="POST" action="${url}" ${onsub} style="margin:0;">
                    ${inputs}
                    <button type="submit" class="dropdown-item fw-bold">${btnTitle}</button>
                </form>
            </li>`;
        }





        function actionLink(label) {
            return `<li><a class=\"dropdown-item fw-bold\" href=\"#\">${label}</a></li>`;
        }

        function buildActionMenu(g) {
            if (g.status === 'cancelled') return '';
            let items = [];
            switch (g.status) {
                case 'new':
                    if (g.isHost) {
                        items.push(actionLink('Send Reminder'));
                        items.push(actionLink('Edit Gathering'));
                        items.push(`
  <li>
    <button type="button" class="dropdown-item fw-bold text-danger" onclick="confirmCancelGathering(${g.id})">
      Cancel Gathering
    </button>
  </li>
`);
                    } else {
                        items.push(actionLink('Reply Reminder'));
                        items.push(`
  <li>
    <button type="button" class="dropdown-item fw-bold text-danger" onclick="confirmLeaveGathering(${g.id})">
      Leave Gathering
    </button>
  </li>
`);
                    }
                    break;
                case 'start':
                    if (g.isHost) items.push(actionLink('Send Reminder'));
                    else items.push(actionLink('Reply Reminder'));
                    break;
                case 'end':
                    items.push(`
  <li>
    <a class="dropdown-item fw-bold"
       href="/my-gathering/gatheringFeedback?gatheringID=${g.id}">
      Gathering Feedback
    </a>
  </li>
`);
                    items.push(`
    <li>
      <a class="dropdown-item fw-bold"
         href="/my-gathering/locationFeedback?gatheringID=${g.id}&locationID=${g.locationID}">
        Location Feedback
      </a>
    </li>
  `);
                    break;
            }
            if (!items.length) return '';
            return `
            <div class="dropdown rounded border-0">
              <button class="btn btn-outline-secondary btn-sm dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" style="border-radius: 20px;">Action</button>
              <ul class="dropdown-menu p-0 action-dropdown" style="background-color: #F5F5F7;">${items.join('')}</ul>
            </div>`;
        }

        function renderGatherings(list) {
            if (!list.length) {
                return $container.html(`
                <img src="<?= $asset->getFilePath('iconPNG'); ?>" class="mx-auto d-block w-25">
                <p class="text-center text-black fw-semibold mt-4">No gatherings found.</p>
            `);
            }

            let html = '';
            list.forEach(g => {
                const theme = g.theme || 'No Theme'; // Fallback for missing theme
                const date = g.date || 'N/A'; // Fallback for missing date
                const startTime = g.startTime || 'N/A'; // Fallback for missing start time
                const endTime = g.endTime || 'N/A'; // Fallback for missing end time
                const venue = g.venue || 'Unknown Venue'; // Fallback for missing venue
                const pax = g.pax || 0; // Fallback for missing pax
                const maxPax = g.maxPax || 0; // Fallback for missing maxPax
                const preference = g.preference.toLowerCase() || 'default'; // Fallback for missing preference

                console.log(preference);
                const preferenceImageMap = <?= json_encode([
                                                'dinner' => $asset->getFilePath('dinner'),
                                                'chill' => $asset->getFilePath('chill'),
                                                'natural' => $asset->getFilePath('natural'),
                                                'shopping' => $asset->getFilePath('shopping'),
                                                'workout' => $asset->getFilePath('workout'),
                                                'entertainment' => $asset->getFilePath('entertainment'),
                                                'music' => $asset->getFilePath('music'),
                                                'movie' => $asset->getFilePath('movie'),
                                                'food' => $asset->getFilePath('food'),
                                                'sports' => $asset->getFilePath('sports'),
                                                'study' => $asset->getFilePath('study'),
                                                'default' => $asset->getFilePath('default-image'),
                                            ]) ?>;
                // Map preference to specific image paths
                const cover = preferenceImageMap[preference] || preferenceImageMap['default'];
                console.log(cover);
                html += `
<div class="col-6 mb-0 mt-4 pb-0">
  <div class="card border-0 rounded">
    <div class="row g-0 align-items-center">
      <div class="col-4 text-center p-2">
        <img src="${cover}" class="img-fluid" style="max-height:100px" onerror="this.src='https://cdn-icons-png.flaticon.com/512/1161/1161388.png'">
      </div>
      <div class="col-8">
        <div class="card-body py-2 px-3">
          <div class="card-text small px-3 py-2 mb-1 rounded" style="background-color: #DEECFF;">
            <h6 class="fw-bold mb-1">${theme}</h6>
            <p class="mb-0 small">Date: ${date}</p>
            <p class="mb-0 small">Time: ${startTime}–${endTime}</p>
            <p class="mb-0 small text-truncate">Venue: ${venue}</p>
            <p class="mb-0 small">Pax: ${pax}/${maxPax}</p>
          </div>
          <div class="d-flex gap-2">
            <a href="/my-gathering/view/${g.id}" class="btn btn-sm w-100 px-3 fw-bold text-white" style="background-color: #569FFF; border:none; border-radius:20px;">View Details</a>
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

        // Render on page load
        renderGatherings(gatherings);

        // when opening, set the form ACTION to the correct POST URL
        window.confirmCancelGathering = function(id) {
            $('#cancelGatheringForm').attr('action', `/my-gathering/cancel/${id}`);
            $('#cancelGatheringModal').modal('show');
        };
        window.confirmLeaveGathering = function(id) {
            $('#leaveGatheringForm').attr('action', `/my-gathering/leave/${id}`);
            $('#leaveGatheringModal').modal('show');
        };

    });
</script>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>