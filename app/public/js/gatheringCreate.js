$(() => {
    // reload clear saved data
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

    $('#nextDayNote').hide();

    const fields = ['gatheringTag', 'inputTheme', 'inputDate', 'inputPax', 'startTime', 'endTime', 'inputLocation'];
    const initFields = ['inputDate', 'inputPax'];
    const timeFields = ['inputDate', 'startTime', 'endTime'];
    const locationFields = ['inputLocation', 'locationId'];
    const fieldStates = {};
    fields.forEach(f => {
        fieldStates[f] = {
            valid: false,
            error: [],
            value: ''
        };
    });

    const savedFieldStates = sessionStorage.getItem('__field_states__');
    if (savedFieldStates) {
        const parsed = JSON.parse(savedFieldStates);
        Object.entries(parsed).forEach(([fieldId, state]) => {
            fieldStates[fieldId] = state;
            $(`#${fieldId}`).val(state.value);
            renderValidation(fieldId);
        });
    } else {
        initFields.forEach(fieldId => {
            const currentVal = $(`#${fieldId}`).val();
            if (currentVal) {
                fieldStates[fieldId].value = currentVal;

                if (timeFields.includes(fieldId)) {
                    validateField(fieldId, datetimeDataHandler(fieldId));
                } else if (locationFields.includes(fieldId)) {
                    validateField(fieldId, locationDataHandler());
                } else {
                    validateField(fieldId, dataHandler(fieldId, currentVal));
                }
            }
        });
    }

    updateButtons();

    // Submit Button
    $('#createGatheringFormEl').on('submit', function () {
        //fields.forEach(id => sessionStorage.removeItem(id));
        sessionStorage.removeItem('__field_states__');
    });


    fields.forEach(fieldId => {
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
        console.log('Pass data: ', data);
        $.ajax({
            url: '/api/validate-gathering',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                console.log('Response: ', response);
                const isValid = response['valid'] === true;
                const errors = isValid ? [] : (Array.isArray(response['errors']) ? response['errors'] : [response['errors']]);


                if (timeFields.includes(data.touched)) {
                    const sharedError = isValid ? [] : (Array.isArray(errors) ? [...new Set(errors)] : [errors]);
                    const errFields = response.errFields || [];

                    // First clear all time fields' errors and validity
                    timeFields.forEach(field => {
                        fieldStates[field].valid = isValid;
                        fieldStates[field].error = [];
                    });

                    // Then apply error to the listed fields only
                    if (!isValid && errFields.length) {
                        errFields.forEach(field => {
                            fieldStates[field].error = sharedError;
                            fieldStates[field].valid = false;
                        });
                    }
                    errFields.forEach(renderValidation);

                    // const sharedError = isValid ? [] : (Array.isArray(errors) ? [...new Set(errors)] : [errors]);
                    // const [date, start, end] = timeFields;

                    // fieldStates[data.touched].valid = isValid;
                    // fieldStates[data.touched].error = sharedError;

                    // if (['time', 'inputDateStartTime', 'inputDateEndTime'].includes(data.field)) {
                    //     [date, start, end].forEach(field => {
                    //         fieldStates[field].valid = isValid;
                    //         fieldStates[field].error = sharedError;
                    //     });

                    //     // Show shared error in only one time field depending on context
                    //     if (!isValid) {
                    //         if (data.field === 'inputDateEndTime') {
                    //             fieldStates[end].error = sharedError;
                    //             fieldStates[start].error = [];
                    //             fieldStates[date].error = [];
                    //         } else {
                    //             fieldStates[start].error = sharedError;
                    //             fieldStates[end].error = [];
                    //             fieldStates[date].error = [];
                    //         }
                    //     }

                    //     // Compare and keep error only on 'inputDate' if all 3 are same
                    //     const errDate = JSON.stringify(fieldStates[date].error);
                    //     const errStart = JSON.stringify(fieldStates[start].error);
                    //     const errEnd = JSON.stringify(fieldStates[end].error);

                    //     if (errDate === errStart && errStart === errEnd && fieldStates[start].error.length > 0) {
                    //         fieldStates[date].error = [];
                    //     }
                    // }

                    // timeFields.forEach(renderValidation);
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

        // const hasError = !state.valid && Array.isArray(state.error) && state.error.length > 0;

        // if (hasError) {
        //     const uniqueErrors = [...new Set(state.error)];
        //     const msg = uniqueErrors.join('<br>');
        //     $input.addClass('is-invalid');
        //     $error.html(`${msg}*`).show();
        // } else {
        //     $input.removeClass('is-invalid');
        //     $error.text('').hide();
        // }
        const hasError = !state.valid;

        if (hasError) {
            const errors = Array.isArray(state.error) ? state.error.filter(e => e.trim() !== '') : [];
            const uniqueErrors = [...new Set(errors)];
            const msg = uniqueErrors.join('<br>');

            $input.addClass('is-invalid');

            if (msg) {
                $error.html(`${msg}*`).show();
            } else {
                $error.text('').hide(); // Hide message but still show border
            }
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
        const payload = {
            field: '',
            touched: fieldId,
            value: {
                inputDate: date,
                startTime: '',
                endTime: ''
            }
        };

        if (date && startTime) {
            startDatetime = new Date(`${date}T${startTime}`);
            payload.value.inputDate = startDatetime;
        }
        if (date && endTime) {
            endDatetime = new Date(`${date}T${endTime}`);
            // if (startDatetime) {

            //     if (!isNaN(startDatetime.getTime()) && !isNaN(endDatetime.getTime())) {
            //         if (endDatetime < startDatetime) {
            //             endDatetime.setDate(endDatetime.getDate() + 1);
            //             nextDayNote.show();
            //         } else {
            //             nextDayNote.hide();
            //         }
            //     }
            // }
            payload.value.inputDate = endDatetime;
        }
        payload.value.startTime = startDatetime;
        payload.value.endTime = endDatetime;

        // Determine which field(s) were touched and the main field flag
        if (startTime && endTime) {
            payload.field = 'time';
        } else if ((date && startTime)) {
            payload.field = 'inputDateStartTime';
        } else if ((date && endTime)) {
            payload.field = 'inputDateEndTime';
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