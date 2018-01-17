jQuery(document).ready(function($){
	var defaultTitle = $('h2.SemesterTitle').text();
	//when a user selects a semester
	$('.semester-option').click(function(){
		var semester = $(this).attr('data-semester');
		var year = $(this).attr('data-year');
		if(semester == 'year'){
			$('.ac-event').addClass('active-semester');
			$(this).addClass('active-semester');
			$('.semester-option').not('.year').removeClass('active-semester');
			$('.calendar-month').show();
			$('h2.SemesterTitle').text(defaultTitle);
		}
		else{
			$('.'+semester+'.'+year).addClass('active-semester');
			$('.semester-option, .ac-event').not('.'+semester+'.'+year).removeClass('active-semester');
			$('.calendar-month:not(:has(.ac-event.'+semester+'.'+year+'))').hide();
			$('.calendar-month:has(.ac-event.'+semester+'.'+year+')').show();
			var title = $(this).text();
			$('h2.SemesterTitle').text(title);
		}

	});

	$('#printButton').click(function(){
		$('.calendar-month').each(function(){
			if($(this).find('.ac-event:visible').length == 0) {
				$(this).hide();
			}
		});
		window.print();
	});


	//when a user selects a term
	$('.term-option').click(function(){
		var term = $(this).attr('data-term');
		$('.'+term).addClass('active-term');
		$('.ac-event, .term-option').not("."+term).removeClass('active-term');
	});

	//when a user selects a category
	$('.category-option').click(function(){
		var category = $(this).attr('data-filter');
		if(category == "all"){
			$('.category-option').addClass('active-category');
			$('.ac-event').addClass('active-category');
			$('.subcategory').removeClass('active-category');
			$('.calendar-month:has(.ac-event.active-category)').show();
			$('.calendar-month:not(:has(.ac-event.active-category))').hide();
		}
		else{
			$('.category-option').not("."+category).removeClass('active-category');
			$('.'+category).not('.subcategory li').addClass('active-category');
			$('.subcategory, .ac-event').not('.'+category).removeClass('active-category');
			$('.calendar-month:has(.ac-event.'+category+')').show();
			$('.calendar-month:not(:has(.ac-event.'+category+'))').hide();
		}
		$('.subcategory li').removeClass('active-category');
	});

	//when a user selects a specific date/event
	$('.subcategory li').click(function(){
			var event = $(this).attr('rel');
			$('.calendar-month').show();
			$('.subcategory li').removeClass('active-category');
			$(this).addClass('active-category');
			$('.ac-event:not(.'+event+')').removeClass('active-category');
			$('.ac-event.'+event).addClass('active-category');
			$('.calendar-month').each(function(){
				if($(this).find('.ac-event.'+event+':visible').length > 0 ){
					$(this).show();
				}
				else{$(this).hide();}
			});
		});

	$('#ac-list-view').click(function(){
		$('#ac-month-view').removeClass('active-view');
		$('#ac-list-view').addClass('active-view');
		$('#academic-calendar').addClass('list');
		$('.day:not(:has(.ac-event.active-semester.active-term))').hide();
	});

	$('#ac-month-view').click(function(){
		$('#ac-list-view').removeClass('active-view');
		$('#ac-month-view').addClass('active-view');
		$('#academic-calendar').removeClass('list');
		$('.day:not(:has(.ac-event.active-semester.active-term))').show();
	});



	//add events onload
	if(typeof academics !== 'undefined'){
		//console.log(academics);
		var $days = $('[id].day');
		academics.forEach(function(event, i ){
			//if the event is on the same day & is for this term
			//TODO FIX THIS TO WORK WITH DATA
			//loop through days -
			var match = false;
			var j = 0;
			while(!match){
				if($days.eq(j).attr('id') == cleanEventDate(event.date)){
					event = eventFormatChecker(event);
					var classes = 'ac-event active-category active-semester hint--top hint hint--dixie ' + event.category + ' ' + event.initials + ' ' + event.semester + ' ' + event.term;
					if(event.term == 'full'){
				 		classes+=' active-term' }
					var description = event.semester.capitalize() + ": " + event.description.capitalize();

					var html = '<div class=" ' + classes + '"  data-category="'+ event.category +'" aria-label="'+ description + '">'+ event.initials +'</div>';
					$days.eq(j).find('.events-wrap').append(html);
					match = true;
				}
				else{
					if(j < $days.length){
						j++;
					}
					else{
						match = true;//break the loop
					}
				}
			}

		});
	}
	else{console.log("can't find academics");}
});

//////////////////    HELPERS  /////////////////////////
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

