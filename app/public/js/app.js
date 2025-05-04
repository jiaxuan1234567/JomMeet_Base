// ============================================================================
// General Functions
// ============================================================================
// header active nav
$(document).ready(function () {
    const currentPath = window.location.pathname;

    $('.navbar .nav-link').each(function () {
        const href = $(this).attr('href');

        if (
            href === currentPath ||
            (currentPath.startsWith('/gathering') && href === '/gathering' && !currentPath.startsWith('/my-gathering')) ||
            (currentPath.startsWith('/my-gathering') && href === '/my-gathering')
        ) {
            $(this).addClass('active');
        }
    });
});

// flash Message
$(function () {
    const $msg = $('#flashMessage');
    const type = $msg.data('type');
    const text = $msg.data('msg');

    if ((type !== 'success' && type !== 'error') || !text) return;

    // Add Bootstrap Icon before the text
    const icons = {
        success: 'bi-check-circle',
        error: 'bi-x-circle'
    };

    const iconClass = icons[type];
    $msg.prepend(`<i class="bi ${iconClass} me-2" style="font-size: 35px;"></i> ${text}`);

    // Base styles
    $msg.css({
        position: 'absolute',
        top: '15px',
        left: '50%',
        transform: 'translateX(-50%)',
        display: 'flex',
        'align-items': 'center',
        'justify-content': 'center',
        padding: '0px 25px',
        'border-radius': '10px',
        'z-index': 9999,
        'font-weight': 'bold',
        'font-size': '15px',
        'text-align': 'center',
        'box-shadow': '0 4px 8px rgba(0,0,0,0.2)',
        color: 'white',
        'min-width': '300px'
    });

    // Type-specific styles
    const styles = {
        success: { backgroundColor: '#13E300' },
        error: { backgroundColor: '#D94343' }
    };

    $msg.css(styles[type]);

    // Auto-fade
    setTimeout(() => $msg.fadeOut(500), 3000);
});

// back button
$(function () {
    $('#backToLastPage').on('click', function (e) {
        e.preventDefault();
        window.history.back();
    });
});

// Storage Clear
$(function () {
    const allowedPaths = [
        '/my-gathering/create',
        '/my-gathering/create/location'
    ];

    const currentPath = window.location.pathname;

    if (!allowedPaths.includes(currentPath)) {
        const fields = ['gatheringTag', 'inputTheme', 'inputDate', 'inputPax', 'startTime', 'endTime', 'inputLocation', 'locationId'];
        fields.forEach(id => sessionStorage.removeItem(id));
        //sessionStorage.removeItem('locationId');
        sessionStorage.removeItem('__field_states__');
    }
});


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

    $('[data-confirm-updateReflection]').on('click', function (e) {
        const text = e.currentTarget.dataset.confirm || 'Confirm to update your self-reflection record?';
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