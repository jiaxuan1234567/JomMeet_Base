// $(function() {
//   // Initial data from PHP session
//   var init = window.profileInit;
//   var maxNick = 20, maxAbout = 255;

//   // Cache elements
//   var $nick       = $('input[name=nickname]'),
//       $nCnt       = $nick.siblings('.text-end'),
//       $about      = $('textarea[name=aboutme]'),
//       $aCnt       = $about.siblings('.text-end'),
//       $mbti       = $('select[name=mbti]'),
//       $hBtns      = $('#hobbiesList .hobby-btn'),
//       $pBtns      = $('#preferencesList .pref-btn'),
//       $hContainer = $('#hobbiesList'),
//       $pContainer = $('#preferencesList'),
//       $save       = $('#saveBtn'),
//       $cancel     = $('#cancelBtn'),
//       $form       = $('#editProfileForm');

//   // Style toggle helper
//   function styleBtn($b, on) {
//     if (on) {
//       $b.addClass('active')
//         .css({ backgroundColor: '#569FFF', borderColor: '#569FFF', color: '#000' });
//     } else {
//       $b.removeClass('active')
//         .css({ backgroundColor: '#fff', borderColor: '#dee2e6', color: '#000' });
//     }
//   }

//   // Initialize fields and counters
//   $nick.val(init.nickname);
//   $about.val(init.aboutme);
//   $mbti.val(init.mbti);

//   function updNick() {
//     var l = $nick.val().length;
//     $nCnt.text(l + '/' + maxNick + ' characters')
//       .css('color', l > maxNick ? '#ff0000' : '');
//     $nick.toggleClass('is-invalid', (l === 0 || l > maxNick));
//   }

//   function updAbout() {
//     var l = $about.val().length;
//     $aCnt.text(l + '/' + maxAbout + ' characters')
//       .css('color', l > maxAbout ? '#ff0000' : '');
//     $about.toggleClass('is-invalid', (l === 0 || l > maxAbout));
//   }

//   updNick();
//   updAbout();

//   // Init and style buttons
//   $hBtns.each(function() {
//     styleBtn($(this), init.hobbies.includes($(this).data('value')));
//   });
//   $pBtns.each(function() {
//     styleBtn($(this), init.preferences.includes($(this).data('value')));
//   });

//   // Validation helpers for hobbies & prefs
//   function validateHobbies() {
//     $hContainer.toggleClass('border-danger', $hBtns.filter('.active').length === 0);
//   }
//   function validatePrefs() {
//     $pContainer.toggleClass('border-danger', $pBtns.filter('.active').length === 0);
//   }

//   // Main form validation, with real-time AJAX check
//   function validateForm() {
//     var okN = $nick.val().length > 0 && $nick.val().length <= maxNick,
//         okA = $about.val().length > 0 && $about.val().length <= maxAbout,
//         okM = $mbti.val() !== '',
//         okH = $hBtns.filter('.active').length > 0,
//         okP = $pBtns.filter('.active').length > 0;

//     validateHobbies();
//     validatePrefs();

//     $nick.toggleClass('is-invalid', !okN);
//     $about.toggleClass('is-invalid', !okA);
//     $mbti.toggleClass('is-invalid', !okM);

//     var clientValid = okN && okA && okM && okH && okP;
//     $save.prop('disabled', !clientValid);

//     if (!clientValid) return clientValid;

//     // Then ask server for authoritative validation
//     $.ajax({
//       url: '/profile/validate',
//       type: 'POST',
//       data: $form.serialize(),
//       dataType: 'json'
//     }).done(function(res) {
//       if (!res.success) {
//         for (var f in res.errors) {
//           var sel = (f === 'aboutme') ? 'textarea[name=aboutme]' : '[name=' + f + ']';
//           $(sel).addClass('is-invalid');
//         }
//         $save.prop('disabled', true);
//       } else {
//         $save.prop('disabled', false);
//       }
//     });

//     return clientValid;
//   }

