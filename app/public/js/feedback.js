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

textarea.addEventListener('keydown', (event) => {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault();
    if (!hasPostedFeedback && textarea.value.length > 0 && textarea.value.length <= 500) {
      postBtn.click();
    }
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
  window.location.href = `/my-gathering#completed`;
}