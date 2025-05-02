$(document).ready(function () {
    // $('#selfReflectionForm').on('submit', function (e) {
    //     e.preventDefault();

    //     $.ajax({
    //         url: /reflection/validate,
    //         type: 'POST',
    //         data: { reflectionContent: $(reflectionContent).val() },
    //         success: function (response) {
    //             const res = JSON.parse(response);
    //             if (res.success) {
    //                 header("Location: /reflection");
    //             } else {
    //                 $('#reflectionContent').addClass('is-invalid');
    //                 $('#errorReflection').text(res.message);
    //             }
    //         },
    //         error: function () {
    //             alert('An error occured while validating.');
    //         }
    //     });
    // });

    const $title = $('#reflectionTitle');
    const $content = $('#reflectionContent');
    const $errorBox = $('#errorReflection');

    function validateField(field) {
        const data = {};
        data[field] = field === 'reflectionTitle' ? $title.val() : $content.val();

        $.ajax({
            url: '/api/validate-reflection',
            type: 'POST',
            data: data,
            success: function (res) {
                if (res.success) {
                    console.log(res.success);
                    $('#error' + (field === 'reflectionTitle' ? 'ReflectionTitle' : 'ReflectionContent')).text('');
                } else {
                    $('#error' + (field === 'reflectionTitle' ? 'ReflectionTitle' : 'ReflectionContent')).text(res.message);
                }
            }
        });
    }

    $title.on('input change', () => validateField('reflectionTitle'));
    $content.on('input change', () => validateField('reflectionContent'));
});

document.addEventListener("DOMContentLoaded", function () {
    const titleInput = document.getElementById('reflectionTitle');
    const contentInput = document.getElementById('reflectionContent');
    const submitBtn = document.querySelector('button[type="submit"]');
    const errorTitle = document.getElementById('errorReflectionTitle');
    const errorContent = document.getElementById('errorReflectionContent');

    function validateForm() {
        let isValid = true;

        const title = titleInput.value.trim();
        const content = contentInput.value.trim();

        if (!title) {
            isValid = false;
        } else if (title.length > 50) {
            isValid = false;
        }

        if (!content) {
            isValid = false;
        } else if (content.length > 5000) {
            isValid = false;
        }

        if (!isValid) {
            submitBtn.disabled = true;
            submitBtn.style.backgroundColor = 'grey';
            submitBtn.style.borderColor = 'grey';
            submitBtn.style.cursor = 'not-allowed';
            submitBtn.style.opacity = '0.7';
        } else {
            submitBtn.disabled = false;
            submitBtn.style.backgroundColor = ''; // Reset to Bootstrap default
            submitBtn.style.borderColor = '';
            submitBtn.style.cursor = '';
            submitBtn.style.opacity = '';
        }
    }

    titleInput.addEventListener('input', validateForm);
    contentInput.addEventListener('input', validateForm);

    validateForm();
});




