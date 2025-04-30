<?php
$_title = 'Create Gathering';
require_once __DIR__ . '/../HomeView/header.php';
?>

<style>
    input[type="date"]::-webkit-inner-spin-button,
    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="time"]::-webkit-inner-spin-button,
    input[type="time"]::-webkit-calendar-picker-indicator {
        opacity: 0;
        cursor: pointer;
        position: absolute;
        right: 0;
        z-index: 1;
        width: 100%;
        -webkit-appearance: none;
    }
</style>

<div class="container-fluid" id="mainContent">
    <div id="createGatheringForm">
        <div class="d-flex mb-4 align-items-center border-bottom border-2 px-2 py-3">
            <div class="col-2">
                <i class="bi bi-arrow-left h3 m-0" id="backToForm" style="cursor:pointer;"></i>
            </div>
            <div class="col-8 text-center">
                <h2 class="fw-bold mb-0 h5">Gathering Details</h2>
            </div>
            <div class="col-2"></div>
        </div>

        <div class="container">
            <form class="row my-4" action="/my-gathering/create" method="post">

                <!-- Image + Gathering Tag -->
                <div class="col-12 text-center mb-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/1161/1161388.png" alt="Gathering Banner" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                    <h4 class="mt-2 fw-semibold">"Gathering tag"</h4>
                </div>

                <!-- Theme and Date -->
                <div class="col-12 row align-items-center mb-3">
                    <div class="col-1 text-end">
                        <label for="inputTheme" class="col-form-label fw-semibold">Theme</label>
                    </div>
                    <div class="col-5">
                        <input type="text" class="form-control px-3 py-2" id="inputTheme" name="inputTheme" placeholder="Enter Gathering Theme" value="<?= htmlspecialchars($_GET['inputTheme'] ?? '') ?>">
                    </div>

                    <div class="col-1 text-end">
                        <label for="inputDate" class="col-form-label fw-semibold">Date</label>
                    </div>
                    <div class="col-5">
                        <div class="input-group shadow-sm rounded overflow-hidden">
                            <input type="date" class="form-control border-0 fw-semibold text-center px-3 py-2" id="inputDate" name="inputDate" min="" value="<?= htmlspecialchars($_GET['inputDate'] ?? '') ?>">
                            <span class=" input-group-text bg-primary text-white border-0" style="cursor: pointer;">
                                <i class="bi bi-calendar-event-fill"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Pax and Time -->
                <div class="col-12 row align-items-center mb-3">
                    <div class="col-1 text-end">
                        <label for="inputPax" class="col-form-label fw-semibold">No. Pax</label>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" id="decreasePax" type="button">-</button>
                            <input type="number" class="form-control text-center px-3 py-2" id="inputPax" name="inputPax" value="<?= htmlspecialchars($_GET['inputPax'] ?? '3') ?>" min="3" max="8" readonly>
                            <button class=" btn btn-outline-secondary" type="button" id="increasePax">+</button>
                        </div>
                    </div>

                    <div class="col-1 text-end">
                        <label for="startTime" class="col-form-label fw-semibold">Time</label>
                    </div>
                    <div class="col-5 d-flex align-items-center gap-2 shadow-sm rounded px-3 py-2 bg-white border">
                        <input type="time" class="form-control text-white fw-semibold text-center bg-primary border-0 rounded" id="startTime" name="startTime" value="<?= htmlspecialchars($_GET['startTime'] ?? '') ?>" />
                        <span class="fw-bold">to</span>
                        <input type="time" class="form-control text-white fw-semibold text-center bg-primary border-0 rounded" id="endTime" name="endTime" min="" value="<?= htmlspecialchars($_GET['endTime'] ?? '') ?>" />
                    </div>
                </div>

                <!-- Location -->
                <div class="col-12 row align-items-center mb-3">
                    <div class="col-1 text-end">
                        <label for="inputLocation" class="col-form-label fw-semibold">Location</label>
                    </div>
                    <div class="col-11">
                        <div class="input-group">
                            <input type="text" class="form-control px-3 py-2" id="inputLocation" name="inputLocation" placeholder="Select a location" value="<?= htmlspecialchars($_GET['locationName'] ?? '') ?>" readonly>

                            <input type="hidden" name="locationId" value="<?= htmlspecialchars($_GET['locationID'] ?? '') ?>">
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
    // choose location
    $('#chooseLocationBtn').on('click', function() {
        const theme = $('#inputTheme').val();
        const pax = $('#inputPax').val();
        const date = $('#inputDate').val();
        const start = $('#startTime').val();
        const end = $('#endTime').val();

        const query = new URLSearchParams({
            inputTheme: theme,
            inputPax: pax,
            inputDate: date,
            startTime: start,
            endTime: end
        }).toString();

        window.location.href = `/my-gathering/create/location?${query}`;
    });

    // adjust pax
    $(function() {
        const $input = $('#inputPax');
        const $increase = $('#increasePax');
        const $decrease = $('#decreasePax');

        function updateButtons() {
            const val = parseInt($input.val());
            $decrease.prop('disabled', val <= parseInt($input.attr('min')));
            $increase.prop('disabled', val >= parseInt($input.attr('max')));

            $decrease.prop('disabled', val <= min);
            $increase.prop('disabled', val >= max);
        }

        $increase.on('click', function() {
            let current = parseInt($input.val());
            const max = parseInt($input.attr('max'));
            if (current < max) {
                $input.val(current + 1);
                updateButtons();
            }
        });

        $decrease.on('click', function() {
            let current = parseInt($input.val());
            const min = parseInt($input.attr('min'));
            if (current > min) {
                $input.val(current - 1);
                updateButtons();
            }
        });

        updateButtons();
    });

    // initialize date button
    $(function() {
        const $date = $('#inputDate');
        const today = new Date().toISOString().split('T')[0];
        $date.attr('min', today);

        if (!$date.val()) {
            $date.val(today);
        }
    });
    $('.input-group-text').on('click', () => {
        document.getElementById('inputDate').showPicker?.();
    });

    // time button
    $(function() {
        const $date = $('#inputDate');
        const $startTime = $('#startTime');
        const $endTime = $('#endTime');

        function getCurrentTimePlus(minutes = 1) {
            const now = new Date();
            now.setMinutes(now.getMinutes() + minutes);
            return now.toTimeString().slice(0, 5); // "HH:MM"
        }

        function isToday(dateStr) {
            const today = new Date().toISOString().split('T')[0];
            return dateStr === today;
        }

        function updateStartTimeMin() {
            const selectedDate = $date.val();
            if (isToday(selectedDate)) {
                const minTime = getCurrentTimePlus();
                $startTime.attr('min', minTime);

                // Force refresh the UI (required for some browsers)
                const val = $startTime.val();
                $startTime.val('');
                setTimeout(() => {
                    $startTime.val(val); // restore if still valid
                }, 10);
            } else {
                $startTime.removeAttr('min');
            }
        }

        function updateEndTimeMin() {
            const startVal = $startTime.val();
            if (startVal) {
                $endTime.attr('min', startVal);
                if ($endTime.val() && $endTime.val() <= startVal) {
                    $endTime.val('');
                }
            } else {
                $endTime.removeAttr('min');
            }
        }

        $date.on('change', updateStartTimeMin);
        $startTime.on('change', updateEndTimeMin);

        // Set default to today
        const today = new Date().toISOString().split('T')[0];
        updateStartTimeMin();
    });
</script>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>