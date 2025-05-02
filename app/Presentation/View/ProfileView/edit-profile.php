<?php
$_title = 'Edit Profile';
require_once __DIR__ . '/../HomeView/header.php';
// $userid = $_SESSION['profile_id'] ?? null;
$profile = $_SESSION['profile'] ?? [];
$selectedMBTI = $profile['mbti']    ?? '';
// $types = [
//     'INTJ', 'INTP', 'ENTJ', 'ENTP', 'INFJ', 'INFP', 'ENFJ', 'ENFP', 'ISTJ', 'ISFJ', 'ESTJ', 'ESFJ', 'ISTP', 'ISFP', 'ESTP', 'ESFP'
// ];
$savedHobbies = $_SESSION['profile']['hobbies'] ?? [];
// Your full list of hobby options
// $hobbyOptions = [
//   'Basketball',
//   'Badminton',
//   'Hiking',
//   'Singing',
//   'Photography',
//   'Reading',
//   'Jogging',
//   'Camping',
//   'Traveling',
//   'Swimming',
//   'Yoga',
//   'Meditation',
//   'Drawing',
//   'Painting',
//   'Squash',
//   'Gym'
// ];
$savedPrefs = $_SESSION['profile']['preferences'] ?? [];
?>

<div class="container-fluid my-3">
  <!-- Header with “Profile” title and edit button -->
  <div class="d-flex align-items-center mb-1">
    <div class="ms-3 mt-2">
      <a href="/profile">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#333333" class="bi bi-arrow-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
        </svg>
      </a>
    </div>
    <h2 class="fw-bold flex-grow-1 text-center">Profile</h2>
  </div>
</div>
<div>
  <hr class="border-2 border-top border-dark mb-3">
</div>

<!-- Phone Number -->
<div class="row mb-4">
  <div class="col-md-9 offset-md-1">
    <label for="phone" class="form-label fw-bold fs-5">Phone Number</label>
    <input
      type="text"
      id="phone"
      class="form-control border roundedv w-75"
      style="background-color: #e8e8e8; border-color: #0077CC;"
      value="<?php echo htmlspecialchars($profile['phone'] ?? '') ?>"
      readonly>
  </div>
</div>

<form id="editProfileForm" action="/profile/edit" method="POST">
  <!-- Nickname and MBTI -->
  <div class="row justify-content-center g-3 mb-4">
    <div class="col-md-9">
      <label for="nickname" class="form-label fw-bold fs-5">Nickname</label>

      <input type="text" name="nickname" class="form-control w-75" maxlength="30" value="<?php echo htmlspecialchars($profile['nickname'] ?? '') ?>" />
      <div class="d-block text-end fs-6 w-75" style="color:#0C0C0D; opacity:40%;">0/20 characters</div>
    </div>


    <div class="col-md-1">
      <label for="mbti" class="form-label fw-bold fs-5">MBTI</label>
      <select class="form-select" name="mbti" required>
        <option value="" disabled <?= $selectedMBTI === '' ? 'selected' : '' ?>>Select</option>
        <?php foreach ($types as $t): ?>
          <option
            value="<?= htmlspecialchars($t) ?>"
            <?= $selectedMBTI === $t ? 'selected' : '' ?>>
            <?= htmlspecialchars($t) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- About Me -->
  <div class="row mb-4">
    <div class="col-md-10 offset-md-1">
      <label class="form-label fw-bold fs-5">About Me</label>
      <textarea name="about_me" class="form-control" rows="4" maxlength="270" style="resize: none;" required><?php echo htmlspecialchars($profile['aboutme']) ?></textarea>
      <div class="d-block text-end fs-6" style="color:#0C0C0D; opacity:40%;">0/255 characters</div>
    </div>
  </div>

  <!-- Hobbies -->
  <div class="row mb-4">
    <div class="col-md-10 offset-md-1" style="display: grid;">
      <h6 class="fw-bold fs-5">Hobbies</h6>
      <div class="border rounded p-3 mb-4" id="hobbiesList" style="display: grid;grid-template-columns: repeat(8, 1fr); gap: 1.5rem; background-color: #ffffff;">
        <?php foreach ($hobbyOptions as $hobby):
          $isActive = in_array($hobby, $savedHobbies, true);
          $btnClass = $isActive
            ? 'background-color: #569FFF; btn btn-primary text-dark w-100 fw-bold'
            : 'btn btn-outline-dark w-100 fw-bold';
        ?>
          <button
            type="button"
            class="<?= $btnClass ?> hobby-btn"
            style="<?= $btnClass ?>"
            data-value="<?php echo htmlspecialchars($hobby) ?>">
            <?php echo htmlspecialchars($hobby) ?>
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Preferences -->
  <div class="row mb-4">
    <div class="col-md-10 offset-md-1" style="display: grid;">
      <h6 class="fw-bold fs-5">Preference Gathering</h6>
      <div class="border rounded p-3 mb-4" id="preferencesList" style="display: grid;grid-template-columns: repeat(8, 1fr); gap: 1.5rem; background-color: #ffffff;">

        <?php
        $options = [
          'Entertainment',
          'Sports',
          'Dining',
          'Nature',
          'Hangout',
          'Coffee',
          'Picnic',
          'Chill'
        ];
        foreach ($options as $pref):
          $active = in_array($pref, $savedPrefs, true);
          $btnCls = $active
            ? 'background-color: #569FFF; btn btn-primary text-dark w-100 fw-bold'
            : 'btn btn-outline-dark w-100 fw-bold';
        ?>
          <button
            type="button"
            class="<?= $btnCls ?> pref-btn"
            style="<?= $btnCls ?>"
            data-value="<?php echo htmlspecialchars($pref) ?>">
            <?php echo htmlspecialchars($pref) ?>
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Form Buttons -->
  <div class="col-12 d-flex justify-content-center gap-3 mt-4 mb-5">
    <button type="button" class="btn py-2 px-4" style="border-color: #000000; color:#000000; background-color:#ffffff; width: 7%;">Cancel</button>
    <button type="submit" class="btn btn-primary py-2 px-4" style="width: 7%;">Save</button>
  </div>

