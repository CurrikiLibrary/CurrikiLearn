$(document).ready(function(){	
	var modal 	= $('#myModal'),
		btn 	= $('#myBtn'),
		span 	= $('.close')[0];
	
	$(window).on('load', function(){
		modal.show();
	});
	
	$(span).on('click', function(){
		modal.hide();
	});
	
	$(document).on('click', function(event){
		if ($(event.target).is(modal)) {
			modal.hide();
		}
	});
	
	$('.dropdown-toggle').on('click', function(e){
		$(this).parent().toggleClass('show');
		$(this).next('.dropdown-menu').toggleClass('show');
		e.preventDefault();
	});
/*	
	$('.nav-tabs > li > a').on('click', function(e){
		var tab_id = $(this).attr('href');
		$('.nav-tabs > li > a').removeClass('active');
		$('.tab-pane').removeClass('active');
		$(this).addClass('active');
		$(tab_id).addClass('active');
		e.preventDefault();
	});

	$('.js-select').select2({
		width: '100%'
	});
	*/	
});