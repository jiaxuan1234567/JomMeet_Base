$(() => {
    const hasSessionData = sessionStorage.getItem('__field_states__') !== null;
    if (performance.getEntriesByType("navigation")[0]?.type === "reload" && hasSessionData) {
        sessionStorage.removeItem('__field_states__');
        location.reload();
    }

    const fields = ['gatheringTag', 'inputTheme', 'inputDate', 'inputPax', 'startTime', 'endTime', 'inputLocation'];
    const initFields = ['inputDate', 'inputPax'];
    const timeFields = ['inputDate', 'startTime', 'endTime'];
    const locationFields = ['inputLocation', 'locationId'];
    const fieldStates = {};
    fields.forEach(f => {
        fieldStates[f] = { valid: false, error: [], value: '', touched: false };
    });

    const savedFieldStates = sessionStorage.getItem('__field_states__');
    if (savedFieldStates) {
        const parsed = JSON.parse(savedFieldStates);
        Object.entries(parsed).forEach(([fieldId, state]) => {
            fieldStates[fieldId] = state;
            $(`#${fieldId}`).val(state.value);
            //renderValidation(fieldId);
            // Only revalidate fields with value but not valid
            if (state.value && !state.valid) {
                if (timeFields.includes(fieldId)) {
                    validateField(fieldId, datetimeDataHandler(fieldId));
                } else if (locationFields.includes(fieldId)) {
                    $('#locationId').val(sessionStorage.getItem('locationId') || '');
                    validateField(fieldId, locationDataHandler());
                } else {
                    validateField(fieldId, dataHandler(fieldId, state.value));
                }
            } else {
                renderValidation(fieldId); // for untouched or already valid
            }
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
    if (initialTagValue) updateSelectedTagUI(initialTagValue);

    updateButtons();

    $('#createGatheringFormEl').on('submit', function () {
        sessionStorage.removeItem('__field_states__');
    });

    fields.forEach(fieldId => {
        $(`#${fieldId}`).on('input change', function () {
            const data = $(this).val();
            fieldStates[fieldId].value = data;
            fieldStates[fieldId].touched = true;

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
        fieldStates['inputLocation'].value = fieldStates['inputLocation'].value;
        console.log('Location: ', locationDataHandler());
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
        $.ajax({
            url: '/api/validate-gathering',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                console.log(response);

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
                        if (fieldStates[field]) {
                            fieldStates[field].valid = true;
                            fieldStates[field].error = [];
                            renderValidation(field);
                        }
                    });
                } else {
                    Object.entries(errors).forEach(([field, msg]) => {
                        if (fieldStates[field]) {
                            const message = Array.isArray(msg) ? msg.join('<br>') : (typeof msg === 'string' ? msg : '');
                            fieldStates[field].error = message ? [message] : [];
                            fieldStates[field].valid = !message;
                            renderValidation(field);
                        }
                    });
                    if (!(touched in errors)) {
                        renderValidation(touched);
                    }
                }

                sessionStorage.setItem('__field_states__', JSON.stringify(fieldStates));
                toggleSubmitButton();

                console.log(fieldStates);
            },
            error: function (xhr) {
                console.error('AJAX error: ', xhr.responseText);
            }
        });
    }

    function renderValidation(fieldId) {
        const state = fieldStates[fieldId];
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

    function toggleSubmitButton() {
        const allValid = Object.values(fieldStates).every(state => state.valid);
        $('#createBtn').prop('disabled', !allValid);
    }

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
});