</form>

<script>
  $(function() {
    // Initial data from PHP session
    var init = {
      nickname: <?= json_encode($profile['nickname']   ?? '') ?>,
      about_me: <?= json_encode($profile['aboutme']    ?? '') ?>,
      mbti: <?= json_encode($selectedMBTI          ?? '') ?>,
      hobbies: <?= json_encode($savedHobbies) ?>,
      preferences: <?= json_encode($savedPrefs) ?>
    };
    var maxNick = 20,
      maxAbout = 255;

    // Cache elements
    var $nick = $('input[name=nickname]'),
      $nCnt = $nick.siblings('.text-end'),
      $about = $('textarea[name=about_me]'),
      $aCnt = $about.siblings('.text-end'),
      $mbti = $('select[name=mbti]'),
      $hBtns = $('#hobbiesList .hobby-btn'),
      $pBtns = $('#preferencesList .pref-btn'),
      $hContainer = $('#hobbiesList'),
      $pContainer = $('#preferencesList'),
      $save = $('button[type=submit]'),
      $cancel = $('button:contains("Cancel")'),
      $form = $('#editProfileForm');

    // Style toggle helper
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

    // Initialize fields and counters
    $nick.val(init.nickname);
    $about.val(init.about_me);
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

    // Init and style buttons
    $hBtns.each(function() {
      styleBtn($(this), init.hobbies.includes($(this).data('value')));
    });
    $pBtns.each(function() {
      styleBtn($(this), init.preferences.includes($(this).data('value')));
    });

    // Bind live events
    $nick.on('input', function() {
      updNick();
      validateForm();
    });
    $about.on('input', function() {
      updAbout();
      validateForm();
    });
    $mbti.on('change', function() {
      if ($mbti.val() === '') $mbti.addClass('is-invalid');
      else $mbti.removeClass('is-invalid');
      validateForm();
    });

    // Toggling on click
    $hBtns.on('click', function() {
      var $btn = $(this);
      styleBtn($btn, !$btn.hasClass('active'));
      validateHobbies();
      validateForm();
    });
    $pBtns.on('click', function() {
      var $btn = $(this);
      styleBtn($btn, !$btn.hasClass('active'));
      validatePrefs();
      validateForm();
    });

    // Validation helper for hobbies & prefs
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

    // Main form validation
    function validateForm() {
      var okN = $nick.val().length > 0 && $nick.val().length <= maxNick,
        okA = $about.val().length > 0 && $about.val().length <= maxAbout,
        okM = $mbti.val() !== '',
        okH = $hBtns.filter('.active').length > 0,
        okP = $pBtns.filter('.active').length > 0;
      validateHobbies();
      validatePrefs();
      if (okN && okA && okM && okH && okP) {
        $save.prop('disabled', false);
      } else {
        $save.prop('disabled', true);
      }
    }
    validateForm();

    // Cancel resets everything
    $cancel.on('click', function() {
      $nick.val(init.nickname);
      updNick();
      $about.val(init.about_me);
      updAbout();
      $mbti.val(init.mbti).removeClass('is-invalid');
      $hBtns.each(function() {
        styleBtn($(this), init.hobbies.includes($(this).data('value')));
      });
      $pBtns.each(function() {
        styleBtn($(this), init.preferences.includes($(this).data('value')));
      });
      $hContainer.removeClass('border-danger');
      $pContainer.removeClass('border-danger');
      validateForm();
    });

    // Save with confirmation
    $form.on('submit', function(e) {
      e.preventDefault();
      if ($save.prop('disabled')) return;
      if (confirm('Are you sure you want to update your profile?')) {
        // unbind to avoid infinite loop, then submit
        $form.off('submit').submit();
      } else {
        alert('Profile update canceled.');
      }
    });
  });
</script>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>