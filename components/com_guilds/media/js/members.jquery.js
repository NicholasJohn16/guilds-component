$(document).ready(function() {
	
	// Prevent clicking buttons from submitting the form
   $('.action').click(function(event) {
	   event.preventDefault();
   });
   
   // Add in editable behavoir
   // It is added 'on' the form and watches for propigation
   // this way, when new editable fields are added via the ajax
   // they are editable as well
   $('#members-form').on('dblclick','div.editable',function(event){
	   insertInput($(this));
   });
   
   $('#members-form').on('change','select.editable',function(event){
	  var update = $(this).attr('data-update');
	  var id = $(this).parents('.com-guilds-row').attr('data-character');
	  var value = $(this).val();
	  console.log(update);
	  console.log(id);
	  console.log(value);
	  $(this).addClass('com-guilds-ajax');
	  postData(update,id,value);
	  $(this).removeClass('com-guilds-ajax');
   });
   
   function insertInput(element) {
	   var value = element.html();
	   var width = element.width();
	   var input = $('<input style="width:'+width+'px;" type="text" value="'+value+'"/>');
	   var field = $(element).attr('data-field');
	   
	   // To prvent another form being added
	   // stop the double click event from being propigated
	   input.dblclick(function(event){event.stopPropagation();});
	   
	   //Add popover instructions
	   input.popover({
			   placement:'bottom',
			   title:'Instructions',
			   content:'<p>Press <code>Enter</code> to submit the new data.</p><p>Press <code>Shift + Enter</code> or <code>Esc</code> to cancel.</p>'
	   });
	   
	   input.keypress(function(event){
		   //If the enter key is hit, prevent the form from being submitted
		   if(event.keyCode == 13 || event.keyCode == 27) {
			   event.preventDefault();  
			   //If Shift+Enter or Escape is pressed, cancel the form
			   //and return previous value
			   if(event.keyCode == 13 && event.shiftKey || event.keyCode == 27) {
				   $(this).parent('.editable').append(value);
			   //If Enter is pressed, update the value
			   } else if(event.keyCode == 13) {
				   var new_value = $(this).val();
				   var result = postData(field,new_value);
				   
				   if(result){
					   //If the updated is successful, update the value
					   $(this).parent('.editable').append(new_value);   
				   } else {
					   //if not, then return to the original value
					   $(this).parent('.editable').append(value);
					   alert("Uh oh! Looks like there was an error submitting that update!");
				   }
			   }
			   //Make sure the popover is hidden
			   $(this).popover('hide');
			   //Make the editable div editable again
			  // editable($(this).parent('.editable'));
			   //remove the input tag
			   $(this).remove();
			   element.css('padding','');
		   };
	   });
		  
	  element.contents().remove();
	  element.css('padding','0px');
	  element.css('width',width);
	  element.append(input);	  
	  element.children('input').focus();
   }
   
   function postData(field,id,value) {
	   var result;
	   $.ajax({
		   type:"POST",
		   url:"index.php?option=com_guilds&view=characters&task=update&tmpl=component",
		   data:'&field='+field+'&value='+value,
		   success:function() {
		   		console.log("Yes!  It was successful!!");
		   		result = true;
	   	   }
	   });
	   return result;
   }
   
   $('.accordion-body').on('shown',function(){
		   var user = $(this).parent('.accordion-group').attr('id');
		   
		   if($('#characters-'+user).html() == "") {
			   getCharactersByUserId(user);
		   }
	});
   
   function refreshCharacters(user) {
	   $('#characters-'+user).addClass('com-guilds-ajax');
	   $('#characters-'+user).html("");
	   getCharactersByUserId(user);
   }
   
   $('button[id^=refresh]').each(function(){
	   $(this).click(function(event){
		   var user = $(this).attr('id').split('-')[1];
		   refreshCharacters(user);
	   });
   });
   
   function getCharactersByUserId(id){
	   
	   $.get('index.php',{
		   option:'com_guilds',
		   view:'characters',
		   //task:'ajax',
		   format:'ajax',
		   layout:'ajax',
		   tmpl:'component',
		   user:id
		   
	   },
	   function(data){
		   var html = $(data);
		   
		   $('#characters-'+id).removeClass('com-guilds-ajax');
		   $('#characters-'+id).append(html);
	   });
   };
   
   $('#close').click(function(event){
	   event.preventDefault();
	   $('#character-form').modal('hide');
   });
   
   // When the Add Character button is clicked
   $('a[title="Add Character"]').click(function() {
	   // get the user id and user name store in data attributes
	   var user = $(this).attr('data-user');
	   var username = $(this).attr('data-username');
	   var user_field = $('#character-form input[name="user"]');
	   // set them on the character form
	   user_field.val(username);
	   user_field.attr('data-user',user);
	   if(username != undefined) {
		   user_field.attr('disabled','disabled');
	   }
   });
   
   // Submits new characters from the Character form
   $('#character-form input[type="submit"]').click(function(event){
	   event.preventDefault();
	   var sData = "";
	   var form = $(this).parents('form');
	   var user = form.find('input[name="user"]').attr('data-user');
	   var character_name = form.find('input[name="character_name"]').val(); 
	   var checked = form.find('input[name="checked"]').val();
	   
	   var eCategories = form.find('select[name^="category"]');
	   
	   // This shouldn't happen, but just in case
	   // If a user isn't set, return an error
	   if(user == "") {
		   alert("User is not selected!");
		   return false;
	   }
	   // If the Character name isn't set
	   if(character_name == "") {
		   // Show an error for this field
		  form.find('input[name="character_name"]').parents(".control-group").addClass("error");
		  // And force its focuse
		  form.find('input[name="character_name"]').focus();
		  // Stops the function from going any farther
		  return false;
	   }
	   // Build the data string to be sent in the request
	   var sData = "user="+user+"&character_name="+character_name+"&checked="+checked;
	   eCategories.each(function(index,value) {
		  sData = sData + "&" + $(value).attr('name')+"="+$(value).val();  
	   });
	   console.log(sData);
	   
	   $.ajax({
		   type:"POST",
		   url:"index.php",
		   data:'option=com_guilds&view=characters&task=add&tmpl=component&'+sData,
		   success:function() {
		   		// onSuccess, hide the form and update the character list
		   		form.modal('hide');
		   		refreshCharacters(user);
	   	   }
	   });
   });
   
   $('button[title="Delete Character(s)"]').click(function() {
	    
	   var user = $(this).attr('id').split('-')[1];
	   var checkboxes = $('#characters-'+user+' .com-guilds-row input[type="checkbox"]:checked');
	   
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
	   characters = characters.join('characters[],');
	   
	   $.ajax({
		   type:"POST",
		   url:"index.php",
		   data:'option=com_guilds&view=characters&task=delete&tmpl=component&layout=ajax&characters='+characters,
		   success:function() {
		   		refreshCharacters(user);
	   	   }
	   });
	   
   });
   
   // When the Character form is hidden
   $('#character-form').on('hidden',function() {
	   // Reset the form back
	  var form = $('#character-form');
	  form.find('input[name="user"]').removeAttr('data-user');
	  form.find('input[name="user"]').val("");
	  form.find('input[name="user"]').removeAttr('disabled');
	  form.find('input[name="character_name"]').val(""); 
	  form.find('input[name="checked"]').val(""); 
	  form.find('select[name^="category"]').val("");
   });
   
//   $('#character-form option').click(function() {
//		var parent = $(this).attr('data-parent');
//		//var children = $(this).attr('data-children');
//		console.log(parent);
//		//console.log(children);
//		selectParents(parent);
//		
//	});
//	
//	function selectParents(parent_id) {
//		var options = $(this).parent('.guilds-row').children('option').each(function(index,element){
//			if($(element).val() == parent_id){
//				$(element).parent('select').val(parent_id);
//				var new_parent = $(element).attr('data-parent');
//				if(new_parent != "0") {
//					selectParents(new_parent);
//				}
//			}
//		});
//	}
   
   //When a "Check All" Input is clicked, all inputs in the list are checked.
   $('form').on('click','.checkall',function(event) {
	   var checkboxes = $(this).parents('div[id^="characters"]').find('input[type="checkbox"]');
	   
	   if($(this).attr('checked')== 'checked'){
		   var bool = true;
   	   } else {
		   var bool = false;
	   }
	   for(var i = 1;i<checkboxes.length;i++){
		   checkboxes[i].checked = bool;
	   };
   });
   
   
   // Simple ordering function
   $('.subnav a').click(function(event){
		event.preventDefault();
		var order = $(this).attr('data-order').replace(" ","_");
		var direction = $(this).attr('data-direction');
				
		$('input[name="order"]').val(order);
		$('input[name="direction"]').val(direction);
		
		
		$("#members-form").submit();
	});
   
   
   // Resets the form controls when the Reset button is clicked.
   $('button[type="reset"]').click(function(event) {
		$('input[name="search"]').val("");
		$('select[name^="filter_type"]').val('');
		$('#members-form').submit();
	});
   
 });