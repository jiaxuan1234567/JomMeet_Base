$(() => {
    const fields = ['gatheringTag', 'inputTheme', 'inputDate', 'inputPax', 'startTime', 'endTime', 'inputLocation', 'locationId'];
    const timeFields = ['inputDate', 'startTime', 'endTime'];
    const touched = new Set();

    storeInitialValues(fields);
    updateButtons();


    // Submit Button
    //    setupSubmitButton(fields);
    $('#createGatheringFormEl').on('submit', function () {
        fields.forEach(id => sessionStorage.removeItem(id));
    });

    // Reset Button
    setupResetButton(fields);

    // Fields On Change
    fields.forEach(fieldId => {
        const val = sessionStorage.getItem(fieldId);
        if (val !== null) {
            $(`#${fieldId}`).val(val).trigger('input');
            touched.add(fieldId);
        }


        $(`#${fieldId}`).on('input change', function () {
            touched.add(fieldId);
            sessionStorage.setItem(fieldId, $(this).val());
            if (timeFields.includes(fieldId)) {
                validateFields(timeFields, fields, touched);
            } else {
                validateFields([fieldId], fields, touched);
            }
            toggleSubmitButton(fields);
        });
    });

    function validateFields(fieldIds, allFields, touched) {
        const data = {
            touchedFields: fieldIds
        };
        allFields.forEach(id => {
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
                toggleSubmitButton(allFields);
            },
            error: function (xhr) {
                console.error('Validation failed:', xhr.responseText);
            }
        });
    }
    toggleSubmitButton(fields);
});