//   // Bind live events
//   $nick.on('input', function() { updNick();  validateForm(); });
//   $about.on('input', function() { updAbout(); validateForm(); });
//   $mbti.on('change', function() {
//     $mbti.toggleClass('is-invalid', $mbti.val() === '');
//     validateForm();
//   });
//   $hBtns.on('click', function() {
//     var $b = $(this), on = !$b.hasClass('active');
//     styleBtn($b, on); validateHobbies(); validateForm();
//   });
//   $pBtns.on('click', function() {
//     var $b = $(this), on = !$b.hasClass('active');
//     styleBtn($b, on); validatePrefs(); validateForm();
//   });

//   validateForm();

//   // Cancel resets everything
//   $cancel.on('click', function() {
//     $nick.val(init.nickname); updNick();
//     $about.val(init.aboutme); updAbout();
//     $mbti.val(init.mbti).removeClass('is-invalid');
//     $hBtns.each(function() {
//       styleBtn($(this), init.hobbies.includes($(this).data('value')));
//     });
//     $pBtns.each(function() {
//       styleBtn($(this), init.preferences.includes($(this).data('value')));
//     });
//     $hContainer.removeClass('border-danger');
//     $pContainer.removeClass('border-danger');
//     validateForm();
//   });

//   // Save with confirmation
//   $form.on('submit', function(e) {
//     $('#hiddenHobbies').val(
//       $hBtns.filter('.active').map(function(){ return this.dataset.value; }).get().join(',')
//     );
//     $('#hiddenPrefs').val(
//       $pBtns.filter('.active').map(function(){ return this.dataset.value; }).get().join(',')
//     );
//     e.preventDefault();
//     if ($save.prop('disabled')) return;
//     if (confirm('Are you sure you want to update your profile?')) {
//       $form.off('submit').submit();
//     } else {
//       alert('Profile update canceled.');
//     }
//   });

// });  // end $(function)





// $(function () {
//   // Initial data from PHP session
//   var init = window.profileInit;
//   var maxNick = 20, maxAbout = 255;

//   // Cache elements
//   var $nick = $('input[name=nickname]'),
//     $nCnt = $nick.siblings('.text-end'),
//     $about = $('textarea[name=aboutme]'),
//     $aCnt = $about.siblings('.text-end'),
//     $mbti = $('select[name=mbti]'),
//     $hBtns = $('#hobbiesList .hobby-btn'),
//     $pBtns = $('#preferencesList .pref-btn'),
//     $hContainer = $('#hobbiesList'),
//     $pContainer = $('#preferencesList'),
//     $save = $('#saveBtn'),
//     $cancel = $('#cancelBtn'),
//     $form = $('#editProfileForm');
//   // Style toggle helper
//   function styleBtn($b, on) {
//     if (on) {
//       $b.addClass('active')
//         .css({
//           backgroundColor: '#569FFF',
//           borderColor: '#569FFF',
//           color: '#000'
//         });
//     } else {
//       $b.removeClass('active')
//         .css({
//           backgroundColor: '#fff',
//           borderColor: '#dee2e6',
//           color: '#000'
//         });
//     }
//   }

//   // Initialize fields and counters
//   $nick.val(init.nickname);
//   $about.val(init.aboutme);
//   $mbti.val(init.mbti);

//   function updNick() {
//     var l = $nick.val().length;
//     $nCnt.text(l + '/' + maxNick + ' characters')
//       .css('color', l > maxNick ? '#ff0000' : '');
//     if (l === 0 || l > maxNick) {
//       $nick.addClass('is-invalid');
//     } else {
//       $nick.removeClass('is-invalid');
//     }
//   }

//   function updAbout() {
//     var l = $about.val().length;
//     $aCnt.text(l + '/' + maxAbout + ' characters')
//       .css('color', l > maxAbout ? '#ff0000' : '');
//     if (l === 0 || l > maxAbout) {
//       $about.addClass('is-invalid');
//     } else {
//       $about.removeClass('is-invalid');
//     }
//   }
//   updNick();
//   updAbout();

//   // Init and style buttons
//   $hBtns.each(function () {
//     styleBtn($(this), init.hobbies.includes($(this).data('value')));
//   });
//   $pBtns.each(function () {
//     styleBtn($(this), init.preferences.includes($(this).data('value')));
//   });

