function appendAcademicCalendar(){
	if(academics == "undefined"){
		return;
	}
	var today = new Date();
	for(var i=0;i<academics.length;i++) {
		academics[i].eventstamp = new Date(academics[i]["date"]);
		//if it is before today and on the "full" semester, format the date
		if (academics[i].eventstamp >= today && academics[i].term === "Full" ) {
			academics[i].month = academics[i].eventstamp.toLocaleString('en-us', { month: "short" });
			academics[i].dayOfMonth = academics[i].eventstamp.getDate();
			if(i>0){
				//if this is the same event as the previous, combine their display into one event.
				if(academics[i].description == academics[i-1].description){
					academics[i-1].dayOfMonth = academics[i-1].eventstamp.getDate() + "-"+ academics[i].dayOfMonth;
					academics.splice(i,1);
					i--;
				}//end if match
			}//end if >0
		}//end if future & full semester
		else{
			//rotherwise remove from the array
			academics.splice(i,1);
			i--;
		}
	}//end of for loop

	academics.sort(function(a,b) {
		return a.eventstamp-b.eventstamp;
	});

	for(var j=0; j < academics.length; j++) {
		var row='<div class="dsu-ac-item">';
		row+='<div class="dsu-ac-date"><div class="date-box">';
		row += '<h4>'+ academics[j].month + '</h4>';
		row += '<h5>' + academics[j].dayOfMonth + '</h5></div></div>';
		row+='<div class="dsu-ac-description">'+academics[j].description+'</div>';
		row+='</div>';
	   $(".dsu-ac-dates").append(row);
   }
}



jQuery(document).ready(function($){
	appendAcademicCalendar();
});
