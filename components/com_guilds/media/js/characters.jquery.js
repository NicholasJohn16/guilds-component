$(document).ready(function() {
	
        // Change some of the editable plugin defaults.
        //$.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.emptytext = '';
        $.fn.editable.defaults.emptyclass = '';
        
        $('.editable').editable({
           url:'index.php?option=com_guilds&view=characters&task=update&format=ajax'
        });
        
	
	$('div[data-toggle="buttons-radio"]').click(function(event){
		event.preventDefault();
	});
	
	$('select').change(function(event){
		$('#roster-form').submit();
	});
	
	$('button[type="reset"]').click(function(event) {
		$('input[name="search"]').val("");
		$('select[name^="filter_type"]').val('');
		$('#roster-form').submit();
	});
	
	$('.com-guilds.header a').click(function(event){
		event.preventDefault();
		var order = $(this).attr('data-order').replace(" ","_");
		var direction = $(this).attr('data-direction');
				
		$('input[name="order"]').val(order);
		$('input[name="direction"]').val(direction);
		
		
		$("#roster-form").submit();
	});
	
	$('a[data-task="delete"]').click(function(event){
		$('#roster-form input[name="task"]').val('delete');
		$('#roster-form').submit();
	});
	
	// Whenever the form is submitted, reset the limit to 0
	// So the user is return to the first page
	$('#roster-form').submit(function(event){
		$('#roster-form').children('input[name="limitstart"]').val(0);
	});
	
	$('#checkAll').click(function(event){
		var checkboxes = $('input[name="characters[]"]');
		if($(this).attr('checked') == 'checked'){
			   var bool = true;
	   	   } else {
			   var bool = false;
		   }
		   for(var i = 0;i<checkboxes.length;i++){
			   checkboxes[i].checked = bool;
		   };
	});
	
	$('#datepicker').datepicker({
		format:'yyyy-mm-dd',
		todayBtn:true,
		autoclose:true
	});
	
 });