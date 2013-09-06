$(document).ready(function() {
    
    $('#character-form-modal').modal({
       show:false 
    });
    
    $('#add-character').click(function(){
       $('#character-form-modal').modal('show'); 
    });

    // Hide the character form when close is clicked
    $('#character-form-close').click(function(event){
        event.preventDefault();
        $('#character-form-modal').modal('hide');
    });

    // When the Add Character button is clicked
    $('.add-character').click(function(event) {
        event.preventDefault();
        // get the user id and user name store them in inputs
        var user_id = $(this).attr('data-user');
        var username = $(this).attr('data-username');
	   
        // set them on the character form
        $('#character-form-user_id').select2('data',{id:user_id,text:username});
        $('#character-form-user_id').select2('readonly',true);
        $('#character-form-modal').modal('show');
    });
    
    $('#character-form-user_id').select2({
        minimumInputLength:1,
        ajax: {
            url:'index.php?option=com_guilds&view=members&format=raw&task=handles',
            datatype:'json',
            data:function(term,page) {
                return {
                    name:term
                };
            },
            results:function(data,page) {
                return { results:data }
            }
        }
    });
    
    $('#character-form-character').select2({
        ajax:{
            url:'index.php?option=com_guilds&view=characters&format=raw&task=characters',
            datatype:'json',
            data:function(term,page) {
                return {
                    user_id:$('#character-form-user_id').select2('val')
                }
            },
            results:function(data,page) {
                return {results:data }
            }
        },
        createSearchChoice:function(term) {
            var newTerm = {
                id:term,
                text:term
            };
            return newTerm;
        }
    });
    
    // Checks if the character is new or old
    // updates the name property as necessary
    $('#character-form-character').on('change',function(event){
        if(Number(event.val)) {
            $('#character-form-character').attr('name','id');
        } else {
            $('#character-form-character').attr('name','name');
        }
    });
    
    //When character modal is activated,
    //Have username field grab focs
    //Removed because it may be interfering with data serliazation
//    $('#character-form-modal').on('shown', function(){
//        $('#character-form-user_id').select2('open');
//    });
    
    // Activate date picker for date field in Character form
    $('#character-form-checked').datepicker({
        autoclose:true,
        todayBtn:'linked',
        format:'yyyy-mm-dd'
    });
    
    // Submits new characters from the Character form
    $('#character-form-submit').click(function(event){
        event.preventDefault();
        var user = $('#character-form-user_id').select2('val');
        var name = $('#character-form-character').select2('val');
        var form = $('#character-form');
        
        // If a user isn't set, return an error
        if(user === "") {
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
        // Reset the form back
        $('#character-form-user_id').select2('val','').select2('readonly',false);
        $('#character-form-character').select2('val','').attr('name','name');
        $('#character-form-handle').val('').hide();
        $('#character-form-handle-link').show();
        $('#character-form-handle-cancel').hide();
        $('#character-form-checked').val('');
        $('select[id^="character-form-category"]').val('');
    });
    
    $('#character-form-user').bind('typeahead:selected typeahead:autocompleted', function(event, item){
        $('#character-form-user_id').val(item.id);
    });
    
    $('#character-form-handle-link').bind('click keypress',function(event){
        event.preventDefault();
        // make sure its the spacebar that triggered the event
        if(event.charCode == 32 || event.type == 'click') {
            $('#character-form-handle-link').hide();
            $('#character-form-handle').show().focus();
            $('#character-form-handle-cancel').show();
        }
    });
    
    $('#character-form-handle-cancel').click(function(){
        event.preventDefault();
       $('#character-form-handle-cancel').hide();
       $('#character-form-handle').hide();
       $('#character-form-handle-link').show().focus();
    });
    
});