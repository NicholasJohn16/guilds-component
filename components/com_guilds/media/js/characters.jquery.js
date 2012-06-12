$(document).ready(function() {
	
	$('option').click(function() {
		var parent = $(this).attr('data-parent');
		var children = $(this).attr('data-children');

		selectParents(parent);
	});
	
	function selectParents(parent_id) {
		var options = $('option').each(function(index,element){
			if($(element).val() == parent_id){
				$(element).parent('select').val(parent_id);
				var new_parent = $(element).attr('data-parent');
				if(new_parent != "0") {
					selectParents(new_parent);
				}
			}
		});
	}
	
	$('div[data-toggle="buttons-radio"]').click(function(event){
		event.preventDefault();
	});
	
	$('select').change(function(event){
		$('#form').submit();
	});
	
	$('button[type="reset"]').click(function(event) {
		$('input[name="search"]').val("");
		console.log($('select[name^="filter_type"]').children(":selected"));
		$('select[name^="filter_type"]').val('');
	});
	
	$('.com-cm-header a').click(function(event){
		event.preventDefault();
		var order = $(this).attr('data-order').replace(" ","_");
		var direction = $(this).attr('data-direction');
				
		$('input[name="filter_order"]').val(order);
		$('input[name="filter_order_dir"]').val(direction);
		
		
		$("#form").submit();
	});
	
	
	// Whenever the form is submitted, reset the limit to 0
	// So the user is return to the first page
	$('#form').submit(function(event){
		$('#form').children('input[name="limitstart"]').val(0);
	});
	
	$('#checkAll').click(function(event){
		var checkboxes = $('input[name="id[]"]');
		console.log(checkboxes);
	});
	
 });