//   // Bind live events
//   $nick.on('input', function () {
//     updNick();
//     validateForm();
//   });
//   $about.on('input', function () {
//     updAbout();
//     validateForm();
//   });
//   $mbti.on('change', function () {
//     if ($mbti.val() === '') $mbti.addClass('is-invalid');
//     else $mbti.removeClass('is-invalid');
//     validateForm();
//   });

//   // Toggling on click
//   $hBtns.on('click', function () {
//     var $btn = $(this);
//     styleBtn($btn, !$btn.hasClass('active'));
//     validateHobbies();
//     validateForm();
//   });
//   $pBtns.on('click', function () {
//     var $btn = $(this);
//     styleBtn($btn, !$btn.hasClass('active'));
//     validatePrefs();
//     validateForm();
//   });

//   // Validation helper for hobbies & prefs
//   function validateHobbies() {
//     if ($hBtns.filter('.active').length === 0) {
//       $hContainer.addClass('border-danger');
//     } else {
//       $hContainer.removeClass('border-danger');
//     }
//   }

//   function validatePrefs() {
//     if ($pBtns.filter('.active').length === 0) {
//       $pContainer.addClass('border-danger');
//     } else {
//       $pContainer.removeClass('border-danger');
//     }
//   }

//   // Main form validation
//   function validateForm() {
//     var okN = $nick.val().length > 0 && $nick.val().length <= maxNick,
//       okA = $about.val().length > 0 && $about.val().length <= maxAbout,
//       okM = $mbti.val() !== '',
//       okH = $hBtns.filter('.active').length > 0,
//       okP = $pBtns.filter('.active').length > 0;
//     validateHobbies();
//     validatePrefs();
//     if (okN && okA && okM && okH && okP) {
//       $save.prop('disabled', false);
//     } else {
//       $save.prop('disabled', true);
//     }
//   }
//   validateForm();

//   // Cancel resets everything
//   $cancel.on('click', function () {
//     $nick.val(init.nickname);
//     updNick();
//     $about.val(init.aboutme);
//     updAbout();
//     $mbti.val(init.mbti).removeClass('is-invalid');
//     $hBtns.each(function () {
//       styleBtn($(this), init.hobbies.includes($(this).data('value')));
//     });
//     $pBtns.each(function () {
//       styleBtn($(this), init.preferences.includes($(this).data('value')));
//     });
//     $hContainer.removeClass('border-danger');
//     $pContainer.removeClass('border-danger');
//     validateForm();
//   });

//   // Save with confirmation
//   $form.on('submit', function (e) {
//     $('#hiddenHobbies').val(
//       $hBtns.filter('.active').map((_, b) => b.dataset.value).get().join(',')
//     );
//     $('#hiddenPrefs').val(
//       $pBtns.filter('.active').map((_, b) => b.dataset.value).get().join(',')
//     );
//     e.preventDefault();
//     if ($save.prop('disabled')) return;
//     if (confirm('Are you sure you want to update your profile?')) {
//       // unbind to avoid infinite loop, then submit
//       $form.off('submit').submit();
//     } else {
//       alert('Profile update canceled.');
//     }
//   });

//   function logValidation() {
//     var action = '/profile/validate';          // << use your JSON endpoint
//     var query  = $form.serialize();
//     $.ajax({
//       url:    action,
//       type:   'POST',
//       data:   query,
//       dataType: 'json'
//     }).done(function(res) {
//       console.log(`${action}?${query}:`, res);
//     }).fail(function(xhr, status, err) {
//       console.error(`${action}?${query} failed:`, status, err);
//     });
//   }

//   $nick.on('input',  logValidation);
//   $about.on('input', logValidation);
//   $mbti.on('change', logValidation);
//   $hBtns.on('click', logValidation);
//   $pBtns.on('click', logValidation);
// });



// $(function () {
//   // ====== Initial data from PHP session ======
//   var init = window.profileInit;
//   var maxNick = 20, maxAbout = 255;

//   // ====== Cache elements ======
//   var $nick       = $('input[name=nickname]'),
//       $nCnt       = $nick.siblings('.text-end'),
//       $about      = $('textarea[name=aboutme]'),
//       $aCnt       = $about.siblings('.text-end'),
//       $mbti       = $('select[name=mbti]'),
//       $hBtns      = $('#hobbiesList .hobby-btn'),
//       $pBtns      = $('#preferencesList .pref-btn'),
//       $hContainer = $('#hobbiesList'),
//       $pContainer = $('#preferencesList'),
//       $save       = $('#saveBtn'),
//       $cancel     = $('#cancelBtn'),
//       $form       = $('#editProfileForm');

