$(() => {
    const hasSessionData = sessionStorage.getItem('__field_states__') !== null;
    if (performance.getEntriesByType("navigation")[0]?.type === "reload" && hasSessionData) {
        sessionStorage.removeItem('__field_states__');
        location.reload();
    }

    function updateSelectedTagUI(value) {
        const $selectedOption = $(`.tag-option[data-value="${value}"]`);
        if ($selectedOption.length) {
            const label = $selectedOption.data('label');
            const image = $selectedOption.data('image');

            $('#selectedTagLabel').text(label);
            $('#selectedTagImage').attr('src', image);
        }
    }

    $('#gatheringTag').on('input change', function () {
        const value = $(this).val();
        updateSelectedTagUI(value);
    });

    const initialTagValue = $('#gatheringTag').val();
    if (initialTagValue) {
        updateSelectedTagUI(initialTagValue);
    }

    const fields = ['gatheringTag', 'inputTheme', 'inputDate', 'inputPax', 'startTime', 'endTime'];
    const timeFields = ['inputDate', 'startTime', 'endTime'];
    const locationFields = ['inputLocation', 'locationId'];
    const fieldStates = {};
    const editFieldStates = {};
    fields.forEach(f => {
        const currentVal = $(`#${f}`).val() ?? '';
        fieldStates[f] = {
            valid: true,
            error: [],
            value: currentVal
        };
        editFieldStates[f] = {
            valid: true,
            touched: false,
            error: [],
            value: currentVal
        };
    });
    console.log(fieldStates);
    console.log(editFieldStates);
    updateButtons();
    toggleSubmitButton();

    // Submit Button
    $('#createGatheringFormEl').on('submit', function () {
        //sessionStorage.removeItem('__field_states__');
    });

    fields.forEach(fieldId => {
        $(`#${fieldId}`).on('input change', function () {
            const data = $(this).val();

            editFieldStates[fieldId].value = data;
            editFieldStates[fieldId].touched = true;

            if (timeFields.includes(fieldId)) {
                validateField(fieldId, datetimeDataHandler(fieldId));
            } else if (locationFields.includes(fieldId)) {
                validateField(fieldId, locationDataHandler());
            } else {
                validateField(fieldId, dataHandler(fieldId, data));
            }
            toggleSubmitButton();
        });
    });

    function validateField(fieldId, data) {
        //console.log(data);
        $gatheringId = $('#gatheringId').val();
        $.ajax({
            url: '/api/validate-gathering-edit',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                editingId: $gatheringId,
                data: data
            }),
            success: function (response) {
                console.log('Response: ', response);
                const isValid = response['valid'] === true;
                const errors = isValid ? [] : (Array.isArray(response['errors']) ? response['errors'] : [response['errors']]);

                if (data.field === 'time') {
                    const sharedError = isValid ? [] : (Array.isArray(errors) ? [...new Set(errors)] : [errors]);

                    // Apply the same error to both fields first
                    ['startTime', 'endTime'].forEach(field => {
                        editFieldStates[field].valid = isValid;
                        editFieldStates[field].error = sharedError;
                    });

                    // If both fields have the same error(s), clear one to avoid duplicate UI
                    if (
                        JSON.stringify(editFieldStates['startTime'].error) === JSON.stringify(editFieldStates['endTime'].error) &&
                        editFieldStates['startTime'].error.length > 0
                    ) {
                        editFieldStates['endTime'].error = [];
                    }

                    ['startTime', 'endTime'].forEach(renderValidation);
                } else {
                    // update only the touched field
                    const touched = data.touched;
                    editFieldStates[touched].valid = isValid;
                    editFieldStates[touched].error = errors;
                    renderValidation(touched);
                }

                toggleSubmitButton();
                console.log('State: ', editFieldStates);
            },
            error: function (xhr, status, error) {
                console.error('AJAX error: ', xhr.responseText);
                console.log(error);
            }
        });
    }

    function renderValidation(fieldId) {
        const state = editFieldStates[fieldId];
        const $input = $(`#${fieldId}`);
        const $error = $(`#error-${fieldId}`);

        const hasError = !state.valid && Array.isArray(state.error) && state.error.length > 0;

        if (hasError) {
            const uniqueErrors = [...new Set(state.error)];
            const msg = uniqueErrors.join('<br>');
            $input.addClass('is-invalid');
            $error.html(`${msg}*`).show();
        } else {
            $input.removeClass('is-invalid');
            $error.text('').hide();
        }
    }

    function toggleSubmitButton() {
        const hasChange = fields.some(fieldId => {
            const original = (fieldStates[fieldId]?.value || '').trim();
            const current = (editFieldStates[fieldId]?.value || '').trim();
            return current !== '' && current !== original;
        });

        const allValid = fields.every(fieldId => editFieldStates[fieldId]?.valid === true);

        $('#createBtn').prop('disabled', !(hasChange && allValid));
    }

    function locationDataHandler() {
        const inputLocation = $('#inputLocation');
        const locationId = $('#locationId');
        return {
            field: 'inputLocation',
            touched: 'inputLocation',
            value: {
                locationName: inputLocation,
                locationId: locationId
            }
        };
    }

    function datetimeDataHandler(fieldId) {
        const date = $('#inputDate').val();
        const startTime = $('#startTime').val();
        const endTime = $('#endTime').val();
        const nextDayNote = $('#nextDayNote');
        let startDatetime = '';
        let endDatetime = '';

        if (date && startTime) {
            startDatetime = new Date(`${date}T${startTime}`);
        }
        if (date && endTime) {
            console.log(endTime);
            endDatetime = new Date(`${date}T${endTime}`);
            if (startDatetime) {

                if (!isNaN(startDatetime.getTime()) && !isNaN(endDatetime.getTime())) {
                    if (endDatetime < startDatetime) {
                        endDatetime.setDate(endDatetime.getDate() + 1);
                        nextDayNote.show(); // 👈 show label
                    } else {
                        nextDayNote.hide(); // 👈 hide if not needed
                    }
                }
            }
        }

        const payload = {
            field: '', // will be assigned below
            touched: fieldId,
            value: {
                inputDate: date || '',
                startTime: startDatetime,
                endTime: endDatetime
            }
        };

        // Determine which field(s) were touched and the main field flag
        // field : inputDate, startTime, endTime, time
        if (fieldId === 'inputDate') {
            payload.field = 'inputDate';
        } else if (startTime && endTime) {
            payload.field = 'time';
        } else {
            payload.field = fieldId;
        }

        return payload;
    }

    function dataHandler(fieldId, data) {
        return {
            field: fieldId,
            touched: fieldId,
            value: data
        }
    }
    updateButtons();


    // // Submit Button
    // //setupSubmitButton(fields);
    // $('#createGatheringFormEl').on('submit', function (e) {
    //     const confirmSubmit = confirm('Are you sure you want to create this gathering?');
    //     if (!confirmSubmit) {
    //         e.preventDefault(); // cancel submission
    //         return;
    //     }
    //     fields.forEach(id => sessionStorage.removeItem(id));
    // });

    // // Reset Button
    // setupResetButton(fields);

    // // Gathering Tag
    // const currentTagValue = $('#gatheringTag').val();
    // if (currentTagValue) {
    //     const $selectedOption = $(`.tag-option[data-value="${currentTagValue}"]`);
    //     if ($selectedOption.length) {
    //         const label = $selectedOption.data('label');
    //         const image = $selectedOption.data('image');

    //         $('#selectedTagLabel').text(label);
    //         $('#selectedTagImage').attr('src', image);
    //     }
    // }

    // // Fields On Change
    // fields.forEach(fieldId => {
    //     const val = sessionStorage.getItem(fieldId);
    //     if (val !== null) {
    //         $(`#${fieldId}`).val(val).trigger('input');
    //         touched.add(fieldId);
    //     }


    //     $(`#${fieldId}`).on('input change', function () {
    //         touched.add(fieldId);
    //         sessionStorage.setItem(fieldId, $(this).val());
    //         if (timeFields.includes(fieldId)) {
    //             validateFields(timeFields, fields, touched);
    //         } else {
    //             validateFields([fieldId], fields, touched);
    //         }
    //         toggleSubmitButton(fields);
    //     });
    // });

    // function validateFields(fieldIds, allFields, touched) {
    //     const data = {
    //         touchedFields: fieldIds,
    //         gatheringId: $('#gatheringId').val()
    //     };
    //     allFields.forEach(id => {
    //         data[id] = $(`#${id}`).val();
    //     });

    //     console.log(data);
    //     console.log(data.touchedFields);

    //     $.ajax({
    //         url: '/api/validate-gathering-edit',
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
    // toggleSubmitButton(fields);
});
