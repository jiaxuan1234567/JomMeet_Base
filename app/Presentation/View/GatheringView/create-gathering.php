<?php
$_title = 'Create Gathering';
require_once __DIR__ . '/../HomeView/header.php';

$asset = new FileHelper('asset');
?>

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
            <form class="row my-4" id="createGatheringFormEl" action="/my-gathering/create" method="post">

                <!-- Image + Gathering Tag -->
                <div class="col-12 text-center mb-4">
                    <!-- Outer ring wrapper -->
                    <div class="position-relative d-inline-block" style="width: 130px; height: 130px;">
                        <!-- Ring border -->
                        <div class="rounded-circle border border-dark w-100 h-100 position-absolute top-0 start-0"></div>
                        <button type="button"
                            class="btn rounded-circle overflow-hidden p-0 border-0 position-absolute top-50 start-50 translate-middle"
                            id="selectTagBtn"
                            style="width: 120px; height: 120px;">

                            <!-- Tag Image -->
                            <img src="<?= $asset->getFilePath('iconPNG') ?>"
                                id="selectedTagImage"
                                class="w-100 h-100 rounded-circle object-fit-cover">

                            <!-- Overlay with icon -->
                            <div class="edit-icon-overlay position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center rounded-circle bg-opacity-25">
                                <i class="bi bi-pencil-square text-white display-4"></i>
                            </div>
                        </button>
                    </div>
                    <h5 class="mt-3 fw-bold mb-0" id="selectedTagLabel">Select A Preference</h5>
                    <input type="hidden" name="gatheringTag" id="gatheringTag" value="<?= htmlspecialchars($_GET['inputTag'] ?? '') ?>">
                </div>

                <!-- Theme and Date -->
                <div class="col-12 row align-items-center mb-4">
                    <div class="col-1 text-end">
                        <label for="inputTheme" class="col-form-label fw-semibold">Theme</label>
                    </div>
                    <div class="col-5">
                        <div class="gathering-input-wrapper position-relative">
                            <input type="text" id="inputTheme" name="inputTheme"
                                class="form-control gathering-input w-100 text-start"
                                placeholder="Enter Gathering Theme"
                                value="<?= htmlspecialchars($_GET['inputTheme'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-1 text-end">
                        <label for="inputDate" class="col-form-label fw-semibold">Date</label>
                    </div>
                    <div class="col-5">
                        <div class="gathering-input-wrapper position-relative">
                            <input type="date"
                                id="inputDate"
                                name="inputDate"
                                class="form-control gathering-input text-center"
                                value="<?= htmlspecialchars($_GET['inputDate'] ?? $allowedDate) ?>"
                                min="<?= $allowedDate ?>">
                            <button type="button" id="triggerDatePicker"
                                class="btn btn-primary button-blue-color text-white gathering-button">
                                <i class="bi bi-calendar-event-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pax and Time -->
                <div class="col-12 row align-items-center mb-4">
                    <div class="col-1 text-end">
                        <label for="inputPax" class="col-form-label fw-semibold">No. Pax</label>
                    </div>
                    <div class="col-5">
                        <div class="gathering-input-wrapper position-relative">
                            <button class="btn btn-primary button-blue-color text-white gathering-button"
                                id="decreasePax" type="button">−</button>

                            <input type="number"
                                id="inputPax"
                                name="inputPax"
                                class="form-control gathering-input text-center"
                                value="<?= htmlspecialchars($_GET['inputPax'] ?? $paxLimit['minPax']) ?>"
                                min="<?= $paxLimit['minPax'] ?>" max="<?= $paxLimit['maxPax'] ?>" readonly
                                style="max-width: 60px;">

                            <button class="btn btn-primary button-blue-color text-white gathering-button"
                                id="increasePax" type="button">+</button>
                        </div>
                    </div>

                    <div class="col-1 text-end">
                        <label for="startTime" class="col-form-label fw-semibold">Time</label>
                    </div>
                    <div class="col-5">
                        <div class="d-flex gathering-input-wrapper gap-2 ">
                            <div class="flex-grow-1 position-relative">
                                <input type="time"
                                    id="startTime"
                                    name="startTime"
                                    class="form-control gathering-input text-center time-blue btn btn-primary"
                                    value="<?= htmlspecialchars($_GET['startTime'] ?? '') ?>">
                            </div>

                            <span class="fw-bold d-flex align-items-center">to</span>

                            <div class="flex-grow-1 position-relative">
                                <input type="time"
                                    id="endTime"
                                    name="endTime"
                                    class="form-control gathering-input text-center time-blue btn btn-primary"
                                    value="<?= htmlspecialchars($_GET['endTime'] ?? '') ?>"
                                    <?= isset($_GET['endTime']) ? 'data-persisted="true"' : '' ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="col-12 row align-items-center mb-4">
                    <div class="col-1 text-end">
                        <label for="inputLocation" class="col-form-label fw-semibold">Location</label>
                    </div>
                    <div class="col-11">
                        <div class="gathering-input-wrapper position-relative">
                            <input type="text"
                                id="inputLocation"
                                name="inputLocation"
                                class="form-control gathering-input"
                                placeholder="Select a location"
                                value="<?= htmlspecialchars($_GET['locationName'] ?? '') ?>"
                                readonly
                                style="cursor: default;">

                            <input type="hidden" name="locationId" id="locationId" value="<?= htmlspecialchars($_GET['locationID'] ?? '') ?>">

                            <!-- <button type="button"
                                class="btn btn-primary button-blue-color text-white gathering-button"
                                id="chooseLocationBtn">
                                Choose
                            </button> -->
                            <a href="/my-gathering/create/location"
                                class="btn btn-primary button-blue-color text-white gathering-button"
                                id="chooseLocationBtn">
                                Choose
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-start">
                    <div class="text-danger small error-message" id="error-inputTheme"></div>
                    <div class="text-danger small error-message" id="error-inputDate"></div>
                    <div class="text-danger small error-message" id="error-inputPax"></div>
                    <div class="text-danger small error-message" id="error-startTime"></div>
                    <div class="text-danger small error-message" id="error-endTime"></div>
                    <div class="text-danger small error-message" id="error-inputLocation"></div>
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

    <!-- Select Preference Modal -->
    <div class="modal fade" id="tagSelectionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3 rounded-4 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Select a Preference</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row row-cols-3 g-4 justify-content-center">
                        <?php foreach ($preferenceTags as $tag): ?>
                            <div class="col text-center tag-option px-2"
                                data-value="<?= $tag['value'] ?>"
                                data-label="<?= $tag['label'] ?>"
                                data-image="<?= $tag['image'] ?>"
                                style="cursor: pointer;">
                                <div class="position-relative mx-auto"
                                    style="width: 80px; height: 80px;">
                                    <img src="<?= $tag['image'] ?>"
                                        class="rounded-circle border border-2 border-secondary shadow-sm tag-image w-100 h-100"
                                        style="object-fit: cover;">
                                </div>
                                <p class="small fw-semibold mt-2 mb-0"><?= $tag['label'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/gatheringMap.js"></script>

<script>
    // ====== Preference ======
    $('#selectTagBtn').on('click', function() {
        $('#tagSelectionModal').modal('show');
    });

    $('.tag-option').on('click', function() {
        const value = $(this).data('value');
        const label = $(this).data('label');
        const image = $(this).data('image');

        $('#gatheringTag').val(value).trigger('input');
        $('#selectedTagLabel').text(label);
        $('#selectedTagImage').attr('src', image);
        $('#tagSelectionModal').modal('hide');
    });

    (function() {
        const $inputTag = $('#gatheringTag');
        const $inputTheme = $('#inputTheme');
        const $inputDate = $('#inputDate');
        const $inputPax = $('#inputPax');
        const $startTime = $('#startTime');
        const $endTime = $('#endTime');
        const $inputLocation = $('#inputLocation');
        const $createBtn = $('#createBtn');
        const $increase = $('#increasePax');
        const $decrease = $('#decreasePax');

        // // ====== Choose Location Button ======
        // $('#chooseLocationBtn').on('click', function() {
        //     const query = new URLSearchParams({
        //         inputTag: $inputTag.val(),
        //         inputTheme: $inputTheme.val(),
        //         inputPax: $inputPax.val(),
        //         inputDate: $inputDate.val(),
        //         startTime: $startTime.val(),
        //         endTime: $endTime.val()
        //     }).toString();

        //     window.location.href = `/my-gathering/create/location?${query}`;
        // });

        // ====== Pax Button Adjustment ======
        function updateButtons() {
            const val = parseInt($inputPax.val());
            const min = parseInt($inputPax.attr('min'));
            const max = parseInt($inputPax.attr('max'));
            $decrease.prop('disabled', val <= min);
            $increase.prop('disabled', val >= max);
        }

        $increase.on('click', function() {
            let current = parseInt($inputPax.val());
            const max = parseInt($inputPax.attr('max'));
            if (current < max) {
                $inputPax.val(current + 1).trigger('input');
                updateButtons();
                toggleSubmitButton();
            }
        });

        $decrease.on('click', function() {
            let current = parseInt($inputPax.val());
            const min = parseInt($inputPax.attr('min'));
            if (current > min) {
                $inputPax.val(current - 1).trigger('input');
                updateButtons();
                toggleSubmitButton();
            }
        });

        updateButtons();

        // ====== Open Date Picker on Icon Click ======
        $('#triggerDatePicker').on('click', function() {
            $inputDate[0].showPicker?.();
        });

        // ====== Time Picker Logic ======
        function getCurrentTimePlus(minutes = 1) {
            const now = new Date();
            now.setMinutes(now.getMinutes() + minutes);
            return now.toTimeString().slice(0, 5);
        }

        // ====== Create Button Enable/Disable Logic ======
        function isFormFilled() {
            return $inputTag.val().trim() !== '' &&
                $inputTheme.val().trim() !== '' &&
                $inputDate.val().trim() !== '' &&
                $inputPax.val().trim() !== '' &&
                $startTime.val().trim() !== '' &&
                $endTime.val().trim() !== '' &&
                $inputLocation.val().trim() !== '';
        }

        console.log('Filled check:', {
            tag: $inputTag.val(),
            theme: $inputTheme.val(),
            date: $inputDate.val(),
            pax: $inputPax.val(),
            start: $startTime.val(),
            end: $endTime.val(),
            location: $inputLocation.val()
        });

        const touched = new Set();
        const fields = ['gatheringTag', 'inputTheme', 'inputDate', 'inputPax', 'startTime', 'endTime', 'inputLocation', 'locationId'];

        // Create Button State
        function toggleSubmitButton() {
            const hasError = fields.some(id => $(`#${id}`).hasClass('is-invalid'));
            $createBtn.prop('disabled', hasError || !isFormFilled());
        }
        toggleSubmitButton();

        // ====== Reset Button ======
        $('#createResetBtn').on('click', function() {
            $('#inputTheme, #inputDate, #startTime, #endTime, #inputLocation, #locationId').val('');
            $inputPax.val($inputPax.attr('min'));
            updateButtons();
            toggleSubmitButton();
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        });

        // ====== AJAX Field Validation with Native Popup ======
        function validateField(fieldId) {
            const $input = $(`#${fieldId}`);
            const data = {
                inputTheme: $inputTheme.val(),
                inputDate: $inputDate.val(),
                inputPax: $inputPax.val(),
                startTime: $startTime.val(),
                endTime: $endTime.val(),
                inputLocation: $inputLocation.val(),
                locationId: $('input[name="locationId"]').val(),
                inputTag: $('#gatheringTag').val()
            };

            console.log(data);

            $.post('/api/validate-gathering', data, function(response) {
                const errors = response.errors || {};

                // only update the fields the user has touched
                touched.forEach(fieldId => {
                    if (errors[fieldId]) {
                        showValidationError(fieldId, errors[fieldId]);
                    } else {
                        clearValidationError(fieldId);
                    }
                });

                toggleSubmitButton();
            }, 'json');
        }

        // Input Fields On Change
        fields.forEach(fieldId => {
            const val = sessionStorage.getItem(fieldId);
            if (val !== null) {
                $(`#${fieldId}`).val(val).trigger('input');
                if (fieldId === 'inputPax') updateButtons();
            }

            $(`#${fieldId}`).on('input change', function() {
                sessionStorage.setItem(fieldId, $(this).val());
                touched.add(fieldId);

                // when date changes, also force re-validate times
                if (fieldId === 'inputDate') {
                    touched.add('startTime');
                }

                validateField(fieldId);
                toggleSubmitButton();
            });
        });

        // Form Submitted Clear Saved State
        $('#createBtn').on('submit', function() {
            formFields.forEach(id => sessionStorage.removeItem(id));
        });

        function showValidationError(fieldId, message) {
            const $input = $(`#${fieldId}`);
            $input.addClass('is-invalid');
            $(`#error-${fieldId}`).text(message + '*').show();
        }

        function clearValidationError(fieldId) {
            const $input = $(`#${fieldId}`);
            $input.removeClass('is-invalid');
            $(`#error-${fieldId}`).text('').hide();
        }

        const tagValue = $inputTag.val();
        if (tagValue) {
            // Match it with your PHP-rendered tag list
            const $selectedOption = $(`.tag-option[data-value="${tagValue}"]`);
            if ($selectedOption.length) {
                const label = $selectedOption.data('label');
                const image = $selectedOption.data('image');

                $('#selectedTagLabel').text(label);
                $('#selectedTagImage').attr('src', image);
            }
        }
    })();
</script>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>