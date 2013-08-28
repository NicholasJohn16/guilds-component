$(document).ready(function() {
    
    // Hide the character form when close is clicked
    $('#character-form-close').click(function(event){
        event.preventDefault();
        $('#character-form-modal').modal('hide');
    });
    
    // When the Add Character button is clicked
    $('a[title="Add Character"]').click(function(event) {
        event.preventDefault();
        // get the user id and user name store them in inputs
        var user = $(this).attr('data-user');
        var username = $(this).attr('data-username');
	   
        // set them on the character form
        $('#username').val(username).focus();
        $('#user_id').val(user);
        
        if(username != undefined) {
            $('#username').attr('disabled','disabled');
        }
        
        $('#character-form-modal').modal('show');
    });
    
    //When character modal is activated,
    //Have username field grab focs
    $('#character-form-modal').on('shown', function(){
        var username_field = $('#username');
        username_field.focus();
    });
    
    // Activate date picker for date field in Character form
    $('#character-form-checked').datepicker({
        autoclose:true,
        todayBtn:'linked',
        format:'yyyy-mm-dd'
    });
    
    // Submits new characters from the Character form
    $('#character-form-submit').click(function(event){
        event.preventDefault();
        var form = $(this).parents('form');
        var user = form.find('#user_id').val();
        var name = form.find('input[name="name"]').val(); 
        
        // This shouldn't happen, but just in case
        // If a user isn't set, return an error
        if(user == "") {
            alert("User is not selected!");
            return false;
        }
        // If the Character name isn't set
        if(name == "") {
            // Show an error for this field
            form.find('input[name="name"]').parents(".control-group").addClass("error");
            // And force its focuse
            form.find('input[name="name"]').focus();
            // Stops the function from going any farther
            return false;
        }
        // Build the data string to be sent in the request
        var data = form.serialize();
	   
        $.ajax({
            type:"POST",
            url:"index.php?option=com_guilds&view=characters&task=ajaxSave&tmpl=component",
            data:data,
            success:function() {
                // onSuccess, hide the form and update the character list
                $('#character-form-modal').modal('hide');
                refreshCharacters(user);
            }
        });
    });
    
    // When the Character form is hidden
    $('#character-form-modal').on('hidden',function() {
        //reset the form
        $('#character-form-user').val('');
        $('#character-form-user').removeAttr('disabled');
        $('#character-form-user_id').val('');
        $('#character-form-handle-cancel').hide();
        $('#character-form-handle').hide();
        $('#character-form-handle-link').show();
        $('#character-form-name').val(''); 
        $('#character-form-checked').val('');
        $('select[id^="character-form-category"]').val('');
    });
    
    $('#character-form-user').typeahead([
        {
            name:'user-for-character',
            remote:'index.php?option=com_guilds&view=members&format=raw&task=handles&name=%QUERY',
            template:'<p>{{value}}</p>',
            engine:Hogan
        }
    ]);
    
    $('#character-form-user').bind('typeahead:selected typeahead:autocompleted', function(event, item){
        $('#character-form-user_id').val(item.id);
    });
    
    $('#character-form-handle-link').bind('click keypress',function(event){
        // make sure its the spacebar that triggered the event
        if(event.charCode == 32 || event.type == 'click') {
            $('#character-form-handle-link').hide();
            $('#character-form-handle').show().focus();
            $('#character-form-handle-cancel').show();
        }
    });
    
    $('#character-form-handle-cancel').click(function(){
       $('#character-form-handle-cancel').hide();
       $('#character-form-handle').hide();
       $('#character-form-handle-link').show().focus();
    });
});