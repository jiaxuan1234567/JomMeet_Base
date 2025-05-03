// $(() => {
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

$('#gatheringTag').on('input change', function () {
    const value = $(this).val();
    const $selectedOption = $(`.tag-option[data-value="${value}"]`);

    if ($selectedOption.length) {
        const label = $selectedOption.data('label');
        const image = $selectedOption.data('image');

        $('#selectedTagLabel').text(label);
        $('#selectedTagImage').attr('src', image);
    }
});

// ====== Pax Button Adjustment ======
const $increase = $('#increasePax');
const $decrease = $('#decreasePax');
const $inputDate = $('#inputDate');
const $inputPax = $('#inputPax');

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
    }
});

$decrease.on('click', function () {
    let current = parseInt($inputPax.val());
    const min = parseInt($inputPax.attr('min'));
    if (current > min) {
        $inputPax.val(current - 1).trigger('input');
        updateButtons();
    }
});

// ====== Open Date Picker on Icon Click ======
$('#triggerDatePicker').on('click', function () {
    $inputDate[0].showPicker?.();
});

// ====== Handle Validation Message ======
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

function clearAllValidationErrors(fields) {
    fields.forEach(fieldId => clearValidationError(fieldId));
}

let initialValues = {};

function storeInitialValues(fields) {
    initialValues = {};
    fields.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            initialValues[id] = el.value;
        }
    });
}

function isFormFilled(fields) {
    // return fields.every(id => {
    //     const currentVal = $(`#${id}`).val()?.trim() || '';
    //     const initialVal = initialValues[id]?.trim() || '';
    //     return currentVal !== '' && currentVal !== initialVal;
    // });
    return fields.every(id => {
        const val = $(`#${id}`).val();
        return val !== null && val.trim() !== '';
    });
}

// ====== Submit Button ======
const $submitBtn = $('#createBtn');

function toggleSubmitButton(fields) {
    const hasError = fields.some(id => $(`#${id}`).hasClass('is-invalid'));
    $('#createBtn').prop('disabled', hasError || !isFormFilled(fields));
}

function setupSubmitButton(fields) {
    $('#createGatheringFormEl').on('submit', function () {
        fields.forEach(id => sessionStorage.removeItem(id));
    });
    // $submitBtn.on('submit', function () {
    //     fields.forEach(id => sessionStorage.removeItem(id));
    // });
}


// ====== Reset Button ======
function setupResetButton(fields) {
    // const initialValues = {};

    // // Save initial field values
    // fields.forEach(id => {
    //     const el = document.getElementById(id);
    //     if (el) {
    //         initialValues[id] = el.value;
    //     }
    // });

    $('#createResetBtn').on('click', function (e) {
        e.preventDefault();

        fields.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.value = initialValues[id]?.trim() || '';
                sessionStorage.removeItem(id);
            }
        });

        if (initialValues['inputPax']) updateButtons?.();
        toggleSubmitButton?.(fields);
        clearAllValidationErrors?.(fields);
    });
}

// ====== AJAX Field Validation with Native Popup ======
// function validateFields(fieldIds, allFields, touched, urlEndpoint) {
//     const data = {
//         touchedFields: fieldIds
//     };
//     allFields.forEach(id => {
//         data[id] = $(`#${id}`).val();
//     });

//     console.log(data);
//     console.log(data.touchedFields);

//     $.ajax({
//         url: urlEndpoint,
//         method: 'POST',
//         contentType: 'application/json',
//         data: JSON.stringify(data),
//         success: function (response) {
//             const errors = response.errors || {};

//             touched.forEach(fieldId => {
//                 if (errors[fieldId]) {
//                     showValidationError(fieldId, errors[fieldId]);
//                 } else {
//                     clearValidationError(fieldId);
//                 }
//             });
//             toggleSubmitButton(allFields);
//         },
//         error: function (xhr) {
//             console.error('Validation failed:', xhr.responseText);
//         }
//     });
// }
// });