Date.prototype.toSlashedStringDate = function(){
	var timestamp = new Intl.DateTimeFormat('en-US').format(this);
	return timestamp;
}
function cleanEventDate(date){
	var date = date.trim();
	if(date.includes('-') !== false){
		date = date.replace(/-/g,"/");
	}
	if(date.slice(-3,-2)== '/'){
		return date.slice(0,-2)+'20'+date.slice(-3);
	}
	else{return date;}
}

function eventFormatChecker(event){
	event.description= typeof event.description== "string" ? event.description : event.title;
	event.category = typeof event.category == "string" ? event.category.toLowerCase() : event.description.toLowerCase();
	event.initials = typeof event.initials == "string" ? event.initials: event.category;
	event.semester = event.semester.toLowerCase();
	event.term = event.term.toLowerCase();
	return event;
}

	$(".mobile-filters").click(function(e) {
      e.stopPropagation();
      $(".mobile-filters>ul").stop().slideToggle(200);
      $(document).click(function() {
        $(".mobile-filters>ul").hide();
      });
    });

    $(".RegistrationSection").click(function(e) {
      e.stopPropagation();
      $(this).find('.fa-angle-down,.fa-angle-up').toggleClass('fa-angle-down').toggleClass('fa-angle-up');
      $(".RegistrationSection>ul").stop().slideToggle(200);
      $(document).click(function() {
        $(".RegistrationSection>ul").hide();
      });
    });
    $(".RegistrationSection>ul li").click(function() {
      $(".mobile-filters>ul").slideToggle(200);
      $(".filterSelect").text(this.innerHTML);
    })


    $(".TuitionSection").click(function(e) {
      e.stopPropagation();
      $(this).find('.fa-angle-down,.fa-angle-up').toggleClass('fa-angle-down').toggleClass('fa-angle-up');
      $(".TuitionSection>ul").stop().slideToggle(200);
      $(document).click(function() {
        $(".TuitionSection>ul").hide();
      });
    });
    $(".TuitionSection>ul li").click(function() {
      $(".mobile-filters>ul").slideToggle(200);
      $(".filterSelect").text(this.innerHTML);
    })


    $(".ClassworkSection").click(function(e) {
      e.stopPropagation();
      $(this).find('.fa-angle-down,.fa-angle-up').toggleClass('fa-angle-down').toggleClass('fa-angle-up');
      $(".ClassworkSection>ul").stop().slideToggle(200);
      $(document).click(function() {
        $(".ClassworkSection>ul").hide();
      });
    });
    $(".ClassworkSection>ul li").click(function() {
      $(".mobile-filters>ul").slideToggle(200);
      $(".filterSelect").text(this.innerHTML);
    })


    $(".GraduationSection").click(function(e) {
      e.stopPropagation();
      $(this).find('.fa-angle-down,.fa-angle-up').toggleClass('fa-angle-down').toggleClass('fa-angle-up');
      $(".GraduationSection>ul").stop().slideToggle(200);
      $(document).click(function() {
        $(".GraduationSection>ul").hide();
      });
    });
    $(".GraduationSection>ul li").click(function() {
      $(".mobile-filters>ul").slideToggle(200);
      $(".filterSelect").text(this.innerHTML);
    })


    $(".BreakSection").click(function(e) {
      e.stopPropagation();
      $(this).find('.fa-angle-down,.fa-angle-up').toggleClass('fa-angle-down').toggleClass('fa-angle-up');
      $(".BreakSection>ul").stop().slideToggle(200);
      $(document).click(function() {
        $(".BreakSection>ul").hide();
      });
    });
    $(".BreakSection>ul li").click(function() {
      $(".mobile-filters>ul").slideToggle(200);
      $(".filterSelect").text(this.innerHTML);
    })


    $(".FacultySection").click(function(e) {
      e.stopPropagation();
      $(this).find('.fa-angle-down,.fa-angle-up').toggleClass('fa-angle-down').toggleClass('fa-angle-up');
      $(".FacultySection>ul").stop().slideToggle(200);
      $(document).click(function() {
        $(".FacultySection>ul").hide();
      });
    });
    $(".FacultySection>ul li").click(function() {
      $(".mobile-filters>ul").slideToggle(200);
      $(".filterSelect").text(this.innerHTML);
    })


    $(".mobile-term").click(function(e) {
      e.stopPropagation();
      $(".mobile-term>ul").stop().slideToggle(200);
      $(document).click(function() {
        $(".mobile-term>ul").hide();
      });
    });
    $(".mobile-term>ul li").click(function() {
      $(".termSelect").text(this.innerHTML);
    })


