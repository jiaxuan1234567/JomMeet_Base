$(() => {
    // reload clear saved data
    const hasSessionData = sessionStorage.getItem('__field_states__') !== null;
    if (performance.getEntriesByType("navigation")[0]?.type === "reload" && hasSessionData) {
        sessionStorage.removeItem('__field_states__');
        location.reload();
    }

    $('#nextDayNote').hide();

    const fields = ['gatheringTag', 'inputTheme', 'inputDate', 'inputPax', 'startTime', 'endTime', 'inputLocation'];
    const fieldStates = {};
    fields.forEach(f => {
        fieldStates[f] = {
            valid: false,
            error: [],
            value: ''
        };
    });

    // defined variables
    const timeFields = ['inputDate', 'startTime', 'endTime'];
    const locationFields = ['inputLocation', 'locationId'];

    const savedFieldStates = sessionStorage.getItem('__field_states__');
    if (savedFieldStates) {
        const parsed = JSON.parse(savedFieldStates);
        Object.entries(parsed).forEach(([fieldId, state]) => {
            fieldStates[fieldId] = state;
            $(`#${fieldId}`).val(state.value);
            renderValidation(fieldId);
        });
    }

    //storeInitialValues(fields);
    updateButtons();

    // Submit Button
    $('#createGatheringFormEl').on('submit', function () {
        //fields.forEach(id => sessionStorage.removeItem(id));
        sessionStorage.removeItem('__field_states__');
    });


    fields.forEach(fieldId => {
        // const val = sessionStorage.getItem(fieldId);
        // if (val !== null) {
        //     $(`#${fieldId}`).val(val).trigger('input');
        //     //touched.add(fieldId);
        // }

        $(`#${fieldId}`).on('input change', function () {
            const data = $(this).val();

            fieldStates[fieldId].value = data;
            //sessionStorage.setItem(fieldId, data);

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
    const id = sessionStorage.getItem('locationId');
    if (id && fieldStates['inputLocation']?.value) {
        $('#inputLocation').val(fieldStates['inputLocation'].value);
        $('#locationId').val(id);
        validateField('inputLocation', locationDataHandler());
    }
    if ($('#inputDate').val()) {
        validateField('inputDate', datetimeDataHandler('inputDate'));
    }
    const inputPax = $('inputPax').val();
    if (inputPax) {
        validateField('inputPax', inputPax);
    }

    $('#createResetBtn').on('click', function (e) {
        e.preventDefault();

        fields.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.value = initialValues[id]?.trim() || '';
                fieldStates[id] = { valid: false, error: [], value: '' };
                renderValidation(id);
            }
        });

        if (initialValues['inputPax']) updateButtons?.();
        sessionStorage.removeItem('__field_states__');
        toggleSubmitButton?.();
    });

    function validateField(fieldId, data) {
        //console.log(data);
        $.ajax({
            url: '/api/validate-gathering',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                console.log('Response: ', response);
                const isValid = response['valid'] === true;
                const errors = isValid ? [] : (Array.isArray(response['errors']) ? response['errors'] : [response['errors']]);

                if (data.field === 'time') {
                    const sharedError = isValid ? [] : (Array.isArray(errors) ? [...new Set(errors)] : [errors]);

                    // Apply the same error to both fields first
                    ['startTime', 'endTime'].forEach(field => {
                        fieldStates[field].valid = isValid;
                        fieldStates[field].error = sharedError;
                    });

                    // If both fields have the same error(s), clear one to avoid duplicate UI
                    if (
                        JSON.stringify(fieldStates['startTime'].error) === JSON.stringify(fieldStates['endTime'].error)
                        && fieldStates['startTime'].error.length > 0
                    ) {
                        fieldStates['endTime'].error = [];
                    }

                    ['startTime', 'endTime'].forEach(renderValidation);
                } else {
                    // update only the touched field
                    const touched = data.touched;
                    fieldStates[touched].valid = isValid;
                    fieldStates[touched].error = errors;
                    renderValidation(touched);
                }

                sessionStorage.setItem('__field_states__', JSON.stringify(fieldStates));
                toggleSubmitButton();
                console.log('State: ', fieldStates);
            },
            error: function (xhr, status, error) {
                console.error('AJAX error: ', xhr.responseText);
                console.log(error);
            }
        });
    }

    function renderValidation(fieldId) {
        const state = fieldStates[fieldId];
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
        const allValid = Object.values(fieldStates).every(state => state.valid);
        $('#createBtn').prop('disabled', !allValid);
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
            // Compare and adjust if needed
            // if (startDatetime && new Date(endDatetime) < new Date(startDatetime)) {
            //     const endDateObj = new Date(endDatetime);
            //     endDateObj.setDate(endDateObj.getDate() + 1); // Add one day
            //     endDatetime = endDateObj.toISOString().slice(0, 16).replace('T', ' ');
            // }
            if (startDatetime) {
                // const s = new Date(startDatetime);
                // const e = new Date(endDatetime);

                if (!isNaN(startDatetime.getTime()) && !isNaN(endDatetime.getTime())) {
                    if (endDatetime < startDatetime) {
                        endDatetime.setDate(endDatetime.getDate() + 1);
                        nextDayNote.show(); // 👈 show label
                    } else {
                        nextDayNote.hide(); // 👈 hide if not needed
                    }
                    //endDatetime = endDatetime.toISOString().slice(0, 16).replace('T', ' ');
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
});