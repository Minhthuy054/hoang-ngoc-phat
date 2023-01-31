function setInputFilter(textbox, inputFilter) {
	if(!textbox) return;
  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
    textbox.addEventListener(event, function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  });
}

jQuery(document).ready(function($){
  $('input#prefix').on('change keyup keypress', function(){
    this.value = this.value.toUpperCase();
  });
  setInputFilter(document.querySelector('input#prefix'), function(value){
    return /^\D+$/.test(value) && value.length<=15;
  });

  $('form[name="bck-setting-form"]').submit(function(e){
    var pf = $('#prefix').val();
    if(pf.length<2) {
      alert(`Tiền tố từ 2 ký tự trở lên`);
      e.preventDefault();
    }
  });
  $('input[type=checkbox]').on('click', function(e){
    this.setAttribute('value', this.checked? 1:0);
  });
});