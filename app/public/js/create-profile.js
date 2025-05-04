$(function () {
  // ====== Initial data ======
  var init = window.profileInit || { nickname: '', aboutme: '', mbti: '', hobbies: [], preferences: [] };
  var maxNick = 20, maxAbout = 255;

  // ====== Cache elements ======
  var $nick = $('input[name=nickname]'),
    $nCnt = $nick.siblings('.text-end'),
    $about = $('textarea[name=aboutme]'),
    $aCnt = $about.siblings('.text-end'),
    $mbti = $('select[name=mbti]'),
    $hBtns = $('#hobbiesList .hobby-btn'),
    $pBtns = $('#preferencesList .pref-btn'),
    $hContainer = $('#hobbiesList'),
    $pContainer = $('#preferencesList'),
    $create = $('#createBtn'),
    $reset = $('#resetBtn'),
    $form = $('#profileForm'),
    $hiddenH = $('#hiddenHobbies'),
    $hiddenP = $('#hiddenPrefs');

  // ====== Initialize hidden fields ======
  $hiddenH.val(init.hobbies.join(','));
  $hiddenP.val(init.preferences.join(','));

  // ====== Style toggle helper ======
  function styleBtn($b, on) {
    if (on) {
      $b.addClass('active').css({ backgroundColor: '#569FFF', borderColor: '#569FFF', color: '#000' });
    } else {
      $b.removeClass('active').css({ backgroundColor: '#fff', borderColor: '#dee2e6', color: '#000' });
    }
  }

  // ====== Initialize fields and counters ======
  $nick.val(init.nickname);
  $about.val(init.aboutme);
  $mbti.val(init.mbti);

  function updNick() {
    var l = $nick.val().length;
    $nCnt.text(l + '/' + maxNick + ' characters').css('color', l > maxNick ? '#ff0000' : '');
    $nick.toggleClass('is-invalid', l === 0 || l > maxNick);
  }

  function updAbout() {
    var l = $about.val().length;
    $aCnt.text(l + '/' + maxAbout + ' characters').css('color', l > maxAbout ? '#ff0000' : '');
    $about.toggleClass('is-invalid', l === 0 || l > maxAbout);
  }

  updNick(); updAbout();

  // ====== Init and style buttons ======
  $hBtns.each(function () { styleBtn($(this), init.hobbies.includes($(this).data('value'))); });
  $pBtns.each(function () { styleBtn($(this), init.preferences.includes($(this).data('value'))); });

  // ====== Validation helpers ======
  function validateHobbies() {
    $hContainer.toggleClass('border-danger', $hBtns.filter('.active').length === 0);
  }
  function validatePrefs() {
    $pContainer.toggleClass('border-danger', $pBtns.filter('.active').length === 0);
  }

  // ====== Main form validation ======
  function validateForm() {
    var okN = $nick.val().length > 0 && $nick.val().length <= maxNick,
      okA = $about.val().length > 0 && $about.val().length <= maxAbout,
      okM = $mbti.val() !== '',
      okH = $hBtns.filter('.active').length > 0,
      okP = $pBtns.filter('.active').length > 0;

    validateHobbies(); validatePrefs();
    $create.prop('disabled', !(okN && okA && okM && okH && okP));
  }
  validateForm();

  // ====== AJAX validation ======
  function logValidation() {
    // sync hidden inputs
    $hiddenH.val($hBtns.filter('.active').map((_, b) => b.dataset.value).get().join(','));
    $hiddenP.val($pBtns.filter('.active').map((_, b) => b.dataset.value).get().join(','));

    var action = '/profile/validate',
      query = $form.serialize();

    $.ajax({
      url: action,
      type: 'POST',
      data: query,
      dataType: 'json'
    })
      .done(function (res) {
        console.log(action + '?' + query + ':', res);
      })
      .fail(function (xhr, status, err) {
        console.error(action + '?' + query + ' failed:', status, err);
      });
  }

  // ====== Bind events ======
  $nick.on('input', function () { updNick(); validateForm(); logValidation(); });
  $about.on('input', function () { updAbout(); validateForm(); logValidation(); });
  $mbti.on('change', function () { $mbti.toggleClass('is-invalid', $mbti.val() === ''); validateForm(); logValidation(); });
  $hBtns.on('click', function () { styleBtn($(this), !$(this).hasClass('active')); validateForm(); logValidation(); });
  $pBtns.on('click', function () { styleBtn($(this), !$(this).hasClass('active')); validateForm(); logValidation(); });

  // ====== Reset form ======
  $reset.on('click', function () {
    $nick.val(''); updNick();
    $about.val(''); updAbout();
    $mbti.val('').removeClass('is-invalid');
    $hBtns.each(function () { styleBtn($(this), false); });
    $pBtns.each(function () { styleBtn($(this), false); });
    $hContainer.removeClass('border-danger'); $pContainer.removeClass('border-danger');
    validateForm(); logValidation();
  });

  // ====== Form submit ======
  $form.on('submit', function (e) {
    e.preventDefault();
    if ($create.prop('disabled')) return;
    $hiddenH.val($hBtns.filter('.active').map((_, b) => b.dataset.value).get().join(','));
    $hiddenP.val($pBtns.filter('.active').map((_, b) => b.dataset.value).get().join(','));
    if (confirm('Confirm to submit your profile?')) {
      $form.off('submit').submit();
    }
  });

  // ====== Initial AJAX ======
  logValidation();
});
