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
        console.log('✅ /api/validate-profile response:', res);
        ['nickname', 'aboutme', 'mbti', 'hobbies', 'preferences'].forEach(fld => {
          const cap = fld.charAt(0).toUpperCase() + fld.slice(1);
          $(`#error${cap}`).text('');
          $(`[name=${fld}]`).removeClass('is-invalid');
        });

        // if validation failed, show new ones
        if (!res.success) {
          Object.entries(res.errors || {}).forEach(([fld, msg]) => {
            const cap = fld[0].toUpperCase() + fld.slice(1);
            console.warn(fld, msg);
            // highlight inputs/textareas
            $(`[name=${fld}]`).addClass('is-invalid');
            // highlight grids
            $(`#${fld}List`).addClass('border-danger');
            // show message
            $(`#error${cap}`).text(msg);
          });
        }
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
  $form.on('reset', function () {
    // restore values
    $nick.val(init.nickname);
    $about.val(init.aboutme);
    $mbti.val(init.mbti);
    $hBtns.each((_, b) => styleBtn($(b), init.hobbies.includes(b.dataset.value)));
    $pBtns.each((_, b) => styleBtn($(b), init.preferences.includes(b.dataset.value)));

    // clear old UI errors/styles
    ['nickname', 'aboutme', 'mbti', 'hobbies', 'preferences'].forEach(fld => {
      const cap = fld.charAt(0).toUpperCase() + fld.slice(1);
      $(`#error${cap}`).text('');
      $(`[name=${fld}]`).removeClass('is-invalid');
      if (fld === 'hobbies' || fld === 'preferences') {
        $(`#${fld}List`).removeClass('border-danger');
      }
    });

    //re‐run local form enable/disable
    validateForm();

    //re‐run AJAX validation _for each_ field so
    //  MBTI + all others get their error state applied
    ['nickname', 'aboutme', 'mbti', 'hobbies', 'preferences']
      .forEach(fld => validateField(fld));
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
  validateField('nickname');
  validateField('aboutme');
  validateField('mbti');
  validateField('hobbies');
  validateField('preferences');
});