$(document).ready(function () {
  // ====== Cache elements ======
  const init = window.profileInit;
  const maxNick = 20, maxAbout = 255;
  const $form = $('#profileForm');
  const $nick = $('input[name=nickname]');
  const $nCnt = $('#nicknameCount');
  const $about = $('textarea[name=aboutme]');
  const $aCnt = $('#aboutCount');
  const $mbti = $('select[name=mbti]');
  const $hBtns = $('#hobbiesList .hobby-btn');
  const $pBtns = $('#preferencesList .pref-btn');
  const $hiddenH = $('#hiddenHobbies');
  const $hiddenP = $('#hiddenPrefs');
  const $create = $('#createBtn');
  const $reset = $('#resetBtn');

  // ====== Initialize hidden fields ======
  $hiddenH.val(init.hobbies.join(','));
  $hiddenP.val(init.preferences.join(','));

  // ====== Style toggle helper ======
  function styleBtn($b, on) {
    $b.toggleClass('active', on)
      .css(on
        ? { backgroundColor: '#569FFF', borderColor: '#569FFF', color: '#000' }
        : { backgroundColor: '#fff', borderColor: '#dee2e6', color: '#000' }
      );
  }

  // ====== Initialize fields and counters ======
  $nick.val(init.nickname);
  $about.val(init.aboutme);
  $mbti.val(init.mbti);

  // counter updaters
  function updNick() {
    const l = $nick.val().length;
    $nCnt.text(`${l}/${maxNick} characters`)
      .css('color', l > maxNick ? '#f00' : '');
    $nick.toggleClass('is-invalid', l === 0 || l > maxNick);
  }

  function updAbout() {
    const l = $about.val().length;
    $aCnt.text(`${l}/${maxAbout} characters`)
      .css('color', l > maxAbout ? '#f00' : '');
    $about.toggleClass('is-invalid', l === 0 || l > maxAbout);
  }

  // ====== Init and style buttons ======
  $hBtns.each((_, b) => styleBtn($(b), init.hobbies.includes(b.dataset.value)));
  $pBtns.each((_, b) => styleBtn($(b), init.preferences.includes(b.dataset.value)));

  // ====== Validation helpers ======
  function validateHobbies() {
    $('#hobbiesList').toggleClass('border-danger',
      $hBtns.filter('.active').length === 0
    );
  }
  function validatePrefs() {
    $('#preferencesList').toggleClass('border-danger',
      $pBtns.filter('.active').length === 0
    );
  }

  // ====== Main form validation ======
  function validateForm() {
    updNick(); updAbout();
    validateHobbies(); validatePrefs();

    const okN = $nick.val().trim().length > 0 && $nick.val().length <= maxNick;
    const okA = $about.val().trim().length > 0 && $about.val().length <= maxAbout;
    const okM = $mbti.val() !== '';
    const okH = $hBtns.filter('.active').length > 0;
    const okP = $pBtns.filter('.active').length > 0;

    $create.prop('disabled', !(okN && okA && okM && okH && okP));
    if ($create.prop('disabled')) {
      $create.css({ backgroundColor: 'grey', borderColor: 'grey', cursor: 'not-allowed', opacity: 0.7 });
    } else {
      $create.css({ backgroundColor: '', borderColor: '', cursor: '', opacity: '' });
    }
  }

  // full‐form AJAX validation on every change
  function validateField(field) {
    // sync hidden arrays
    $hiddenH.val($hBtns.filter('.active').map((_, b) => b.dataset.value).get().join(','));
    $hiddenP.val($pBtns.filter('.active').map((_, b) => b.dataset.value).get().join(','));

    const payload = $form.serialize();
    console.log('🔍 validating', field, payload);

    $.ajax({
      url: '/api/validate-profile',
      type: 'POST',
      data: payload,
      dataType: 'json',
      success(res) {
        console.log('✅ /api/validate-profile', res);
        if (res.success) {
          // clear all error boxes
          ['Nickname', 'Aboutme', 'Mbti', 'Hobbies', 'Preferences']
            .forEach(s => $(`#error${s}`).text(''));
        } else {
          // show errors
          Object.entries(res.errors || {}).forEach(([fld, msg]) => {
            const label = fld.charAt(0).toUpperCase() + fld.slice(1);
            $(`#error${label}`).text(msg);
          });
        }
      },
      error(xhr, status, err) {
        console.error('❌ Validation failed:', status, err);
      }
    });
  }

  // ====== Bind events ======
  $nick.on('input change', () => { validateForm(); validateField('nickname'); });
  $about.on('input change', () => { validateForm(); validateField('aboutme'); });
  $mbti.on('change', () => { validateForm(); validateField('mbti'); });
  $hBtns.on('click', function () {
    styleBtn($(this), !$(this).hasClass('active'));
    validateForm(); validateField('hobbies');
  });
  $pBtns.on('click', function () {
    styleBtn($(this), !$(this).hasClass('active'));
    validateForm(); validateField('preferences');
  });

  // ====== Reset form ======
  $reset.on('click', function () {
    $nick.val(''); updNick();
    $about.val(''); updAbout();
    $mbti.val('').removeClass('is-invalid');
    $hBtns.each(function () { styleBtn($(this), false); });
    $pBtns.each(function () { styleBtn($(this), false); });
    $hContainer.removeClass('border-danger'); $pContainer.removeClass('border-danger');
    validateForm();
  });

  // ====== Form submit ======
  $form.on('submit', function (e) {
    $hiddenH.val($hBtns.filter('.active').map((_, b) => b.dataset.value).get().join(','));
    $hiddenP.val($pBtns.filter('.active').map((_, b) => b.dataset.value).get().join(','));

    e.preventDefault();
    if ($create.prop('disabled')) return;

    if (confirm('Confirm to submit your profile?')) {
      $form.off('submit').submit();
    }
  });

  // initial run
  validateForm();
});