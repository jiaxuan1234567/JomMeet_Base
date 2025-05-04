$(() => {
    const hasSessionData = sessionStorage.getItem('__field_states__') !== null;
    if (performance.getEntriesByType("navigation")[0]?.type === "reload" && hasSessionData) {
        sessionStorage.removeItem('__field_states__');
        location.reload();
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

    // console.log(fieldStates);
    // console.log(editFieldStates);
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
        console.log('Data: ', data);
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


                const isValid = response.valid === true;
                const touched = response.touched;
                const errors = response.errors || {};

                if (isValid) {
                    const affectedFields = [];
                    if (data.field === 'time') {
                        affectedFields.push('inputDate', 'startTime', 'endTime');
                    } else if (data.field === 'inputDateStartTime') {
                        affectedFields.push('inputDate', 'startTime');
                    } else if (data.field === 'inputDateEndTime') {
                        affectedFields.push('inputDate', 'endTime');
                    } else {
                        affectedFields.push(touched);
                    }

                    affectedFields.forEach(field => {
                        if (editFieldStates[field]) {
                            editFieldStates[field].valid = true;
                            editFieldStates[field].error = [];
                            renderValidation(field);
                        }
                    });
                } else {
                    Object.entries(errors).forEach(([field, msg]) => {
                        if (editFieldStates[field]) {
                            const message = Array.isArray(msg) ? msg.join('<br>') : (typeof msg === 'string' ? msg : '');
                            editFieldStates[field].error = message ? [message] : [];
                            editFieldStates[field].valid = !message;

                            // ✅ Mark touched if not already
                            if (!editFieldStates[field].touched) {
                                editFieldStates[field].touched = true;
                            }

                            renderValidation(field);
                        }
                    });
                    if (!(touched in errors)) {
                        renderValidation(touched);
                    }
                }

                //sessionStorage.setItem('__field_states__', JSON.stringify(editFieldStates));
                toggleSubmitButton();

                console.log('Edit Field State: ', editFieldStates);
                // const isValid = response['valid'] === true;
                // const errors = isValid ? [] : (Array.isArray(response['errors']) ? response['errors'] : [response['errors']]);

                // if (data.field === 'time') {
                //     const sharedError = isValid ? [] : (Array.isArray(errors) ? [...new Set(errors)] : [errors]);

                //     // Apply the same error to both fields first
                //     ['startTime', 'endTime'].forEach(field => {
                //         editFieldStates[field].valid = isValid;
                //         editFieldStates[field].error = sharedError;
                //     });

                //     // If both fields have the same error(s), clear one to avoid duplicate UI
                //     if (
                //         JSON.stringify(editFieldStates['startTime'].error) === JSON.stringify(editFieldStates['endTime'].error) &&
                //         editFieldStates['startTime'].error.length > 0
                //     ) {
                //         editFieldStates['endTime'].error = [];
                //     }

                //     ['startTime', 'endTime'].forEach(renderValidation);
                // } else {
                //     // update only the touched field
                //     const touched = data.touched;
                //     editFieldStates[touched].valid = isValid;
                //     editFieldStates[touched].error = errors;
                //     renderValidation(touched);
                // }

                // toggleSubmitButton();

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
        const hasError = !state.valid && state.touched;
        if (hasError) {
            const errors = Array.isArray(state.error) ? state.error.filter(e => e.trim() !== '') : [];
            const msg = [...new Set(errors)].join('<br>');
            $input.addClass('is-invalid');
            msg ? $error.html(`${msg}*`).show() : $error.text('').hide();
        } else {
            $input.removeClass('is-invalid');
            $error.text('').hide();
        }
    }

    // function toggleSubmitButton() {
    //     const allValid = Object.values(fieldStates).every(state => state.valid);
    //     $('#createBtn').prop('disabled', !allValid);
    // }

    function locationDataHandler() {
        return {
            field: 'inputLocation',
            touched: 'inputLocation',
            value: {
                locationName: $('#inputLocation').val(),
                locationId: $('#locationId').val()
            }
        };
    }

    function datetimeDataHandler(fieldId) {
        const date = $('#inputDate').val();
        const startTime = $('#startTime').val();
        const endTime = $('#endTime').val();
        let startDatetime = '', endDatetime = '';

        const payload = {
            field: '',
            touched: fieldId,
            value: {
                inputDate: date,
                startTime: '',
                endTime: ''
            }
        };

        if (date && startTime) payload.value.inputDate = new Date(`${date}T${startTime}`);
        if (date && endTime) payload.value.inputDate = new Date(`${date}T${endTime}`);

        payload.value.startTime = new Date(`${date}T${startTime}`);
        payload.value.endTime = new Date(`${date}T${endTime}`);

        if (startTime && endTime) {
            payload.field = 'time';
        } else if (date && startTime) {
            payload.field = 'inputDateStartTime';
        } else if (date && endTime) {
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
        };
    }

    updateButtons();

    function toggleSubmitButton() {
        const hasChange = fields.some(fieldId => {
            const original = (fieldStates[fieldId]?.value || '').trim();
            const current = (editFieldStates[fieldId]?.value || '').trim();
            return current !== '' && current !== original;
        });

        const allValid = fields.every(fieldId => editFieldStates[fieldId]?.valid === true);

        $('#createBtn').prop('disabled', !(hasChange && allValid));
    }

    // function locationDataHandler() {
    //     const inputLocation = $('#inputLocation');
    //     const locationId = $('#locationId');
    //     return {
    //         field: 'inputLocation',
    //         touched: 'inputLocation',
    //         value: {
    //             locationName: inputLocation,
    //             locationId: locationId
    //         }
    //     };
    // }

    // function datetimeDataHandler(fieldId) {
    //     const date = $('#inputDate').val();
    //     const startTime = $('#startTime').val();
    //     const endTime = $('#endTime').val();
    //     const nextDayNote = $('#nextDayNote');
    //     let startDatetime = '';
    //     let endDatetime = '';

    //     if (date && startTime) {
    //         startDatetime = new Date(`${date}T${startTime}`);
    //     }
    //     if (date && endTime) {
    //         console.log(endTime);
    //         endDatetime = new Date(`${date}T${endTime}`);
    //         if (startDatetime) {

    //             if (!isNaN(startDatetime.getTime()) && !isNaN(endDatetime.getTime())) {
    //                 if (endDatetime < startDatetime) {
    //                     endDatetime.setDate(endDatetime.getDate() + 1);
    //                     nextDayNote.show(); // 👈 show label
    //                 } else {
    //                     nextDayNote.hide(); // 👈 hide if not needed
    //                 }
    //             }
    //         }
    //     }

    //     const payload = {
    //         field: '', // will be assigned below
    //         touched: fieldId,
    //         value: {
    //             inputDate: date || '',
    //             startTime: startDatetime,
    //             endTime: endDatetime
    //         }
    //     };

    //     // Determine which field(s) were touched and the main field flag
    //     // field : inputDate, startTime, endTime, time
    //     if (fieldId === 'inputDate') {
    //         payload.field = 'inputDate';
    //     } else if (startTime && endTime) {
    //         payload.field = 'time';
    //     } else {
    //         payload.field = fieldId;
    //     }

    //     return payload;
    // }

    // function dataHandler(fieldId, data) {
    //     return {
    //         field: fieldId,
    //         touched: fieldId,
    //         value: data
    //     }
    // }
    // updateButtons();
});