//   // ====== Style toggle helper ======
//   function styleBtn($b, on) {
//       if (on) {
//           $b.addClass('active')
//             .css({
//                 backgroundColor: '#569FFF',
//                 borderColor: '#569FFF',
//                 color: '#000'
//             });
//       } else {
//           $b.removeClass('active')
//             .css({
//                 backgroundColor: '#fff',
//                 borderColor: '#dee2e6',
//                 color: '#000'
//             });
//       }
//   }

//   // ====== Initialize fields and counters ======
//   $nick.val(init.nickname);
//   $about.val(init.aboutme);
//   $mbti.val(init.mbti);

//   function updNick() {
//       var l = $nick.val().length;
//       $nCnt.text(l + '/' + maxNick + ' characters')
//            .css('color', l > maxNick ? '#ff0000' : '');
//       if (l === 0 || l > maxNick) {
//           $nick.addClass('is-invalid');
//       } else {
//           $nick.removeClass('is-invalid');
//       }
//   }

//   function updAbout() {
//       var l = $about.val().length;
//       $aCnt.text(l + '/' + maxAbout + ' characters')
//            .css('color', l > maxAbout ? '#ff0000' : '');
//       if (l === 0 || l > maxAbout) {
//           $about.addClass('is-invalid');
//       } else {
//           $about.removeClass('is-invalid');
//       }
//   }

//   updNick();
//   updAbout();

//   // ====== Init and style hobby & preference buttons ======
//   $hBtns.each(function () {
//       styleBtn($(this), init.hobbies.includes($(this).data('value')));
//   });
//   $pBtns.each(function () {
//       styleBtn($(this), init.preferences.includes($(this).data('value')));
//   });

//   // ====== Validation helpers ======
//   function validateHobbies() {
//       if ($hBtns.filter('.active').length === 0) {
//           $hContainer.addClass('border-danger');
//       } else {
//           $hContainer.removeClass('border-danger');
//       }
//   }

//   function validatePrefs() {
//       if ($pBtns.filter('.active').length === 0) {
//           $pContainer.addClass('border-danger');
//       } else {
//           $pContainer.removeClass('border-danger');
//       }
//   }

//   // ====== Main form validation ======
//   function validateForm() {
//       var okN = $nick.val().length > 0 && $nick.val().length <= maxNick,
//           okA = $about.val().length > 0 && $about.val().length <= maxAbout,
//           okM = $mbti.val() !== '',
//           okH = $hBtns.filter('.active').length > 0,
//           okP = $pBtns.filter('.active').length > 0;

//       validateHobbies();
//       validatePrefs();

//       if (okN && okA && okM && okH && okP) {
//           $save.prop('disabled', false);
//       } else {
//           $save.prop('disabled', true);
//       }
//   }

//   validateForm();

//   // ====== Bind live events ======
//   $nick.on('input', function () {
//       updNick();
//       validateForm();
//       logValidation();
//   });

//   $about.on('input', function () {
//       updAbout();
//       validateForm();
//       logValidation();
//   });

//   $mbti.on('change', function () {
//       if ($mbti.val() === '') {
//           $mbti.addClass('is-invalid');
//       } else {
//           $mbti.removeClass('is-invalid');
//       }
//       validateForm();
//       logValidation();
//   });

//   $hBtns.on('click', function () {
//       var $btn = $(this);
//       styleBtn($btn, !$btn.hasClass('active'));
//       validateHobbies();
//       validateForm();
//       logValidation();
//   });

//   $pBtns.on('click', function () {
//       var $btn = $(this);
//       styleBtn($btn, !$btn.hasClass('active'));
//       validatePrefs();
//       validateForm();
//       logValidation();
//   });

//   // ====== Cancel resets everything ======
//   $cancel.on('click', function () {
//       $nick.val(init.nickname);
//       updNick();
//       $about.val(init.aboutme);
//       updAbout();
//       $mbti.val(init.mbti).removeClass('is-invalid');

