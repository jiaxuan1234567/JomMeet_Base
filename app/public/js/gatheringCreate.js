$(() => {
    const fields = ['gatheringTag', 'inputTheme', 'inputDate', 'inputPax', 'startTime', 'endTime', 'inputLocation'];
    $('#nextDayNote').hide();

    // reload clear saved data
    const hasSessionData = fields.some(id => sessionStorage.getItem(id) !== null);
    if (performance.getEntriesByType("navigation")[0]?.type === "reload" && hasSessionData) {
        //fields.forEach(id => sessionStorage.removeItem(id));
        //sessionStorage.removeItem('__validation_state__');
        location.reload();
    }

    // defined variables
    const timeFields = ['inputDate', 'startTime', 'endTime'];
    const locationFields = ['inputLocation', 'locationId'];

    // // validation state define and restore
    // const validationState = {};
    // const savedValidation = sessionStorage.getItem('__validation_state__');
    // if (savedValidation) {
    //     const parsed = JSON.parse(savedValidation);
    //     fields.forEach(f => {
    //         validationState[f] = parsed[f] === true;
    //     });
    // } else {
    //     fields.forEach(f => validationState[f] = false);
    // }

    // // errorMsg state
    // const errorMessages = {};
    // const savedErrors = sessionStorage.getItem('__error_messages__');
    // if (savedErrors) {
    //     const parsed = JSON.parse(savedErrors);
    //     Object.entries(parsed).forEach(([fieldId, messages]) => {
    //         errorMessages[fieldId] = messages;
    //         showValidationError(fieldId, messages); // ⛔ Re-show errors
    //     });
    // }

    const fieldStates = {};
    fields.forEach(f => {
        fieldStates[f] = { valid: false, error: [] };
    });

    const savedFieldStates = sessionStorage.getItem('__field_states__');
    if (savedFieldStates) {
        const parsed = JSON.parse(savedFieldStates);
        Object.entries(parsed).forEach(([fieldId, state]) => {
            fieldStates[fieldId] = state;
            renderValidation(fieldId); // Restore UI
        });
    }

    storeInitialValues(fields);
    updateButtons();

    // Submit Button
    $('#createGatheringFormEl').on('submit', function () {
        //fields.forEach(id => sessionStorage.removeItem(id));
        sessionStorage.removeItem('__field_states__');
    });


    fields.forEach(fieldId => {
        const val = sessionStorage.getItem(fieldId);
        if (val !== null) {
            $(`#${fieldId}`).val(val).trigger('input');
            //touched.add(fieldId);
        }

        $(`#${fieldId}`).on('input change', function () {
            const data = $(this).val();
            sessionStorage.setItem(fieldId, data);

            if (timeFields.includes(fieldId)) {
                validateField(fieldId, datetimeDataHandler(fieldId));
            } else if (locationFields.includes(fieldId)) {
                validateField(fieldId, locationDataHandler());
            } else {
                validateField(fieldId, dataHandler(data));
            }
            toggleSubmitButton();
        });
    });
    const id = sessionStorage.getItem('locationId');
    if (id) {
        validateField('inputLocation', locationDataHandler());
        $('#locationId').val(id);
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
                el.value = initialValues[id]?.trim() || ''; // initial value concern
                //sessionStorage.removeItem(id);
                fieldStates[id] = { valid: false, error: [] };
                renderValidation(id);
            }
        });

        if (initialValues['inputPax']) updateButtons?.();
        sessionStorage.removeItem('__field_states__');
        toggleSubmitButton?.();
    });

    function validateField(fieldId, data) {
        console.log(data);
        $.ajax({
            url: '/api/validate-gathering',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                fieldStates[fieldId] = {
                    valid: response.valid,
                    error: response.valid ? [] : response.errors
                };
                sessionStorage.setItem('__field_states__', JSON.stringify(fieldStates));
                renderValidation(fieldId);
                toggleSubmitButton();
            }
        });
        console.log(fieldStates);
    }

    function renderValidation(fieldId) {
        const state = fieldStates[fieldId];
        const $input = $(`#${fieldId}`);
        const $error = $(`#error-${fieldId}`);

        const hasError = !state.valid && Array.isArray(state.error) && state.error.length > 0;

        if (hasError) {
            const uniqueErrors = [...new Set(state.error)]; // ✅ remove duplicates
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

    // function updateValidationState(fieldId, isValid) {
    //     validationState[fieldId] = isValid;
    //     sessionStorage.setItem('__validation_state__', JSON.stringify(validationState));
    //     toggleSubmitButton();
    // }

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