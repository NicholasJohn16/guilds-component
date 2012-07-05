$(document).ready(function() {
	
	$('ul.nav-list li').click(function(event){
		$('ul.nav-list li').removeClass('active');
		$(this).addClass('active');
	});
	
});