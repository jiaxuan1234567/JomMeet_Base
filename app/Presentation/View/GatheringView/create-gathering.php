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

    button[type="reset"]:hover {
        background-color: #f8f9fa;
        /* Bootstrap light */
    }

    button#createBtn[disabled] {
        background-color: #e0dcdc !important;
        color: #b1a9a9 !important;
        cursor: not-allowed !important;
        border: none !important;
        opacity: 1 !important;
    }

    .pax-wrapper {
        background-color: #fff;
    }

    #inputPax {
        width: 60px;
        font-size: 1.2rem;
        background-color: #fff;
    }

    #decreasePax:disabled {
        background-color: #e0dcdc;
        color: #888;
    }

    #increasePax:disabled {
        background-color: #e0dcdc;
        color: #888;
    }

    .btn:disabled {
        opacity: 1;
    }

    .gathering-input-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 0.375rem;
        /* same as rounded */
        padding: 0.5rem 1rem;
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        /* shadow-sm */
        height: 48px;
        /* fixed height for all */
    }

    .gathering-input {
        background-color: transparent;
        border: none;
        box-shadow: none;
        font-weight: 600;
        padding: 0;
        text-align: center;
        height: 100%;
    }

    button.gathering-button {
        padding: 0.375rem 1rem;
        font-weight: 500;
        border: none;
        border-radius: 0.375rem;
    }

    .time-blue {
        background-color: #569FFF;
        color: #fff;
    }

    .time-blue::-webkit-calendar-picker-indicator {
        filter: invert(1);
        cursor: pointer;
    }
</style>

<div class="container-fluid" id="mainContent">
    <div id="createGatheringForm">
        <div class="d-flex mb-4 align-items-center border-bottom border-2 px-2 py-3">
            <div class="col-2">
                <a href="/my-gathering"><i class="bi bi-arrow-left text-black h3 m-0" id="back" style="cursor:pointer;"></i></a>
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
                    <div class="position-relative d-inline-block" style="height: 120px;">
                        <!-- Outer ring -->
                        <div class="rounded-circle border border-dark position-absolute top-50 start-50 translate-middle"
                            style="width: 120px; height: 120px;"></div>

                        <!-- Inner ring -->
                        <div class="rounded-circle border-1 bg-info position-absolute top-50 start-50 translate-middle"
                            style="width: 100px; height: 100px;">
                            <div class="d-flex align-items-center justify-content-center h-100 w-100">
                                <img src="https://cdn-icons-png.flaticon.com/512/1161/1161388.png"
                                    alt="icon"
                                    style="width: 60%; height: auto;">
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-3 fw-bold mb-0">Entertainment</h5>
                </div>

                <!-- Theme and Date -->
                <div class="col-12 row align-items-center mb-3">
                    <div class="col-1 text-end">
                        <label for="inputTheme" class="col-form-label fw-semibold">Theme</label>
                    </div>
                    <div class="col-5">
                        <div class="gathering-input-wrapper">
                            <input type="text"
                                id="inputTheme"
                                name="inputTheme"
                                class="form-control gathering-input w-100 text-start"
                                placeholder="Enter Gathering Theme"
                                value="<?= htmlspecialchars($_GET['inputTheme'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-1 text-end">
                        <label for="inputDate" class="col-form-label fw-semibold">Date</label>
                    </div>
                    <div class="col-5">
                        <div class="gathering-input-wrapper">
                            <input type="date"
                                id="inputDate"
                                name="inputDate"
                                class="form-control gathering-input text-center"
                                value="<?= htmlspecialchars($_GET['inputDate'] ?? '') ?>">
                            <button type="button" id="triggerDatePicker"
                                class="btn btn-primary button-blue-color text-white gathering-button">
                                <i class="bi bi-calendar-event-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pax and Time -->
                <div class="col-12 row align-items-center mb-3">
                    <div class="col-1 text-end">
                        <label for="inputPax" class="col-form-label fw-semibold">No. Pax</label>
                    </div>
                    <div class="col-5">
                        <div class="gathering-input-wrapper">
                            <button class="btn btn-primary button-blue-color text-white gathering-button"
                                id="decreasePax" type="button">−</button>

                            <input type="number"
                                id="inputPax"
                                name="inputPax"
                                class="form-control gathering-input text-center"
                                value="<?= htmlspecialchars($_GET['inputPax'] ?? '3') ?>"
                                min="3" max="8" readonly
                                style="max-width: 60px;">

                            <button class="btn btn-primary button-blue-color text-white gathering-button"
                                id="increasePax" type="button">+</button>
                        </div>
                    </div>


                    <div class="col-1 text-end">
                        <label for="startTime" class="col-form-label fw-semibold">Time</label>
                    </div>
                    <div class="col-5">
                        <div class="gathering-input-wrapper gap-2">
                            <input type="time"
                                id="startTime"
                                name="startTime"
                                class="form-control gathering-input text-center time-blue btn btn-primary"
                                value="<?= htmlspecialchars($_GET['startTime'] ?? '') ?>">

                            <span class="fw-bold">to</span>

                            <input type="time"
                                id="endTime"
                                name="endTime"
                                class="form-control gathering-input text-center time-blue btn btn-primary"
                                value="<?= htmlspecialchars($_GET['endTime'] ?? '') ?>"
                                <?= isset($_GET['endTime']) ? 'data-persisted="true"' : '' ?>>
                        </div>
                    </div>

                </div>

                <!-- Location -->
                <div class="col-12 row align-items-center mb-3">
                    <div class="col-1 text-end">
                        <label for="inputLocation" class="col-form-label fw-semibold">Location</label>
                    </div>
                    <div class="col-11">
                        <div class="gathering-input-wrapper">
                            <input type="text"
                                id="inputLocation"
                                name="inputLocation"
                                class="form-control gathering-input"
                                placeholder="Select a location"
                                value="<?= htmlspecialchars($_GET['locationName'] ?? '') ?>"
                                readonly
                                style="cursor: default;">

                            <input type="hidden" name="locationId" value="<?= htmlspecialchars($_GET['locationID'] ?? '') ?>">

                            <button type="button"
                                class="btn btn-primary button-blue-color text-white gathering-button"
                                id="chooseLocationBtn">
                                Choose
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="col-12 d-flex justify-content-center gap-3 mt-4">
                    <button type="reset" class="btn border-black rounded-1 px-4 py-2 fw-semibold" id="createResetBtn">
                        Reset
                    </button>
                    <button type="submit" class="btn btn-primary py-2 px-4 button-blue-color border-0" id="createBtn">Create</button>
                </div>

            </form>
        </div>
    </div>

    <div id="selectLocationForm"></div>
