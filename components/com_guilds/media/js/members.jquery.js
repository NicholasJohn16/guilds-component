$(document).ready(function() {
	
	// Prevent clicking buttons from submitting the form
   $('.action').click(function(event) {
	   event.preventDefault();
   });
   
   // Change some of the editable plugin defaults.
   //$.fn.editable.defaults.mode = 'inline';
   $.fn.editable.defaults.emptytext = '';
   $.fn.editable.defaults.emptyclass = '';
   
   $('.editable.rank').editable({
       source:'index.php?option=com_guilds&view=members&task=getRanks&format=ajax',
       name:'forum_rank',
       type:'select',
       title:'Select Rank',
       showbuttons:false,
       url:'index.php?option=com_guilds&view=members&task=update&format=ajax'
   });
   
   $('.editable.handle').editable({
      title:'Enter @Handle',
      name:'handle',
      url:'index.php?option=com_guilds&view=members&task=update&format=ajax'
   });
   
   $('.editable.intro').editable({
       title:'Enter Date of Introduction',
       type:'date',
       name:'appdate',
       placement:'right',
       //showbuttons:false,
       datepicker: {
           todayBtn:'linked',
           todayHighlight:true
       },
       url:'index.php?option=com_guilds&view=members&task=update&format=ajax',
       send:'always'
   });
   
   $('.accordion').editable({
       selector:'div.editable-click',
       url:'index.php?option=com_guilds&view=characters&task=update&format=ajax',
       //clear:'<button class="btn">Clear</button>',
       datepicker: {
           todayBtn:'linked'
       }
   });
   
//   $('.accordion').on('click','div.editable-click',function(){
//       var name = $(this).attr('data-name');
//       var sourceUrl = 'index.php?option=com_guilds&view=characters&format=json&layout=categories&name='+name;
//       console.log(name,"Name");
//       $(this).editable({
//            selector:'div.editable-click',
//            url:'index.php?option=com_guilds&view=characters&task=update&format=ajax',
//            source:function(){
//                $.ajax({
//                    url:sourceUrl,
//                    dataType:'json',
//                    success:function(data){
//                        console.log(data);
//                    }
//                });
//            }
//        }).editable('show');
//   });
   
//   $('.accordion').editable({
//       selector:'div.char_name',
//       name:'character_name',
//       title:'Edit Character Name'
//   });
   
//   $('.accordion').editable({
//       selector:'div.category',
//       type:'select',
//       title:'Select Category',
//       source:'index.php?option=com_guilds&view=characters&format=json&layout=categories&name='+$(this).attr('data-name')
//   });
   
   $('.accordion-body').on('shown',function(){
		   var user = $(this).parent('.accordion-group').attr('data-user');
		   
		   if($('#characters-'+user).html() == "") {
			   getCharactersByUserId(user);
		   }
	});
   
   function refreshCharacters(user) {
	   $('#characters-'+user).addClass('com-guilds-ajax');
	   $('#characters-'+user).html("");
	   getCharactersByUserId(user);
   }
   
   $('button[title="Refresh Characters"]').each(function(){
	   $(this).click(function(event){
		   var user = $(this).parents('.accordion-group').attr('data-user');
		   console.log(user);
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
   
   
   // Activate date picker for date field in Character form
   $('#date').datepicker({
       autoclose:true,
       todayBtn:'linked'
   });
       
   // Hide the character form when close is clicked
   $('#close').click(function(event){
	   event.preventDefault();
	   $('#character-form').modal('hide');
   });
   
   // When the Add Character button is clicked
   $('a[title="Add Character"]').click(function() {
	   // get the user id and user name store them in inputs
	   var user = $(this).attr('data-user');
	   var username = $(this).attr('data-username');
	   
	   // set them on the character form
	   $('#username').val(username).focus();
           $('#user').val(user);
           
	   if(username != undefined) {
		   $('#username').attr('disabled','disabled');
	   }
   });
   
   //When character modal is activated,
   //Have username field grab focs
   $('#character-form').on('shown', function(){
       var username_field = $('#username');
       console.log(username_field);
       username_field.focus();
   });
   
   // Submits new characters from the Character form
   $('#character-form input[type="submit"]').click(function(event){
	   event.preventDefault();
	   var sData = "";
	   var form = $(this).parents('form');
	   var user = form.find('#user').val();
	   var character_name = form.find('input[name="character_name"]').val(); 
	   var checked = form.find('#date').val();
	   
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
	    
	   var user = $(this).parents('.accordion-group').attr('data-user');
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
	  form.find('#username').val("");
	  form.find('#username').removeAttr('disabled');
          form.find('#user').val('');
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