// ============================================================================
// General Functions
// ============================================================================



// ============================================================================
// Page Load (jQuery)
// ============================================================================

$(() => {

    // Autofocus
    $('form:not(.newsletter-form) :input:not(button):first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();

    // Confirmation message
    $('[data-confirm]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

    //   message
    $('[data-confirm-gathering]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure about joining the gathering?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

     //   message reflection
     $('[data-confirm-deleteReflection]').on('click', function (e) {
        const text = e.currentTarget.dataset.confirm || 'Confirm to delete your self-reflection record?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });
    


    // Initiate GET request
    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        location = url || location;
    });

    // Initiate POST request
    $('[data-post]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.post;
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    // Reset form
    $('[type=reset]').on('click', e => {
        e.preventDefault();
        location = location;
    });

    // Auto uppercase
    $('[data-upper]').on('input', e => {
        const a = e.target.selectionStart;
        const b = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(a, b);
    });

    // Accept digit character only
    $('[data-phone]').on('input', function () {
        // Remove all non-digit characters
        let phone = $(this).val().replace(/[^\d]/g, '');
        $(this).val(phone);
    });

    // Accept alphabet character only
    $('[data-alpha]').on('input', function () {
        // Remove all non-alphabet characters
        let text = $(this).val().replace(/[^a-zA-Z\s]/g, '');
        $(this).val(text);
    });
});