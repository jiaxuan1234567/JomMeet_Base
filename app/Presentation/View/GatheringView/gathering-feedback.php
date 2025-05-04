<?php
$_title = 'Gathering Feedback';
require_once __DIR__ . '/../../../FileHelper.php';   // adjust path
require_once __DIR__ . '/../HomeView/header.php';

/**
 * @var int   $gatheringID
 * @var array $gatheringFeedbacks  each ['feedbackDesc' => string, 'date' => string]
 */
?>

<div class="container-fluid my-5 mb-5 mt-1">
    <?php if (!empty($_SESSION['flash_message'])): ?>
        <div id="flashMessage"
             class="flash-message"
             data-type="<?= $_SESSION['flash_type'] ?? '' ?>"
             data-msg="<?= $_SESSION['flash_message'] ?>">
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

<div class="container-fluid px-2" style="background-color: #F5F5F7; min-height: 100vh; padding-bottom: 200px;">

<div style="position: relative; text-align: center; padding: 20px 0 10px 0;">
<button type="button" onclick="goBack()" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; font-size: 24px;">&#8592;</button>
  <h1 style="font-size: 30px;">Gathering Feedback</h1>
  <hr style="margin-top: 20px;">
</div>

<div id="feedbackContainer" style="width: 100%; max-width: 1000px; margin: 0 auto; overflow-y: auto; padding: 10px 20px; ">
  <?php if (empty($gatheringFeedbacks)): ?>
    <p class="text-center text-muted">No feedback yet. Be the first to share!</p>
  <?php else: ?>
    <?php foreach ($gatheringFeedbacks as $fb): ?>
      <div class="bg-white rounded p-3 mb-3 shadow-sm">
        <small class="text-muted"><?= htmlspecialchars($fb['date']) ?></small>
        <p class="mt-1 mb-0"><?= nl2br(htmlspecialchars($fb['feedbackDesc'])) ?></p>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<!-- Bottom Input Section -->
<div style="background: #F5F5F7; border-top: 1px solid #ccc; padding: 15px; position: fixed; bottom: 0; left: 0; right: 0; width: 100%; z-index: 1000;">
  <div style="width: 100%; max-width: 900px; margin: 0 auto;">
    <form action="/my-gathering/gatheringFeedback" method="POST" style="display: flex; align-items: center;">
      <input type="hidden" name="gatheringID" value="<?= (int)$gatheringID ?>">

      <textarea name="feedbackDesc"
        id="feedbackDesc"
        class="form-control"
        placeholder="Enter location feedback here……"
        maxlength="500"
        style="background-color: white; width: 100%; margin-right: 10px; height: 150px; border-radius: 10px;"
        required></textarea>

      <button type="submit" id="postBtn" class="btn btn-secondary"
        disabled style="min-width: 100px; margin-top: 110px; background-color: #A49292; border-radius: 15px;">Post</button>
    </form>
    <div id="charCount" style="text-align: right; font-size: 14px; color: grey; margin-top: 5px; margin-right: 110px;">0/500</div>
  </div>
</div>
</div>

<!-- <script>
  const textarea = document.getElementById('feedbackDesc');
  const postBtn = document.getElementById('postBtn');
  const charCount = document.getElementById('charCount');
  let hasPostedFeedback = false;

  textarea.addEventListener('input', () => {
    const len = textarea.value.length;
    charCount.textContent = `${len}/500`;

    if (len > 0 && len <= 500 && !hasPostedFeedback) {
      postBtn.disabled = false;
      postBtn.classList.remove('btn-secondary');
      postBtn.classList.add('btn-primary');
      postBtn.style.backgroundColor = '#569FFF';
    } else {
      postBtn.disabled = true;
      postBtn.classList.remove('btn-primary');
      postBtn.classList.add('btn-secondary');
      postBtn.style.backgroundColor = '#A49292';
    }
  });

  postBtn.addEventListener('click', (e) => {
    if (hasPostedFeedback) {
      e.preventDefault();
      alert("You have already posted feedback.");
    } else {
      hasPostedFeedback = true;
    }
  });

  function goBack() {
    const status = document.getElementById('gatheringStatus')?.value || '';
    window.location.href = `/my-gathering?status=completed`;
  }
</script> -->

<script src="/js/feedback.js"></script>




