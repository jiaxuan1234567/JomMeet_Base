$(() => {
    const fields = ['gatheringTag', 'inputTheme', 'inputDate', 'inputPax', 'startTime', 'endTime', 'inputLocation', 'locationId'];
    const timeFields = ['inputDate', 'startTime', 'endTime'];
    const touched = new Set();

    storeInitialValues(fields);
    updateButtons();
    toggleSubmitButton(fields);

    // Submit Button
    setupSubmitButton(fields);

    // Reset Button
    setupResetButton(fields);

    // Fields On Change
    fields.forEach(fieldId => {
        $(`#${fieldId}`).on('input change', function () {
            touched.add(fieldId);
            if (timeFields.includes(fieldId)) {
                validateFields(timeFields, fields, touched);
            } else {
                validateFields([fieldId], fields, touched);
            }
        });
    });
});