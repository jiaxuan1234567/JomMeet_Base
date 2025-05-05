$(document).ready(function () {
    const init = window.reminderInit;
    const maxDesc = 255;
  
    const $form = $('#reminderForm');
    const $desc = $('textarea[name=description]');
    const $dCnt = $('#descCount');
    const $submit = $('#submitBtn');
  
    // ====== Initialize ======
    $desc.val(init.description || '');
  
    function updateDesc() {
      const l = $desc.val().length;
      $dCnt.text(`${l}/${maxDesc} characters`)
        .css('color', l > maxDesc ? '#f00' : '');
      $desc.toggleClass('is-invalid', l === 0 || l > maxDesc);
    }
  
    function validateForm() {
      updateDesc();
      const isValid = $desc.val().trim().length > 0 && $desc.val().length <= maxDesc;
      $submit.prop('disabled', !isValid);
      $submit.css({
        backgroundColor: isValid ? '' : 'grey',
        borderColor: isValid ? '' : 'grey',
        cursor: isValid ? '' : 'not-allowed',
        opacity: isValid ? '' : 0.7
      });
    }
  
    function validateField() {
      const payload = $form.serialize();
  
      $.ajax({
        url: '/api/validate-reminder',
        type: 'POST',
        data: payload,
        dataType: 'json',
        success(res) {
          if (res.success) {
            $('#errorDescription').text('');
          } else {
            if (res.field === 'description') {
              $('#errorDescription').text(res.message);
            }
          }
        },
        error(xhr, status, err) {
          console.error('❌ Validation error:', status, err);
        }
      });
    }
  
    $desc.on('input change', () => {
      validateForm();
      validateField();
    });
  
    $form.on('submit', function (e) {
      e.preventDefault();
      if ($submit.prop('disabled')) return;
  
      if (confirm('Send this reminder?')) {
        $form.off('submit').submit();
      }
    });
  
    // Init
    validateForm();
  });
  