 $("#login-button").click(function(event){
		 event.preventDefault();

	 $('form').fadeOut(500);
	 $('.wrapper').addClass('form-success');
  document.getElementById("form").submit();
});
