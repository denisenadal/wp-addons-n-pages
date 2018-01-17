//  ============  Master Calendar Utility Functions ============
//function to clean up Master Cal Event Content, format as JSON,
//and group events with multiple listings
function formatEvents(response){
	var eventsXML = response.getElementsByTagName("item");
	var formattedEvents = [];
	for(var i=0;i<eventsXML.length;i++){
		var event = eventsXML[i];
		var eventObject = RSStoJSON(event);
		formattedEvents.push(eventObject);
	}
	for(var j=1;j<formattedEvents.length;j++){
		//if event is canceled, remove it
		if(formattedEvents[j].title.includes("canceled")){
			formattedEvents.splice(j,1);
			j--;
		}
	}
	return formattedEvents;
}


//function to format Master Cal XML as JSON
function RSStoJSON(event){
	var eventObject ={};

	eventObject.title = event.getElementsByTagName("title")[0].textContent.replace(/\d+:\d{2}\s\w{2}\sto\s\d+:\d{2}\s\w{2}/g,'').replace(/\d+\/\d+\/\d{4}/g,'').replace(/"/g, '&quot;').trim();
	if(event.getElementsByTagName("enclosure").length){
		eventObject.imageurl = event.getElementsByTagName("enclosure")[0].getAttribute('url').replace('?','?ShowFullImage=1&');
	}
	eventObject.time = event.getElementsByTagName("title")[0].textContent.match(/\d+:\d{2}\s\w{2}\sto\s\d+:\d{2}\s\w{2}/g)[0];
	eventObject.date = event.getElementsByTagName("title")[0].textContent.match(/\d+\/\d+\/\d{4}/g)[0];
	eventObject.description = event.getElementsByTagName("description")[0].textContent.replace(/\d+:\d{2}\s\w{2}\sto\s\d+:\d{2}\s\w{2}\s-\s/g,"").replace(/"/g, '&quot;').trim();
	eventObject.category = event.getElementsByTagName("category")[0].textContent;
	eventObject.location = eventObject.description.slice(0, eventObject.description.indexOf(":") );
	eventObject.description = eventObject.description.slice(eventObject.description.indexOf(":")+1 );
	eventObject.link = event.getElementsByTagName("link")[0].textContent;
	return eventObject;
}

//sort Json-formatted events into various calendar lists based on their category
//this function is neccessary when requesting more than one calendar, the RSS feeds load very slowly, so it's faster for the client to get one list (the full calendar) and sort the events rather than request X many calendars and parse them individually.
function sortEvents(eventList){
	// catLists & eventLists should have same keys to append events to the correct list
	var catLists = {
		"artsCalendar":["Concert","Performance","Recital","Production","Art","Gallery"],
		"sportsCalendar":["Sport","Athletic"],
		"studentCalendar":["Student","Banquet","Reception","Graduation","Social","Club","Lecture"],
		"staffCalendar":["Conference","Banquet","Reception","Graduation","Lecture"],
		"publicCalendar":["Sport","Athletic","Banquet","Reception","Graduation","Student","Social","Concert","Performance","Recital","Production","Lecture","Orientation"],
	};
	var eventLists = {
		"artsCalendar":[],
		"sportsCalendar":[],
		"studentCalendar":[],
		"staffCalendar":[],
		"publicCalendar":[],
		"fullCalendar":[]
	};
	//for each event
	for(var i=0;i<eventList.length;i++){
		//check each list
		for(catList in catLists){
			//check to see if event's category is in the list of categories
			//if match, add to eventList that matches categoryList
			if( catLists.hasOwnProperty(catList) && catLists[catList].filter(function(cat){
					if(eventList[i].category.includes(cat) )
						return eventList[i];
				}).length )
				{
				eventLists[catList].push(eventList[i]);
			}
		}
		eventLists.fullCalendar.push(eventList[i]);
	}
	return eventLists;
}


//  ============  Master Calendar Display Functions ============


//this creates a simple list of events for use in sidebar widgets, check documentation for more details
function displayEventList(events,containerID){
	var $list =$("#"+containerID);
	if(events.length==0){
		$list.append('<li class="ms-list-item"><h4 class="title is-5 is-strong">No events found</h4></li>');
	}
	for(var i=0;i<events.length;i++){
		var event = events[i];
		$list.append('<li class="mc=-list-item"><a href="'+ event.link +'"><h4 class="title is-5 is-strong">'+event.title+'</h4><h5 class="subtitle is-6">'+event.date +' - '+event.time+'</h5><span>'+event.description+'</span></a></li>');
	}
}

//this creates a list of events with images for use in full-width sliders.
//used by the row-slider & the single slide slider
function displayCalendarCards(events,containerID){
	var $list =$("#"+containerID);
	var count = events.length;
	$list.addClass('has-'+count+'-items');
	for(var i=0;i<events.length;i++){
		var event = events[i];
		if( !event.title.includes('canceled')  ){
			event.date = new Date(event.date);
			var content = '<li id="mc-event-'+(i+1)+'" class="mc-event-item"><a href="'+ event.link +'"><div class="img-frame">';
			content += event.imageurl ? '<img src="'+event.imageurl+'" alt="'+event.title+'">' :'';
			content += '<div class="date">'+event.date.toLocaleDateString('en-US',{"month":"short","day":"numeric"})+'</div></div>';//end img-frame
			content += '<h4 class="event-title">'+event.title +'</h4><h5 class="event-time">  '+event.time+'</h5></a></li>';
			$list.append(content);
		}
	}
	if (isAnimatable()){
		$list.addClass("animated-slider");
	}
}




//  ============ Slider specific Functions ============
//function to control slider navigation
//this function must receive "init" on page load or window resize in order to check if the slider needs to be animated. (will not auto-slide when all slides fit in the window, or on mobile.)
function eventSliderNav(direction ){
	var direction = typeof timer !== 'undefined' ? direction : null;
	//clear any existing timers
	if(typeof timer !== 'undefined'){
		clearTimeout(timer);
	}
	if(isAnimatable() ){
		//if we are not starting up,
		//we are either clicking a nav button or auto-sliding
		if(direction != "init"){
			var events = $(".mc-event-item");
			var eventsList = events.first().parent();
			var itemWidth = $(".mc-event-item").first().width();
			//don't do slide if currently sliding
			if(eventsList.is(':animated')){
				return;
			}
			if(direction && direction == "prev"){
				var last = events.last();
				last.clone().prependTo(eventsList);
				eventsList.css({marginLeft:-itemWidth});
				eventsList.animate({marginLeft:0},500,function(){
					last.remove();

				});
			}
			//else slide forward
			else {
				var first = events.first();
				first.clone().appendTo(eventsList);
				eventsList.animate({marginLeft:-itemWidth},500,function(){
					first.remove();
					eventsList.css({marginLeft:0});
				});
			}
		}//end if not init
		//if it's animatable, set the timer
		timer = window.setTimeout(function(){
			eventSliderNav();
		},5000);
	}//end if animatable
}

function isAnimatable(){
	var animate = false;
	var events = $(".mc-event-item");
	var winWidth = $(window).width();
	switch (true){
		case winWidth < 540:
			if( events.length > 1) {
				animate = true;
			}
			break;
		case winWidth >= 540 && winWidth < 768:
			if( events.length > 2) {
				animate = true;
			}
			break;
		case winWidth >= 768 && winWidth < 1200:
			if(events.length > 3) {
				animate = true;
			}
			break;
		case winWidth >= 1200 && winWidth < 1600:
			if(events.length > 4) {
				animate = true;
			}
			break;
		case winWidth > 1600:
			if(events.length > 5) {
				animate = true;
			}
			break;
	}
	if(animate){
		$(".mc-slider-wrap").addClass('animated-slider');
	}
	else{
		$(".mc-slider-wrap").removeClass('animated-slider');
	}
	return animate;
}

//  ============  Event Listeners Functions ============

jQuery(document).ready(function($){
	//get all events
	$.ajax({
		method: "GET",
		url: "https://events.dixie.edu/MasterCalendar/RSSFeeds.aspx?data=OiNeXA6LJItp%2bLkkMsbi47cRFbkQgnvYK18w%2bQ33Jqg%3d",
		dataType : 'xml'
	})
	.done(function( response ) {
		var formattedEvents = formatEvents(response);
		//console.log(formattedEvents);
		if($('.mc-slider').length){
			var id = $(".mc-slider").attr("id");
			//populate the slider with cards, check if it should be animated
			displayCalendarCards(formattedEvents, id);
			isAnimatable();
			eventSliderNav("init");
			//set up the event listeners for the slider nav
			$("i.mc-slider-nav").click(function(){
				if($(this).hasClass("mc-featured-left") ){
					eventSliderNav("prev");
				}
				else if($(this).hasClass("mc-featured-right") ){
					eventSliderNav("next");
				}
			});
		}//end if row-slider
	});	//end ajax request
});//end events onload wrapper


 function restartSlider(){
	isAnimatable();
	eventSliderNav("init");
}
//slider must be reinitialized on window resize because the window width determines whether or not there's enough slides visible to slide through
$(document).ready(function(){
	$( window ).resize( debouncer( function ( e ) {
	    restartSlider();
	} ) );
});
