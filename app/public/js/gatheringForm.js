// ====== Preference ======
$('#selectTagBtn').on('click', function () {
    $('#tagSelectionModal').modal('show');
});

$('.tag-option').on('click', function () {
    const value = $(this).data('value');
    const label = $(this).data('label');
    const image = $(this).data('image');

    $('#gatheringTag').val(value).trigger('input');
    $('#selectedTagLabel').text(label);
    $('#selectedTagImage').attr('src', image);
    $('#tagSelectionModal').modal('hide');
});

$(function () {
    const $inputTag = $('#gatheringTag');
    //const $inputTheme = $('#inputTheme');
    const $inputDate = $('#inputDate');
    const $inputPax = $('#inputPax');
    //const $startTime = $('#startTime');
    //const $endTime = $('#endTime');
    //const $inputLocation = $('#inputLocation');
    const $createBtn = $('#createBtn');
    const $increase = $('#increasePax');
    const $decrease = $('#decreasePax');

    // ====== Pax Button Adjustment ======
    function updateButtons() {
        const val = parseInt($inputPax.val());
        const min = parseInt($inputPax.attr('min'));
        const max = parseInt($inputPax.attr('max'));
        $decrease.prop('disabled', val <= min);
        $increase.prop('disabled', val >= max);
    }

    $increase.on('click', function () {
        let current = parseInt($inputPax.val());
        const max = parseInt($inputPax.attr('max'));
        if (current < max) {
            $inputPax.val(current + 1).trigger('input');
            updateButtons();
            toggleSubmitButton();
        }
    });

    $decrease.on('click', function () {
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
    $('#triggerDatePicker').on('click', function () {
        $inputDate[0].showPicker?.();
    });

    // ====== Time Picker Logic ======
    function getCurrentTimePlus(minutes = 1) {
        const now = new Date();
        now.setMinutes(now.getMinutes() + minutes);
        return now.toTimeString().slice(0, 5);
    }

    const touched = new Set();
    const fields = ['gatheringTag', 'inputTheme', 'inputDate', 'inputPax', 'startTime', 'endTime', 'inputLocation', 'locationId'];

    // ====== Create Button Enable/Disable Logic ======
    function isFormFilled() {
        return fields.every(id => {
            const val = $(`#${id}`).val();
            return val !== null && val.trim() !== '';
        });
    }

    // Create Button State
    function toggleSubmitButton() {
        const hasError = fields.some(id => $(`#${id}`).hasClass('is-invalid'));
        $createBtn.prop('disabled', hasError || !isFormFilled());
    }
    toggleSubmitButton();

    // ====== Reset Button ======
    $('#createResetBtn').on('click', function () {
        fields.forEach(fieldId => {
            if (fieldId === 'inputPax') {
                const min = parseInt($('#inputPax').attr('min'));
                $('#inputPax').val(min);
            } else {
                $(`#${fieldId}`).val('');
            }
        });
        updateButtons();
        toggleSubmitButton();
        const cleanUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    });

    // ====== AJAX Field Validation with Native Popup ======
    function validateFields(fieldIds) {
        const data = {
            touchedFields: fieldIds
        };
        fields.forEach(id => {
            data[id] = $(`#${id}`).val();
        });

        console.log(data);
        console.log(data.touchedFields);

        $.ajax({
            url: '/api/validate-gathering',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                const errors = response.errors || {};

                touched.forEach(fieldId => {
                    if (errors[fieldId]) {
                        showValidationError(fieldId, errors[fieldId]);
                    } else {
                        clearValidationError(fieldId);
                    }
                });
                toggleSubmitButton();
            },
            error: function (xhr) {
                console.error('Validation failed:', xhr.responseText);
            }
        });
    }

    // Input Fields On Change
    fields.forEach(fieldId => {
        const timeFields = ['inputDate', 'startTime', 'endTime'];
        const val = sessionStorage.getItem(fieldId);
        if (val !== null) {
            $(`#${fieldId}`).val(val).trigger('input');
            touched.add(fieldId);

            if (timeFields.includes(fieldId)) {
                validateFields(timeFields);
            } else {
                validateFields([fieldId]);
            }

            if (fieldId === 'inputPax') updateButtons();
        }

        $(`#${fieldId}`).on('input change', function () {
            touched.add(fieldId);
            sessionStorage.setItem(fieldId, $(this).val());

            if (timeFields.includes(fieldId)) {
                touched.add(fieldId);
                validateFields(timeFields);
            } else {
                validateFields([fieldId]);
            }
            toggleSubmitButton();
        });
    });

    // Form Submitted Clear Saved State
    $('#createBtn').on('submit', function () {
        fields.forEach(id => sessionStorage.removeItem(id));
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
        const $selectedOption = $(`.tag-option[data-value="${tagValue}"]`);
        if ($selectedOption.length) {
            const label = $selectedOption.data('label');
            const image = $selectedOption.data('image');

            $('#selectedTagLabel').text(label);
            $('#selectedTagImage').attr('src', image);
        }
    }
});