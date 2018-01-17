
jQuery(document).ready(function($){

//================ EVENT HANDLERS  ================//
	//EVENT: when user clicks on semester
	// Adds active class to calendar Terms
    $('#semester_filter li').on('click', function(){
        var semester = $(this).data('semester');
		$("."+semester).show();
        $('li.current_semester').removeClass('current_semester');
        $(this).addClass('current_semester');
                                console.log( semester +" Semester");
    });


    // Adds active class to calendar Terms
    var activeTerm = '#term_filter li';
    $(activeTerm).on('click', function(){
        $('li.current').removeClass('current');
        $(this).addClass('current');
		var term = $(this).data('term');
        $("."+term).show().siblings("div").hide();
                                 console.log("show "+term);
    });

	// This toggles the active calendar Filters
	// Displays the active filters on the calendar
    // Adds active class to calendar Categories
    var activeCategory = '#categories li';
    $(activeCategory).on('click', function(){
        $('li.active').removeClass('active');
        $(this).addClass('active');

		$("#sub-filters li").hide();
		// Categories data-filter to show all cells that match the Category
		var Category = $(this).data('filter');
			// console.log("target: ",Category);
		$("."+Category).show();

		//select every table cell or span that is not the selected class
		$('td[class]:not(.'+Category+'), td[rev]').css({opacity:'0.1'});
		//select anything with a title attribute and the selected class.
		$('[class].'+Category).css({opacity: '1'});

                                console.log("clicked cat");
    });

	// Displays the active filters on the calendar &
    // Adds active class to calendar Filters
    var activeSubFilter = '#sub-filters li';
    $(activeSubFilter).on('click', function(){
        $(this).siblings('li.active').removeClass('active');
        $(this).addClass('active');
		// Gets the active filters rel to link to the cells in the calendar that match
        var target = $(this).attr('rel');
            // console.log("target: ",target);
            //select every table cell or span that is not the selected class
            $('td[class]:not(.'+target+'), td[rev], span[class]:not(.'+target+')').css({opacity:'0.1'});
            //select anything with a title attribute and the selected class.
            $('[class].'+target).css({opacity: '1'});
                                console.log("clicked filter");
    });

});
