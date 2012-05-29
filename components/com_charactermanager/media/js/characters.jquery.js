$(document).ready(function() {
	
//	$('option').click(function() {
//		var parent = $(this).attr('data-parent');
//		var children = $(this).attr('data-children');
//		console.log(parent);
//		console.log(children);
//		selectParents(parent);
//	});
	
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
	
 });