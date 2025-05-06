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

let previousDate = $('#inputDate').val();

$('#inputDate').on('focus', function () {
    previousDate = $(this).val();
});

$('#inputDate').on('change', function () {
    if (!$(this).val()) {
        $(this).val(previousDate);
    }
});