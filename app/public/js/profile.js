// public/js/profile.js
;(function($, window){
    $(function(){
      var init     = window.profileInit || {},
          maxNick  = 20,
          maxAbout = 255;
  
      // Cache elements (note the corrected selector for about_me)
      var $nick       = $('input[name=nickname]'),
          $nCnt       = $nick.siblings('.text-end'),
          $about      = $('textarea[name=aboutme]'),
          $aCnt       = $about.siblings('.text-end'),
          $mbti       = $('select[name=mbti]'),
          $hBtns      = $('#hobbiesList .hobby-btn'),
          $pBtns      = $('#preferencesList .pref-btn'),
          $hContainer = $('#hobbiesList'),
          $pContainer = $('#preferencesList'),
          $save       = $('button[type=submit]'),
          $cancel     = $('button:contains("Cancel")'),
          $form       = $('#editProfileForm');
  
      function styleBtn($b, on) {
        if (on) {
          $b.addClass('active').css({
            backgroundColor: '#569FFF',
            borderColor:     '#569FFF',
            color:           '#000'
          });
        } else {
          $b.removeClass('active').css({
            backgroundColor: '#fff',
            borderColor:     '#dee2e6',
            color:           '#000'
          });
        }
      }
  
      // initialize fields
      $nick.val(init.nickname);
      $about.val(init.aboutme);
      $mbti.val(init.mbti);
  
      function updNick() {
        var l = $nick.val().length;
        $nCnt.text(l + '/' + maxNick + ' characters')
             .css('color', l > maxNick ? '#ff0000' : '');
        $nick.toggleClass('is-invalid', (l === 0 || l > maxNick));
      }
  
      function updAbout() {
        var l = $about.val().length;
        $aCnt.text(l + '/' + maxAbout + ' characters')
             .css('color', l > maxAbout ? '#ff0000' : '');
        $about.toggleClass('is-invalid', (l === 0 || l > maxAbout));
      }
  
      updNick(); 
      updAbout();
  
      // style initial buttons
      $hBtns.each(function(){
        var val = this.dataset.value;
        styleBtn($(this), init.hobbies.includes(val));
      });
      $pBtns.each(function(){
        var val = this.dataset.value;
        styleBtn($(this), init.preferences.includes(val));
      });
  
      // bind events
      $nick.on('input', function(){
        updNick(); validateForm();
      });
      $about.on('input', function(){
        updAbout(); validateForm();
      });
      $mbti.on('change', function(){
        $(this).toggleClass('is-invalid', $(this).val() === '');
        validateForm();
      });
  
      $hBtns.on('click', function(){
        styleBtn($(this), !$(this).hasClass('active'));
        validateHobbies(); validateForm();
      });
      $pBtns.on('click', function(){
        styleBtn($(this), !$(this).hasClass('active'));
        validatePrefs(); validateForm();
      });
  
      function validateHobbies(){
        $hContainer.toggleClass('border-danger', $hBtns.filter('.active').length === 0);
      }
      function validatePrefs(){
        $pContainer.toggleClass('border-danger', $pBtns.filter('.active').length === 0);
      }
      function validateForm(){
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
  
      // Cancel
      $cancel.on('click', function(){
        $nick.val(init.nickname); updNick();
        $about.val(init.aboutme);  updAbout();
        $mbti.val(init.mbti).removeClass('is-invalid');
        $hBtns.each(function(){ styleBtn($(this), init.hobbies.includes($(this).dataset.value)); });
        $pBtns.each(function(){ styleBtn($(this), init.preferences.includes($(this).dataset.value)); });
        $hContainer.removeClass('border-danger');
        $pContainer.removeClass('border-danger');
        validateForm();
      });
  
      // Save
      $form.on('submit', function(e){
        $('#hiddenHobbies').val( 
          $hBtns.filter('.active').map((_,b)=>b.dataset.value).get().join(',')
        );
        $('#hiddenPrefs').val(
          $pBtns.filter('.active').map((_,b)=>b.dataset.value).get().join(',')
        );
        e.preventDefault();
        if ($save.prop('disabled')) return;
        if (confirm('Are you sure you want to update your profile?')) {
          $form.off('submit').submit();
        } else {
          alert('Profile update canceled.');
        }
      });
    });
  })(jQuery, window);
  