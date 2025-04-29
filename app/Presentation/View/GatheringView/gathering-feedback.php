<?php
$_title = 'Gathering Feedback';
require_once __DIR__ . '/../../../FileHelper.php'; // adjust the path accordingly
require_once __DIR__ . '/../HomeView/header.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Gathering Feedback</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #F5F5F7; margin: 0; padding: 0; height: 100vh; display: flex; flex-direction: column;">

  <!-- Header -->
  <div style="position: relative; text-align: center; padding: 20px 0 10px 0;">
    <button onclick="goBack()" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; font-size: 24px;">&#8592;</button>
    <h1 style="font-size: 30px;">Gathering Feedback</h1>
    <hr style="margin-top: 20px;">
  </div>

  <!-- Feedback Content -->
  <div id="feedbackContainer" style="width: 100%; max-width: 1000px; margin: 0 auto; overflow-y: auto; padding: 10px 20px; ">

    <!-- Example feedbacks -->
    <div style="background-color: #DEECFF;; border-radius: 10px; padding: 15px; margin-bottom: 15px; display: flex; align-items: flex-start;">
      <img src="../../../public/asset/userIcon.png" alt="User Icon" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 15px;">
      <div>This is a feedback example 1.</div>
    </div>


    <!-- Bottom Input Section -->
    <div style="background: #F5F5F7; border-top: 1px solid #ccc; padding: 15px; position: fixed; bottom: 0; left: 0; right: 0; width: 100%; z-index: 1000;">
      <div style="width: 100%; max-width: 900px; margin: 0 auto;">
        <div style="display: flex; align-items: center;">
          <textarea
            id="gatheringFeedback"
            class="form-control"
            placeholder="Enter gathering feedback here...... Don’t worry, it will be anonymous."
            maxlength="500"
            style="background-color: white; width: 100%; margin-right: 10px; height: 150px;"></textarea>

          <button id="postBtn" class="btn btn-secondary" disabled style="min-width: 100px; margin-top: 110px; background-color: #A49292; border-radius: 15px;">Post</button>
        </div>
        <div id="charCount" style="text-align: right; font-size: 14px; color: grey; margin-top: 5px; margin-right: 110px;">0/500</div>
      </div>
    </div>

    <div id="jomAlert" style="
    position: fixed;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #f8f8f8;
    border: 1px solid #888;
    border-radius: 6px;
    width: 300px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    padding: 0;
    font-family: system-ui, sans-serif;
    z-index: 9999;
    display: none;
">
  <div style="background-color: #569FFF; color: white; padding: 10px 15px; border-top-left-radius: 6px; border-top-right-radius: 6px;">
    JomMeet says
  </div>
  <div style="padding: 15px; font-size: 14px; color: #000;">
    Your feedback has been successfully submitted.
  </div>
  <div style="padding: 10px; text-align: right;">
    <button onclick="document.getElementById('jomAlert').style.display='none';"
      style="padding: 5px 12px; font-size: 13px; border: none; background-color: #569FFF; color: white; border-radius: 4px; cursor: pointer;">
      OK
    </button>
  </div>
</div>


</body>

<script>
  // Get status from URL
  const urlParams = new URLSearchParams(window.location.search);
  const gatheringStatus = urlParams.get('status');

  function goBack() {
    window.location.href = 'my-gathering.php#' + encodeURIComponent(gatheringStatus);
  }

  // Get the feedback container, feedback input, and other elements
const gatheringFeedbackInput = document.getElementById('gatheringFeedback');
const postBtn = document.getElementById('postBtn');
const charCount = document.getElementById('charCount');
const feedbackContainer = document.getElementById('feedbackContainer');

// Flag to track if feedback has been posted
let hasPostedFeedback = false;

// Handle feedback input event
gatheringFeedbackInput.addEventListener('input', function() {
  const length = this.value.length;
  charCount.textContent = `${length}/500`;

  // Enable the Post button if input is valid and user hasn't posted yet
  if (length > 0 && length <= 500 && !hasPostedFeedback) {
    postBtn.classList.remove('btn-secondary');
    postBtn.classList.add('btn-primary');
    postBtn.disabled = false;
    postBtn.style.backgroundColor = '#569FFF';  // Set background color when active
  } else {
    postBtn.classList.remove('btn-primary');
    postBtn.classList.add('btn-secondary');
    postBtn.disabled = true;
    postBtn.style.backgroundColor = '';  // Reset the background color
  }
});

// Handle post button click
postBtn.addEventListener('click', function() {
  if (gatheringFeedbackInput.value.trim() !== '' && gatheringFeedbackInput.value.length <= 500) {
    // Create feedback box and append it
    const feedbackBox = document.createElement('div');
    feedbackBox.setAttribute('style', 'background-color: #DEECFF; border-radius: 10px; padding: 15px; margin-bottom: 15px; display: flex; align-items: flex-start;');

    feedbackBox.innerHTML = `
      <img src="../../../public/asset/userIcon.png" alt="User Icon" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 15px;">
      <div style="word-break: break-word;">${gatheringFeedbackInput.value}</div>
    `;

    feedbackContainer.appendChild(feedbackBox);

    // Clear the feedback input and reset character count
    gatheringFeedbackInput.value = '';
    charCount.textContent = '0/500';

    // Disable the Post button permanently for this user
    postBtn.classList.remove('btn-primary');
    postBtn.classList.add('btn-secondary');
    postBtn.disabled = true;
    postBtn.style.backgroundColor = '';  // Reset the background color

    // Set the flag to indicate the user has posted feedback
    hasPostedFeedback = true;

    // Optional: Show a message indicating that only one feedback can be posted
    document.getElementById('jomAlert').style.display = 'block';
  }
});


</script>

</html>

