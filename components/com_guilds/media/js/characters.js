$(document).ready(function() {
	
    // Change some of the editable plugin defaults.
    //$.fn.editable.defaults.mode = 'inline';
    $.fn.editable.defaults.emptytext = '';
    $.fn.editable.defaults.emptyclass = '';
        
    $('.editable').editable({
        url:'index.php?option=com_guilds&view=characters&task=ajaxSave&format=raw',
        params:function(params) {
            // Store the values so they won't be deleted
            var field = params.name;
            var value = params.value;
            var id = params.pk;
            
            // Remove the params
            delete params.name;
            delete params.pk;
            delete params.value;
            
            // Set the new params
            params[field] = value;
            params.id = id;
            
            return params 
        }
    });
        
    $('#filters select').change(function(event){
        $('#roster-form').submit();
    });
	
    $('button[type="reset"]').click(function(event) {
        $('input[name="search"]').val("");
        $('select[name^="filter_type"]').val('');
        $('#roster-form').submit();
    });
	
    $('.header a').click(function(event){
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
	        
    $('body').on('click','.publish',function(event) {
        event.preventDefault();
        var el = $(this);
        var task = el.attr('data-task');
        var id = el.attr('data-id');
        var toggle = {
            'publish':'unpublish',
            'unpublish':'publish'
        };
        
        $.ajax({
            url:'index.php?option=com_guilds&view=characters&task='+task+'&id='+id,
            success:function() {
                el.toggleClass('btn-inverse');
                el.attr('data-task',toggle[task]);
                //el.attr('title',toggle[task].charAt(0).toUpperCase() + toggle[task].slice(1) + ' Character');
                el.attr('title',task.charAt(0).toUpperCase() + toggle[task].slice(1));
                el.children('i').toggleClass('icon-white');
                el.children('i').toggleClass('icon-eye-close icon-eye-open');
            },
            error:function() {
                alert("The Character could not be updated.\nTry refreshing the page.\nIf there error persists, contact an admin.");
            }
        });
    });
    
    /*
    * Deletes checked character(s) when delete character button is clicked
    */
    $('#delete-character').click(function(event) {
        event.preventDefault();
        // Search for all the checked characters
        var checkboxes = $('#roster-form input[type="checkbox"]:checked');
	   
        // Make sure there are some characters selected
        if(checkboxes.length == 0 ) {
            alert("Oops, you don't have any characters selected.");
            return false;
        } else {
            // Make sure they acutally want to delete the characters
            var response = confirm("Are you sure?");
            // if Cancelled, stop the function from proceeding
            if(response == false) {
                return false;
            }
        }
	   
        var characters = new Array();
	   
        checkboxes.each(function(index,element) {
            characters.push($(element).val());
        });
        characters = characters.join(',');
        
        // Remove checks so they aren't still there when the page refreshes
        checkboxes.removeAttr('checked');
	   
        $.ajax({
            type:'POST',
            url:'index.php?option=com_guilds&view=characters&task=delete&format=raw',
            data:'ids='+characters,
            success:function() {
                location.reload();
            }
        });
	   
    });
	
});