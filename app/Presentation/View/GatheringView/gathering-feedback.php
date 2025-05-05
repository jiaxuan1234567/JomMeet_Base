<?php
$_title = 'Gathering Feedback';
require_once __DIR__ . '/../../../FileHelper.php';   // adjust path as needed
require_once __DIR__ . '/../HomeView/header.php';

/**
 * @var int   $gatheringID
 * @var array $gatheringFeedbacks  each ['feedbackDesc' => string, 'date' => string, 'profileID' => int]
 */

// Retrieve the logged-in user's profile ID from session
if (isset($_SESSION['profile']['profileID'])) {
  $loggedInProfile = (int)$_SESSION['profile']['profileID'];
} elseif (isset($_SESSION['profile_id'])) {
  $loggedInProfile = (int)$_SESSION['profile_id'];
} else {
  $loggedInProfile = null;
}
?>

<div class="container-fluid my-5 mb-5 mt-1">
  <?php if (!empty($_SESSION['flash_message'])): ?>
    <div id="flashMessage"
      class="flash-message"
      data-type="<?= htmlspecialchars($_SESSION['flash_type'] ?? '') ?>"
      data-msg="<?= htmlspecialchars($_SESSION['flash_message']) ?>">
    </div>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
  <?php endif; ?>

  <div class="container-fluid px-2" style="background-color: #F5F7F7; min-height: 100vh; padding-bottom: 200px;">

    <div class="d-flex mb-4 align-items-center border-bottom border-2 px-2 py-3">
      <div class="col-2">
        <a href="/my-gathering"><i class="bi bi-arrow-left text-black h3 m-0" style="cursor:pointer;"></i></a>
      </div>
      <div class="col-8 text-center">
        <h2 class="fw-bold mb-0 h5">Gathering Feedback</h2>
      </div>
      <div class="col-2"></div>
    </div>

    <div id="feedbackContainer" style="width: 100%; max-width: 1000px; margin: 0 auto; overflow-y: auto; padding: 10px 20px;">
      <?php if (empty($gatheringFeedbacks)): ?>
        <p class="text-center text-muted">No feedback yet. Be the first to share!</p>
      <?php else: ?>
        <?php foreach ($gatheringFeedbacks as $fb): ?>
          <?php
          $feedbackAuthor = isset($fb['profileID']) ? (int) $fb['profileID'] : 0;
          $isMine = ($feedbackAuthor === $loggedInProfile);
          ?>
          <div class="rounded p-3 mb-3 shadow-sm" style="background-color: <?= $isMine ? '#DEECFF' : '#FFFFFF' ?>;">
            <small class="text-muted"><?= htmlspecialchars($fb['date']) ?></small>
            <p class="mt-1 mb-0"><?= nl2br(htmlspecialchars($fb['feedbackDesc'])) ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Bottom Input Section -->
    <div style="background: #F5F7F7; border-top: 1px solid #ccc; padding: 15px; position: fixed; bottom: 0; left: 0; right: 0; width: 100%; z-index: 1000;">
      <div style="width: 100%; max-width: 900px; margin: 0 auto;">
        <form action="/my-gathering/postGatheringFeedback" method="POST" style="display: flex; align-items: center;">
          <input type="hidden" name="gatheringID" value="<?= (int)$gatheringID ?>">

          <textarea name="feedbackDesc"
            id="feedbackDesc"
            class="form-control"
            placeholder="Enter gathering feedback here…"
            maxlength="500"
            style="background-color: white; width: 100%; margin-right: 10px; height: 150px; border-radius: 10px;"
            required></textarea>

          <button type="submit" id="postBtn" class="btn btn-secondary" disabled style="min-width: 100px; margin-top: 110px; background-color: #A49292; border-radius: 15px;">Post</button>
        </form>
        <div id="charCount" style="text-align: right; font-size: 14px; color: grey; margin-top: 5px; margin-right: 110px;">0/500</div>
      </div>
    </div>

  </div>

</div>

<script src="/js/feedback.js"></script>