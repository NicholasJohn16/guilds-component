window.addEvent('domready',function(){

	$$('button[id^=characters]').each(function(elem){
		var id = getID(elem);
		var list = $('characterlist-'+id);
		var container = list.getParent();
		//var wrap = list.getParents('.character-list-wrapper');
		var myFx = new Fx.Slide(container,{
			//wrapper:wrap,
			onComplete: function(){
				if(container.getParent().getStyle('height') != "0px"){
					// Check if the slider is open
					container.getParent().setStyle('height', 'auto');
				}}
		}).hide();
		elem.addEvent('click',function(event){
//			var arrow = $('arrow-'+id);
//			// Change so that it uses myFx.open for boolean
//			if(arrow.hasClass('mmRotate')){
//				arrow.removeClass('mmRotate');
//			} else {
//				arrow.addClass('mmRotate');
//			}
			event.preventDefault();
			if($('characterlist-'+id).innerHTML == ""){
				getCharactersforUserId(id);
			}
			myFx.toggle();
		});
	});
	
	$$('img[id^=deletechars]').addEvent('click',function(){
		var id = getID(this);
		var char_ids = $('characterlist-'+id).getElements('input:checked');
		var ids = "";
		for(var i=0;i<char_ids.length;i++){
			if(ids == ""){
				ids = ids + getID(char_ids[i]);
			} else {
				ids = ids + ',' + getID(char_ids[i]);
			}
		}
		if(char_ids != "") {
			alert("IDs: "+ids);
		}
	});
	
	// Must come after creation of Slide instances
//	$$('img[id^=characters]').addEvent('click',function(){
//		var id = getID(this);
//		var container = $('characterlist-'+id).getParent();
//		// Get the Fx.Slide instance for this element
//		var myFx = container.get('slide');
//		var arrow = $('arrow-'+id);
//		if(arrow.hasClass('mmRotate')){
//			arrow.removeClass('mmRotate');
//		} else {
//			arrow.addClass('mmRotate');
//		}
//		if($('characterlist-'+id).innerHTML == ""){
//			getCharactersforUserId(id);
//		}
//		myFx.toggle();
//		
//	});
	
	$$('img[id^=refresh]').addEvent('click',function(){
		var id = getID(this);
		var charList = $('characterlist-'+id);
		charList.innerHTML = "";
		charList.addClass('com-mm-ajax');
		getCharactersforUserId(id);
	});
	
	//var arrows = $$('img.com-mm-left');
//	arrows.addEvent('click',function() {
//		var id = this.id.split('-')[1];
//		
//		if(this.hasClass('mmRotate')) {
//			this.removeClass('mmRotate');
//			//$('characterlist-'+id).setStyle('display','none');
//			$('characterlist-'+id).getParent().setStyle('display','none');
//		} else {
//			if($('characterlist-'+id).innerHTML == ""){
//				getCharactersforUserId(id);
//			}
//			this.addClass('mmRotate');
//			$('characterlist-'+id).getParent().setStyle('display','block');
//		}
//	});
	
	$$('.editable').addEvent('dblclick',function(){
		insertForm(this);
	});
	
	function getCharactersforUserId(id){
		var url = 'index.php?option=com_charactermanager'
			+ '&view=characters'
			+ '&format=json'
			+ '&layout=json'
			+ '&user='+id;
		var request = new Request.JSON({
			method:'get',
			url:url,
			onSuccess:function(responseJSON,responseText){
						var html = "";
						if(responseText == "[]") {
							html = '<div style="height:75px;text-align:center;">This user doesn\'t have any characters yet.</div>';
						} else { responseJSON.each(function(item,index){
							html = html + '<div class="com-mm-row">';
								html = html + '<input id="cb-'+ item.id + '" type="checkbox" value="' + item.id + '"/>';
								html = html + '<div class="editable" style="width:100px">' + item.name + '</div>';
								//html = html + '<div class="com-mm-left">' + item.user_id + '</div>';
								html = html + '<div class="drop-down" style="width:135px;">' + item.game_name + '</div>';
								html = html + '<div class="drop-down" style="width:150px;">' + item.allegiance_name + '</div>';
								html = html + '<div class="drop-down" style="width:50px;">' + item.class_name + '</div>';
								html = html + '<div class="drop-down" style="width:150px;">' + item.guild_name + '</div>';
								html = html + '<div class="editable date" style="width:65px;">' + item.rosterchecked + '</div>';
								if(item.published == 1){
									html = html + '<img class="com-mm-icon" style="width:16px;" src="components/com_membermanager/media/images/tick.png" alt="Published" title="Published">';
								} else {
									html = html + '<img class="com-mm-icon" style="width:16px;" src="components/com_membermanager/media/images/cross.png" alt="Unpublished" title="Unpublished">';
								}
								//html = html + '<div class="com-mm-left">' + item.published + '</div>';
								html = html + '<div>' + item.unpublisheddate + '</div>';
								html = html + '<img class="com-mm-icon" style="width:16px;" src="components/com_membermanager/media/images/bin_closed.png"/>';
							html = html + '</div>';
							html = html + '<div style="clear:both"></div>';
							});
						}
						$('characterlist-'+id).removeClass('com-mm-ajax');
						$('characterlist-'+id).innerHTML = html;
					//	$$('.editable').addEvent('dblclick',function() {
					//		insertForm(this);
					//	});
					},
			onException:function(headerName,value){
						//updateField(field,user,previous);
						},
			onFailure:function(xhr){
						//updateField(field,user,previous);
						}
			});
		request.send();
	}
	
//	$$('.drop-down').getElement('ul').each(function(elem){
//		var myFx = new Fx.Slide(elem).hide();
//			elem.getParents('.drop-down').addEvents({
//			'click':function(){
//				myFx.cancel();
//				myFx.slideIn();
//			},
//			'mouseleave':function(){
//				myFx.cancel();
//				myFx.slideOut();
//			}
//		});
//	});
	
	$$('.drop-down').addEvents({
			'mouseover':function(){
			this.set('tag','select');
		},
			'mouseleave':function(){
			this.set('tag','div');
		}
	});
	
	document.getElements("#mydiv span").each(function(el) {
	     new Element("div", {
	        html: el.get("html")
	    }).replaces(el);
	});
		
		//Make the first letter upcase and pluralize before getting
		//var options = get(ucfirst(field)+"s",this);
	
	function get(options,element) {
		var url = 'index.php?option=com_membermanager'
			+ '&view=membermanager'
			+ '&format=ajax'
			//+ '&tmpl=component'
			+ '&task=get'+ options;
		var request = new Request.JSON({
			method:'get',
			url:url,
			onSuccess:function(responseJSON,responseText){
						var dl = new Element('dl');
						responseJSON.each(function(item,index){
							var dd = new Element('dd',{
								'class':"rank-"+item.rank_id,
								'style':{
									'margin':'0'
								}
							});
							dd.innerHTML = item.rank_title;
							//text = text + '<dd class="rank-'+ item.rank_id + '>' + item.rank_title + '</dd>';
							dl.grab(dd);
						});
						element.grab(dl);
						//updateField(field,user,next);
						},
			onException:function(headerName,value){
						//updateField(field,user,previous);
						},
			onFailure:function(xhr){
						//updateField(field,user,previous);
						}
			});
		request.send();
	}
	
	function insertForm(element) {
		var previous = element.innerHTML;
		var field = element.id.split('-')[0];
		var user = element.id.split('-')[1];
		var isDate = element.hasClass('date');
		var offset = (isDate) ? 25 : 10; 
		element.innerHTML = "";
		
		var form = new Element('form',{
			'class':'form'
		});
		var input = new Element('input', {
			'type':'text',
			'value':previous,
			'id':field+'-'+user+'-input',
			'styles':{
				'width':element.style.width.toInt()-offset,
				'height':'14px',
				'float':'left'
				},
			'events':{
				'keydown':function(event){
					if(event.key == 'enter' && !event.shift) {
						event.preventDefault();
						var next = this.value;
						setValue(field,previous,next,user);
						removeForm(this);
					}
					if(event.key == 'enter' && event.shift || event.key == 'esc'){
						event.preventDefault();
						removeForm(this);
						updateField(field,user,previous);
					}
				},
				'dblclick':function(event){
					event.preventDefault();
					var next = this.value;
					setValue(field,previous,next,user);
					removeForm(this);
				}
			}
			});
		var calendar = new Element('img', {
			'src':'components/com_membermanager/media/images/calendar.png',
			'alt':'Calendar',
			'title':'Calendar',
			'id':field+'-'+user+'-calendar',
			'styles': {
				'float':'left',
				'padding':'1px',
				'vertical-align':'middle',
				'cursor':'pointer'
				},
			'events':{
					'click':function() {
					
					}
				}
			});
		// Wrap the save button in a link so the mouse becomes a hand
		if(isDate) { 
			form.adopt([input,calendar])
		} else {
			form.adopt([input]);
		}
		// The 'editable' div grabs the container
		element.grab(form);
		//create calendar
		if(isDate){
			Calendar.setup({
				inputField:field+'-'+user+'-input',
				ifFormat:'%Y-%m-%d',
				button:field+'-'+user+'-calendar',
				align:'Br',
				singleClick:true
			});
		}
		// Remove event so it can't be triggered during editing
		element.removeEvents('dblclick');
	};
	
	function updateField(field,user,value) {
		$(field+'-'+user).removeClass('com-mm-ajax-small');
		$(field+'-'+user).innerHTML = value;
	}
	
	function setValue(field,previous,next,user) {
		// This will load view.ajax.php from the membermanager view without the site template
		$(field+'-'+user).addClass('com-mm-ajax-small');
		var url = 'index.php?option=com_membermanager'
			+ '&view=members'
			+ '&format=ajax'
			+ '&tmpl=component'
			+ '&task=update'+ field
			+ '&value='+ encodeURIComponent(next)
			+ '&userid=' + user;
		var request = new Request({
			method:'get',
			url:url,
			onSuccess:function(responseText,responseXML){
						updateField(field,user,next);
						},
			onException:function(headerName,value){
						updateField(field,user,previous);
						},
			onFailure:function(xhr){
						updateField(field,user,previous);
						}
			});
		request.send();
	}
	
	function removeForm(element) {
		// Add the event back so it can be edited again in the future
		element.getParents('.editable').addEvent('dblclick',function() {
			insertForm(this);
		});
		// Destroy the form
		element.getParents('.form').destroy();
	}
	
	function ucfirst(string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	}
	
	function getID(elem){
		return elem.id.split('-')[1];
	}
});