//       $hBtns.each(function () {
//           styleBtn($(this), init.hobbies.includes($(this).data('value')));
//       });
//       $pBtns.each(function () {
//           styleBtn($(this), init.preferences.includes($(this).data('value')));
//       });

//       $hContainer.removeClass('border-danger');
//       $pContainer.removeClass('border-danger');
//       validateForm();
//   });

//   // ====== Save with confirmation ======
//   $form.on('submit', function (e) {
//       // populate hidden inputs
//       $('#hiddenHobbies').val(
//           $hBtns.filter('.active').map((_, b) => b.dataset.value).get().join(',')
//       );
//       $('#hiddenPrefs').val(
//           $pBtns.filter('.active').map((_, b) => b.dataset.value).get().join(',')
//       );

//       e.preventDefault();
//       if ($save.prop('disabled')) return;

//       if (confirm('Are you sure you want to update your profile?')) {
//           // unbind to avoid infinite loop, then submit
//           $form.off('submit').submit();
//       } else {
//           alert('Profile update canceled.');
//       }
//   });

//   // ====== AJAX Field Validation Logging ======
//   function logValidation() {
//       var action = '/profile/validate',
//           query  = $form.serialize();

//       $.ajax({
//           url:    action,
//           type:   'POST',
//           data:   query,
//           dataType: 'json'
//       })
//       .done(function (res) {
//           console.log(action + '?' + query + ':', res);
//       })
//       .fail(function (xhr, status, err) {
//           console.error(action + '?' + query + ' failed:', status, err);
//       });
//   }
// });

// $(function () {
//   const init   = window.profileInit,
//         maxNick  = 20,
//         maxAbout = 255;

//   // Element cache
//   const $nick    = $('#nickname'),
//         $nCnt    = $('#nicknameCount'),
//         $about   = $('#aboutme'),
//         $aCnt    = $('#aboutCount'),
//         $mbti    = $('#mbti'),
//         $hBtns   = $('.hobby-btn'),
//         $pBtns   = $('.pref-btn'),
//         $hCont   = $('#hobbiesList'),
//         $pCont   = $('#preferencesList'),
//         $hiddH   = $('#hiddenHobbies'),
//         $hiddP   = $('#hiddenPrefs'),
//         $save    = $('#saveBtn'),
//         $cancel  = $('#cancelBtn'),
//         $form    = $('#editProfileForm');

//   // Helper to toggle button styles
//   function styleBtn($b, on) {
//     if (on) {
//       $b.addClass('active btn-primary')
//         .removeClass('btn-outline-secondary');
//     } else {
//       $b.removeClass('active btn-primary')
//         .addClass('btn-outline-secondary');
//     }
//   }

//   // Show or clear an inline error message
//   function showError(field, msg) {
//     const $inp = $('#' + field),
//           $err = $('#' + field + 'Error');
//     if (msg) {
//       $inp.addClass('is-invalid');
//       $err.text(msg);
//     } else {
//       $inp.removeClass('is-invalid');
//       $err.text('');
//     }
//   }

//   // Initialize fields from PHP data
//   $nick.val(init.nickname);
//   $about.val(init.aboutme);
//   $mbti.val(init.mbti);

//   // Initialize hobby/preference buttons
//   $hBtns.each((_, b) => styleBtn($(b), init.hobbies.includes(b.dataset.value)));
//   $pBtns.each((_, b) => styleBtn($(b), init.preferences.includes(b.dataset.value)));

//   // Initialize hidden inputs so first AJAX has correct data
//   $hiddH.val(init.hobbies.join(','));
//   $hiddP.val(init.preferences.join(','));

//   // Update character counters
//   function updNick() {
//     $nCnt.text($nick.val().length + '/' + maxNick + ' characters');
//   }
//   function updAbout() {
//     $aCnt.text($about.val().length + '/' + maxAbout + ' characters');
//   }
//   updNick(); updAbout();

//   // Re-populate hidden fields & POST to validate
//   function logValidation() {
//     $hiddH.val(
//       $hBtns.filter('.active').map((_,b)=>b.dataset.value).get().join(',')
//     );
//     $hiddP.val(
//       $pBtns.filter('.active').map((_,b)=>b.dataset.value).get().join(',')
//     );

