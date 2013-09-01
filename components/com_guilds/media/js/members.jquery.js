function refreshCharacters(user) {
        $('#characters-'+user).addClass('com-guilds-ajax');
        $('#characters-'+user).html("");
        getCharactersByUserId(user);
    };

function getCharactersByUserId(id){
        $.get('index.php',{
            option:'com_guilds',
            view:'characters',
            //task:'ajax',
            format:'raw',
            layout:'ajax',
            //tmpl:'component',
            id:id
        },
        function(data){
            var html = $(data);
		   
            $('#characters-'+id).removeClass('com-guilds-ajax');
            $('#characters-'+id).append(html);
        });
    };
    
//function updateStatusforUserId(id){
//    $.get('index.php',{
//        option:'com_guilds',
//        view:'members',
//        task:'getStatus',
//        id:id
//    },
//    function(data) {
//        var
//    }
//)
//};

$(document).ready(function() {
	
    // Prevent clicking buttons from submitting the form
    $('.action').click(function(event) {
        event.preventDefault();
    });
    
    // Whenever the form is submitted, reset the limit to 0
    // So the user is return to the first page
    $('#members-form').submit(function(event){
            $('#members-form').children('input[name="limitstart"]').val(0);
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
        url:'index.php?option=com_guilds&view=characters&task=ajaxSave&format=raw',
        //clear:'<button class="btn">Clear</button>',
        datepicker: {
            todayBtn:'linked',
            todayHightlight:true
                },
        params:function(params) {
            // modify the params so that it conforms to other category submission
            // ie submit as an array
            params["category["+params.name+"]"] = params.value;
            params.id = params.pk;
            // remove old variables so there's no confusion
            delete params.value;
            delete params.pk;
            delete params.name;
            return params 
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
   
   $('button[title="Refresh Characters"]').each(function(){
        $(this).click(function(event){
            var user = $(this).parents('.accordion-group').attr('data-user');
            refreshCharacters(user);
        });
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
    $('button[title="Delete Character(s)"]').click(function() {
	// Get the user_id so we can search for all checked boxes
        var user = $(this).parents('.accordion-group').attr('data-user');
        // Search for all the checked characters
        var checkboxes = $('#characters-'+user+' input[type="checkbox"]:checked');
	   
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
	   
        $.ajax({
            type:'POST',
            url:'index.php',
            data:'option=com_guilds&view=characters&task=delete&tmpl=component&layout=ajax&ids='+characters,
            success:function() {
                refreshCharacters(user);
            }
        });
	   
    });
   
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