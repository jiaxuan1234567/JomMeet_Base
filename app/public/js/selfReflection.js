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