//     const payload = $form.serialize();
//     $.post('/profile/validate', payload, function (res) {
//       console.log('POST /profile/validate', payload, '→', res);
//       // Display inline errors
//       ['nickname','aboutme','mbti','hobbies','preferences']
//         .forEach(f => showError(f, res.errors[f] || ''));
//       // Enable save button only if all pass
//       const ok = res.success === true;
//       $save.prop('disabled', !ok);
//     }, 'json')
//     .fail((xhr, status, err) => {
//       console.error('Validation AJAX failed:', status, err);
//     });
//   }

//   // Bind events
//   $nick.on('input', () => { updNick(); logValidation(); });
//   $about.on('input', () => { updAbout(); logValidation(); });
//   $mbti.on('change', () => { logValidation(); });
//   $hBtns.on('click', function () {
//     styleBtn($(this), !$(this).hasClass('active'));
//     logValidation();
//   });
//   $pBtns.on('click', function () {
//     styleBtn($(this), !$(this).hasClass('active'));
//     logValidation();
//   });

//   $cancel.on('click', () => {
//     // Reset everything
//     $nick.val(init.nickname); updNick();
//     $about.val(init.aboutme); updAbout();
//     $mbti.val(init.mbti);
//     $hBtns.each((_,b) => styleBtn($(b), init.hobbies.includes(b.dataset.value)));
//     $pBtns.each((_,b) => styleBtn($(b), init.preferences.includes(b.dataset.value)));
//     logValidation();
//   });

//   // On final submit, form fields are already correct because hidden inputs
//   // are kept in sync in logValidation(); confirm then allow native submit.
//   $form.on('submit', function (e) {
//     if ($save.prop('disabled')) {
//       e.preventDefault();
//       return;
//     }
//     if (!confirm('Are you sure you want to update your profile?')) {
//       e.preventDefault();
//     }
//   });

//   // Trigger initial validation
//   logValidation();
// });