</div>

<script src="/js/gatheringMap.js"></script>

<script>
    // ====== Choose Location Button ======
    $('#chooseLocationBtn').on('click', function() {
        const query = new URLSearchParams({
            inputTheme: $('#inputTheme').val(),
            inputPax: $('#inputPax').val(),
            inputDate: $('#inputDate').val(),
            startTime: $('#startTime').val(),
            endTime: $('#endTime').val()
        }).toString();

        window.location.href = `/my-gathering/create/location?${query}`;
    });

    // ====== Pax Button Adjustment ======
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
                toggleSubmitButton();
            }
        });

        $decrease.on('click', function() {
            let current = parseInt($input.val());
            const min = parseInt($input.attr('min'));
            if (current > min) {
                $input.val(current - 1);
                updateButtons();
                toggleSubmitButton();
            }
        });

        updateButtons();
    });

    // ====== Date Setup ======
    $(function() {
        const $date = $('#inputDate');
        const today = new Date().toISOString().split('T')[0];
        $date.attr('min', today);

        if (!$date.val()) {
            $date.val(today);
        }
        $date.trigger('change');
    });

    // ====== Open Date Picker on Icon Click ======
    $('#triggerDatePicker').on('click', function() {
        document.getElementById('inputDate').showPicker?.();
    });

    // ====== Time Picker Logic ======
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

                const val = $startTime.val();
                $startTime.val('');
                setTimeout(() => {
                    $startTime.val(val);
                    toggleSubmitButton();
                }, 10);
            } else {
                $startTime.removeAttr('min');
            }
        }

        function updateEndTimeMin() {
            const startVal = $startTime.val();
            if (startVal) {
                $endTime.attr('min', startVal);
                if ($endTime.val() && $startTime.val() && $endTime.val() < startVal && !$endTime.data('persisted')) {
                    $endTime.val('');
                }
            } else {
                $endTime.removeAttr('min');
            }
            toggleSubmitButton();
        }

        $date.on('change', updateStartTimeMin);
        //$date.on('change', updateEndTimeMin);
        $startTime.on('change', updateEndTimeMin);

        updateStartTimeMin();
        setTimeout(updateEndTimeMin, 50);
        //updateEndTimeMin();
    });

    // ====== Create Button Enable/Disable Logic ======
    function isFormValid() {
        return $('#inputTheme').val().trim() !== '' &&
            $('#inputDate').val().trim() !== '' &&
            $('#inputPax').val().trim() !== '' &&
            $('#startTime').val().trim() !== '' &&
            $('#endTime').val().trim() !== '' &&
            $('#inputLocation').val().trim() !== '';
    }

    function toggleSubmitButton() {
        $('button#createBtn[type="submit"]').prop('disabled', !isFormValid());
    }

    $(function() {
        $('#inputTheme, #inputDate, #inputPax, #startTime, #endTime, #inputLocation').on('input change', toggleSubmitButton);

        toggleSubmitButton();
    });

    // ====== Reset Button ======
    $('#createResetBtn').on('click', function() {
        // Clear all values manually
        $('#inputTheme, #inputDate, #startTime, #endTime, #inputLocation, input[name="locationId"]').val('');
        $('#inputPax').val(3);

        // Disable create button
        toggleSubmitButton();

        // Remove query string from URL
        const cleanUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    });
</script>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>