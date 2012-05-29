$(document).ready(function() {
   $('.action').click(function(event) {
	   event.preventDefault();
   });
   
//   $('.editable').each(function(index){ 
//	   editable($(this));
//   });
   
   $('form').on('dblclick','.editable',function(event){
	  insertInput($(this)); 
   });
   
   function insertInput(element) {
	   var value = element.html();
	   var width = element.width();
	   var input = $('<input style="width:'+width+'px;margin:-3px;" type="text" value="'+value+'"/>');
	   
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
				   
				   if(postData(new_value)){
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
		   };
	   });
		  
	  element.unbind('dblclick');
	  element.contents().remove();
	  element.append(input);	  
	  element.children('input').focus();
   }
   
   $('.accordion-body').each(function(){
	   $(this).on('shown',function(){
		   var user_id = $(this).parent('.accordion-group').attr('id');
		   
		   if($('#characters-'+user_id).html() == "") {
			   getCharactersByUserId(user_id);
		   }
	   });
   });
   
   $('button[id^=refresh]').each(function(){
	   $(this).click(function(event){
		   var user_id = $(this).attr('id').split('-')[1];
		   
		   $('#characters-'+user_id).addClass('com-mm-ajax');
		   $('#characters-'+user_id).html("");
		   getCharactersByUserId(user_id);
	   });
   });
   
   function getCharactersByUserId(id){
	   
	   $.get('index.php',{
		   option:'com_charactermanager',
		   view:'characters',
		   //task:'ajax',
		   format:'ajax',
		   layout:'ajax',
		   tmpl:'component',
		   user:id
		   
	   },
	   function(data){
		   var html = $(data);
		   var list = $(data).filter('.com-mm-container');
		   //var pagination = $(data).filter('#pagination');
		   
		   $('#characters-'+id).removeClass('com-mm-ajax');
		   $('#characters-'+id).append(list);
		   $(document).trigger('ready');
		   //$('#toolbar-'+id).append(pagination);
	   });
   };
   
   function postData(new_value) {
	   return false;  
   }
   
   $('#close').click(function(event){
	   $('#character-form').modal('hide');
   });
   
   $('a[title="Add Character"]').click(function() {
	   var user = $(this).attr('data-user');
	   var username = $(this).attr('data-username');
	   
	   $('#user').val(user);
	   $('#username').val(username);
	   
	   
   });
   
//   $('option').click(function() {
//		var parent = $(this).attr('data-parent');
//		//var children = $(this).attr('data-children');
//		console.log(parent);
//		//console.log(children);
//		selectParents(parent);
//		
//	});
//	
//	function selectParents(parent_id) {
//		var options = $(this).parent('.com-mm-row').children('option').each(function(index,element){
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
   
   //Change select list style when clicked
//   $('.navbar select').bind({
//	   click:function() {
//	   	$(this).css('background-color','#FFFFFF');
//	   	$(this).css('color','#000000');
//   	},
//   	   mouseleave:function() {
//   		$(this).css('background-color','#626262');
//	   	$(this).css('color','#CCCCCC');
//   	}	   
//   });
   
 });