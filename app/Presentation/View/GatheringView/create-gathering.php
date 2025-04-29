<?php
$_title = 'Create Gathering';
require_once __DIR__ . '/Users/tojiaxuan/Documents/GitHub/JomMeet_Base/app/Presentation/View/HomeView/header.php';
?>

<div class="container-fluid" id="mainContent">
    <div id="createGatheringForm">
        <div class="d-flex mb-4 align-items-center">
            <div class="col-2">
                <button class="btn btn-outline-secondary" id="backToForm" type="button">&#8592;</button>
            </div>
            <div class="col-8 text-center">
                <h2 class="fw-bold mb-0">Gathering Details</h2>
            </div>
            <div class="col-2"></div>
        </div>

        <div class="container">
            <form class="row g-4 my-4" action="/my-gathering/create" method="post">

                <!-- Image + Gathering Tag -->
                <div class="col-12 text-center mb-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/1161/1161388.png" alt="Gathering Banner" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                    <h4 class="mt-2 fw-semibold">"Gathering tag"</h4>
                </div>

                <!-- Theme and Date -->
                <div class="col-12 row align-items-center">
                    <div class="col-sm-1 text-sm-end">
                        <label for="inputTheme" class="col-form-label fw-semibold">Theme</label>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="inputTheme" name="inputTheme">
                    </div>

                    <div class="col-sm-1 text-sm-end">
                        <label for="inputDate" class="col-form-label fw-semibold">Date</label>
                    </div>
                    <div class="col-sm-5">
                        <input type="date" class="form-control" id="inputDate" name="inputDate">
                    </div>
                </div>

                <!-- Pax and Time -->
                <div class="col-12 row align-items-center">
                    <div class="col-sm-1 text-sm-end">
                        <label for="inputPax" class="col-form-label fw-semibold">No. Pax</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" onclick="adjustPax(-1)">-</button>
                            <input type="number" class="form-control text-center" id="inputPax" name="inputPax" value="1" min="1">
                            <button class="btn btn-outline-secondary" type="button" onclick="adjustPax(1)">+</button>
                        </div>
                    </div>

                    <div class="col-sm-1 text-sm-end">
                        <label for="startTime" class="col-form-label fw-semibold">Time</label>
                    </div>
                    <div class="col-sm-5 d-flex gap-2">
                        <input type="time" class="form-control" id="startTime" name="startTime">
                        <input type="time" class="form-control" id="endTime" name="endTime">
                    </div>
                </div>

                <!-- Location -->
                <div class="col-12 row align-items-center">
                    <div class="col-sm-1 text-sm-end">
                        <label for="inputLocation" class="col-form-label fw-semibold">Location</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputLocation" name="inputLocation" placeholder="Select a location" value="<?= htmlspecialchars($address) ?>" readonly>
                            <input type="hidden" name="locationId" value="<?= htmlspecialchars($locationId) ?>">
                            <button type="button" class="btn btn-outline-primary" id="chooseLocationBtn">Choose</button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 d-flex justify-content-center gap-3 mt-4">
                    <button type="reset" class="btn btn-secondary py-2 px-4">Reset</button>
                    <button type="submit" class="btn btn-primary py-2 px-4">Create</button>
                </div>

            </form>
        </div>
    </div>

    <div id="selectLocationForm"></div>
</div>


<script src="/js/gatheringMap.js"></script>

<script>
    function adjustPax(change) {
        const paxInput = document.getElementById('inputPax');
        let current = parseInt(paxInput.value) || 1;
        current += change;
        paxInput.value = current < 1 ? 1 : current;
    }

    $('#chooseLocationBtn').on('click', () => {
        window.location.href = '/my-gathering/create/location';
    });
</script>

<?php require_once __DIR__ . '/Users/tojiaxuan/Documents/GitHub/JomMeet_Base/app/Presentation/View/HomeView/footer.php'; ?>