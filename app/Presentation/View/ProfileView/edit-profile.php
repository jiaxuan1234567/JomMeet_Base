<?php
$_title = 'Edit Profile';
require_once __DIR__ . '/../HomeView/header.php';
// $userid = $_SESSION['profile_id'] ?? null;
$profile = $_SESSION['profile'] ?? [];

$errors          = $_SESSION['profileErrors']   ?? [];
$old             = $_SESSION['oldProfile']      ?? [];
unset($_SESSION['profileErrors'], $_SESSION['oldProfile']);

$selectedMBTI = $profile['mbti']    ?? '';
$savedHobbies = $_SESSION['profile']['hobbies'] ?? [];
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

      <input type="text" id="nickname" name="nickname" class="form-control w-75" maxlength="30" value="<?php echo htmlspecialchars($profile['nickname'] ?? '') ?>" />
      <div id="nicknameCount" class="d-block text-end fs-6 w-75" style="color:#0C0C0D; opacity:40%;">0/20 characters</div>
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
      <textarea id="aboutme" name="aboutme" class="form-control" rows="4" maxlength="270" style="resize: none;" required><?php echo htmlspecialchars($profile['aboutme']) ?></textarea>
      <div id="aboutCount" class="d-block text-end fs-6" style="color:#0C0C0D; opacity:40%;">0/255 characters</div>
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

        <?php foreach ($preferenceOptions as $pref):
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

  <input type="hidden" name="hobbies" id="hiddenHobbies">
  <input type="hidden" name="preferences" id="hiddenPrefs">

  <!-- Form Buttons -->
  <div class="col-12 d-flex justify-content-center gap-3 mt-4 mb-5">
    <button type="button" id="cancelBtn" class="btn py-2 px-4" style="border-color: #000000; color:#000000; background-color:#ffffff; width: 7%;">Cancel</button>
    <button type="submit" id="saveBtn" class="btn btn-primary py-2 px-4" style="width: 7%;">Save</button>
  </div>

</form>

<script>
  window.profileInit = {
    nickname: <?= json_encode($profile['nickname']   ?? '') ?>,
    aboutme: <?= json_encode($profile['aboutme']    ?? '') ?>,
    mbti: <?= json_encode($selectedMBTI          ?? '') ?>,
    hobbies: <?= json_encode($savedHobbies) ?>,
    preferences: <?= json_encode($savedPrefs) ?>
  };
</script>

<script src="/js/profile.js"></script>

<?php require_once __DIR__ . '/../HomeView/footer.php'; ?>