$(function () {
  // ====== Initial data from PHP session ======
  var init = window.profileInit;
  var maxNick = 20, maxAbout = 255;

  // ====== Cache elements ======
  var $nick        = $('input[name=nickname]'),
      $nCnt        = $nick.siblings('.text-end'),
      $about       = $('textarea[name=aboutme]'),
      $aCnt        = $about.siblings('.text-end'),
      $mbti        = $('select[name=mbti]'),
      $hBtns       = $('#hobbiesList .hobby-btn'),
      $pBtns       = $('#preferencesList .pref-btn'),
      $hContainer  = $('#hobbiesList'),
      $pContainer  = $('#preferencesList'),
      $save        = $('#saveBtn'),
      $cancel      = $('#cancelBtn'),
      $form        = $('#editProfileForm'),
      // ---- MISSING: hidden fields for AJAX ----
      $hiddenH     = $('#hiddenHobbies'),
      $hiddenP     = $('#hiddenPrefs');

  // ====== INITIALIZE HIDDENS from saved profile so first AJAX sees them ======
  $hiddenH.val(init.hobbies.join(','));
  $hiddenP.val(init.preferences.join(','));

  // ====== Style toggle helper ======
  function styleBtn($b, on) {
    if (on) {
      $b.addClass('active')
        .css({
          backgroundColor: '#569FFF',
          borderColor: '#569FFF',
          color: '#000'
        });
    } else {
      $b.removeClass('active')
        .css({
          backgroundColor: '#fff',
          borderColor: '#dee2e6',
          color: '#000'
        });
    }
  }

  // ====== Initialize fields and counters ======
  $nick.val(init.nickname);
  $about.val(init.aboutme);
  $mbti.val(init.mbti);

  function updNick() {
    var l = $nick.val().length;
    $nCnt.text(l + '/' + maxNick + ' characters')
         .css('color', l > maxNick ? '#ff0000' : '');
    if (l === 0 || l > maxNick) {
      $nick.addClass('is-invalid');
    } else {
      $nick.removeClass('is-invalid');
    }
  }

  function updAbout() {
    var l = $about.val().length;
    $aCnt.text(l + '/' + maxAbout + ' characters')
         .css('color', l > maxAbout ? '#ff0000' : '');
    if (l === 0 || l > maxAbout) {
      $about.addClass('is-invalid');
    } else {
      $about.removeClass('is-invalid');
    }
  }

  updNick();
  updAbout();

  // ====== Init and style hobby & preference buttons ======
  $hBtns.each(function () {
    styleBtn($(this), init.hobbies.includes($(this).data('value')));
  });
  $pBtns.each(function () {
    styleBtn($(this), init.preferences.includes($(this).data('value')));
  });

  // ====== Validation helpers ======
  function validateHobbies() {
    if ($hBtns.filter('.active').length === 0) {
      $hContainer.addClass('border-danger');
    } else {
      $hContainer.removeClass('border-danger');
    }
  }

  function validatePrefs() {
    if ($pBtns.filter('.active').length === 0) {
      $pContainer.addClass('border-danger');
    } else {
      $pContainer.removeClass('border-danger');
    }
  }

  // ====== Main form validation ======
  function validateForm() {
    var okN = $nick.val().length > 0 && $nick.val().length <= maxNick,
        okA = $about.val().length > 0 && $about.val().length <= maxAbout,
        okM = $mbti.val() !== '',
        okH = $hBtns.filter('.active').length > 0,
        okP = $pBtns.filter('.active').length > 0;

    validateHobbies();
    validatePrefs();

    $save.prop('disabled', !(okN && okA && okM && okH && okP));
  }

  validateForm();

  // ====== AJAX Field Validation Logging ======
  function logValidation() {
    // ---- MISSING: sync hidden inputs BEFORE serializing ----
    $hiddenH.val(
      $hBtns.filter('.active').map((_, b) => b.dataset.value).get().join(',')
    );
    $hiddenP.val(
      $pBtns.filter('.active').map((_, b) => b.dataset.value).get().join(',')
    );

    var action = '/profile/validate',
        query  = $form.serialize();

    $.ajax({
      url:      action,
      type:     'POST',
      data:     query,
      dataType: 'json'
    })
    .done(function (res) {
      console.log(action + '?' + query + ':', res);
    })
    .fail(function (xhr, status, err) {
      console.error(action + '?' + query + ' failed:', status, err);
    });
  }

  // ====== Bind live events ======
  $nick.on('input', function () {
    updNick();
    validateForm();
    logValidation();
  });

  $about.on('input', function () {
    updAbout();
    validateForm();
    logValidation();
  });

  $mbti.on('change', function () {
    if ($mbti.val() === '') {
      $mbti.addClass('is-invalid');
    } else {
      $mbti.removeClass('is-invalid');
    }
    validateForm();
    logValidation();
  });

  $hBtns.on('click', function () {
    var $btn = $(this);
    styleBtn($btn, !$btn.hasClass('active'));
    validateHobbies();
    validateForm();
    logValidation();
  });

  $pBtns.on('click', function () {
    var $btn = $(this);
    styleBtn($btn, !$btn.hasClass('active'));
    validatePrefs();
    validateForm();
    logValidation();
  });

  // ====== Cancel resets everything ======
  $cancel.on('click', function () {
    $nick.val(init.nickname); updNick();
    $about.val(init.aboutme); updAbout();
    $mbti.val(init.mbti).removeClass('is-invalid');

    $hBtns.each(function () {
      styleBtn($(this), init.hobbies.includes($(this).data('value')));
    });
    $pBtns.each(function () {
      styleBtn($(this), init.preferences.includes($(this).data('value')));
    });

    $hContainer.removeClass('border-danger');
    $pContainer.removeClass('border-danger');
    validateForm();
    logValidation();
  });

  // ====== Save with confirmation ======
  $form.on('submit', function (e) {
    // also sync hidden inputs one last time
    $('#hiddenHobbies').val(
      $hBtns.filter('.active').map((_, b) => b.dataset.value).get().join(',')
    );
    $('#hiddenPrefs').val(
      $pBtns.filter('.active').map((_, b) => b.dataset.value).get().join(',')
    );

    e.preventDefault();
    if ($save.prop('disabled')) return;

    if (confirm('Are you sure you want to update your profile?')) {
      $form.off('submit').submit();
    } else {
      alert('Profile update canceled.');
    }
  });

  // ====== Trigger initial AJAX validation ======
  logValidation();
});
