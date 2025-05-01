$(document).ready(function() {
    $('#selfReflectionForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax ({
            url:/reflection/validate,
            type: 'POST',
            data: { reflectionContent: $(reflectionContent).val() },
            success: function (response) {
                const res = JSON.parse(response);
                if (res.success) {
                    header("Location: /reflection");
                } else {
                    $('#reflectionContent').addClass('is-invalid');
                    $('#errorReflection').text(res.message);
                }
            },
            error: function() {
                alert('An error occured while validating.');
            }
        });
    });
});