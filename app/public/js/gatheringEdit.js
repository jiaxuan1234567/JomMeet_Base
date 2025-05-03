$(() => {
    const fields = ['gatheringTag', 'inputTheme', 'inputDate', 'inputPax', 'startTime', 'endTime'];
    const timeFields = ['inputDate', 'startTime', 'endTime'];
    const touched = new Set();

    storeInitialValues(fields);
    updateButtons();
    toggleSubmitButton(fields);

    // Submit Button
    setupSubmitButton(fields);

    // Reset Button
    setupResetButton(fields);

    // Gathering Tag
    const currentTagValue = $('#gatheringTag').val();
    if (currentTagValue) {
        const $selectedOption = $(`.tag-option[data-value="${currentTagValue}"]`);
        if ($selectedOption.length) {
            const label = $selectedOption.data('label');
            const image = $selectedOption.data('image');

            $('#selectedTagLabel').text(label);
            $('#selectedTagImage').attr('src', image);
        }
    }

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
