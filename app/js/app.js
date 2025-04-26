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

    // Photo preview
    $('label.upload input[type=file]').on('change', e => {
        const f = e.target.files[0];
        const img = $(e.target).siblings('img')[0];

        if (!img) return;

        img.dataset.src ??= img.src;

        if (f?.type.startsWith('image/')) {
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = img.dataset.src;
            e.target.value = '';
        }
    });

    $('a[href="_forgot_password.php"]').on('click', function (e) {
        e.preventDefault();
        const email = prompt("Please enter your email:");
        if (email) {
            $.post('_forgot_password.php', { email: email }, function (response) {
                alert(response.message);
            }, 'json');
        }
    });

    $('#register-link').on('click', function (e) {
        e.preventDefault();
    });

    $(document).on('click', '#login-link', function (e) {
        e.preventDefault();
        window.location.href = '/pages/auth/login.php';
    });



    $('#register-link').on('click', function (e) {
        e.preventDefault();
        window.location.href = '/pages/auth/register.php';
    });

    // Toggle to login form 
    $('#login-link').on('click', function (e) {
        e.preventDefault();
        window.location.href = '/pages/auth/login.php';
    });


    loadCartNumber();

    const $cartIcon = $('.cart-icon');
    const $cartPopup = $('#cart-popup');
    const $cartContent = $('#cart-content');

    // Show popup on hover
    $cartIcon.hover(
        function () {
            $cartPopup.removeClass('hidden').fadeIn();
            loadCartDetails();
        },
        function () {
            setTimeout(function () {
                if (!$cartPopup.is(':hover')) {
                    $cartPopup.fadeOut();
                }
            }, 200);
        }
    );

    // Hide popup when mouse leaves the popup
    $cartPopup.mouseleave(function () {
        $cartPopup.fadeOut();
    });

    // Function to load cart details dynamically
    function loadCartDetails() {
        $.ajax({
            url: '/pages/cart/shopping-cart.php',
            type: 'GET',
            async: false,
            data: {
                action: 'get_cart_data'
            },
            success: function (response) {
                $cartContent.html(response);
            },
            error: function (xhr, status, error) {
                $cartContent.html('<p>Error loading cart details.</p>');
                console.error('Error fetching cart details:', error);
            },
        });
    }
});