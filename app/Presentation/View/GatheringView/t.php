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