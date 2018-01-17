#Academic Calendar v1.0
A web app by Denise Nadal & David Hulet to automate the display of the DSU Academic Calendar, so little to no maintenance is needed by web services.


##Files Included:
### /includes/academic_calendar/
* *academic_calendar.php* - This file replaces the usual 'content.php' include. It generates the static markup and collects the Academic Calendar data.
* *AcClass.js* -  This file defines and creates the Calendar Object. It generates a year calendar and puts the Academic Calendar events on it.
* *AcEventHandlers.js* - This file controls all the user interactions/onClicks.
* *academic-calendar.css* - All the custom styling for the calendar.
------------------------------------------------------------
##academic_calendar.php - Page Content
------------------------------------------------------------
this php file contains all the standard markup you would find in a content.php for the DSU theme. At the bottom of the file it includes code borrowed from

The first sets of a variable defining which calendars we would like to request from the MC RSS feed. The second includes the file which will read that variable and make the request. It will save the returned RSS feed as json in the MCG_cals javascript variable.

------------------------------------------------------------
##AcEventHandlers.js - Calendar Event Handlers
------------------------------------------------------------
All events are loaded on the calendar at page load. The events and months displayed are filtered by the classes attached to each event. In general, when a user clicks a button, we grab the data attributes, and add an "active-"[whatever-they-clicked] class to every element that matches, and remove the active class from whatever doesn't match. The CSS will show or hide elements based on the "active" status.

###on load:
	- show all full semester events for a year starting from the current semester.
	- hides block1 and block2 events by default.

###user clicks semester
	- Adds "active-semester" to any element with the semester as a class
	- removes "active-semester" from anything that doesn't have the semester.
	- also hides any months with no children assigned the "active-semester" class.
	- should also verify that all months with active semester children are visible

###user selects term
	- Adds "active-term" to any element with the term as a class
	- removes "active-term" from anything that doesn't have the term.
	- should also verify that all months with active term children are visible


###user selects a category
	- Adds "active-category" to any element with the category as a class
	- removes "active-category" from anything that doesn't have the category.
	- also display the subcategories/events in  the category.
	- hides any previously selected subcategories
	- should also verify that all months with active category children are visible


###user selects a subcat/event type
	- hides all events except for the one chosen
	- should also hide months without the chosen event.

------------------------------------------------------------
##AcClass.js - Calendar Creation
------------------------------------------------------------
###Calendar Object
DESCRIPTION: An automated calendar creator. It generates the HTML markup for one academic year, starting with the current semester. Depending on the date, it will either show January - December, May - May (of next year), or August - August (next year).

REQUIRED PARAMETERS: currently none

RETURNS: an object with startDate, endDate, length (in months) and internal methods to set the daterange and build the HTML. To render the outputed calendar, call displayCalendar on the Cal Object.

FURTHER NOTES: to extend this calendar to generate dates for additional years, modify the setDateRange function to accept a parameter to set the year.
If you want to generate a calendar for each year, create the calendar object inside a for loop and pass the current value of i into the setDateRange to iterate the year.
If you want a singular calendar, you can just call setDateRange with your chosen year int, and then buildCalendar to update the values, and finally displayCalendar to render the calendar.

###Month Object
DESCRIPTION:  The Month object will determine the dates for any given month, in relation to the start of the calendar. It must receive a date and an index to work. (The current month's dates are calculated by adding the index to the starting date)

REQUIRED PARAMETERS:  a starting date (Date obj), an index (int)

RETURN: creates an object with all the properties the month needs to be calculated and displayed, as well as a method to render the month's html

###HELPERS
####String.prototype.capitalize
This function extends the String prototype to have a method to capitalize words for better display.
PARAMETERS: none, is an object method
RETURNS: a capitalized string. Note that this capitalizes only one word.

#####Date.prototype.toSlashedStringDate
this extends the Date object to have a function to convert dates into the format used by Master Calendar: mm/dd/yyyy
PARAMETERS: none, is an object method
RETURNS: a string date, mm/dd/yyyy .
IMPORTANT : note that this also trims any leading zeros, so Jan 1st, 2017 would be 1/1/2017 rather than 01/01/2017

####eventFormatChecker
this function just checks that all the required fields are available. If not, it uses content from another field.
USED BY: Month Object
PARAMETERS: an event object representing a date/event on the academic calendar
RETURNS: an event object with all the required fields to prevent the function from throwing errors. This does not guarantee the event will be formatted correctly for filtering to works
NOTES: you may choose to expand upon this and try to catch more errors.

####cleanEventDate
This is a helper function to ensure human entered dates have standardize formatting to match computer generated dates
USED BY: Month Object
PARAMETERS: a string date
RETURNS: a sting date formatted as mm/dd/yyyy
NOTES: should be expanded upon to catch more errors

##getEndOfTerm
returns the second friday of the last month of the semester, which is usually the last day of the semester. This currently only works for future dates, so it can not be used generate past dates.
USED BY: whichSemester
REQUIRED PARAMETERS: an integer for which month you would like to get the 2nd Friday for.
RETURN: a date object for an approximate end of the semester

####whichSemester
using getEndOfTerm, this function will return a string "spring", "summer" or "fall" based on a given date. If no date is provided, it returns the semester for today. Again, this might not be 100% accurate, but close enough.
USED BY: getSemesters
REQUIRED PARAMETERS: none. OPTIONAL parameter of a date object.
RETURN: a string "spring", "summer" or "fall"

####getSemesters
used to calculate the current semester & academic calendar year
USED BY: the anonymous jQuery function that generates the calendar markup
REQUIRED PARAMETERS: currently none
RETURN:  an object with the currentSemester, a list of semesters for the current year(SemesterList), and a static list of terms['full','block1','block2'].
each semester in the SemesterList has a name and year property.

------------------------------------------------------------
##Development Notes for v2
------------------------------------------------------------
* get page to listen to GET requests with query parameters, use the params to set a  date range for the calendar and the csv/json feeds.

* mod the csv to json function to read multiple files, academic_calendar_v2.php has the beginnings of this.

* convert ::after hover elements to spans so content can be searched

* Add a "Quick Search" filtering system to return dates for most often asked questions - eg "When's Semester Break?", "Dates i need to know for to start semester", "Graduation Dates & Deadlines " etc.

* consider new interaction for clicking on "semester filter" - show all dates for that Semester(regardless of when it occurs), as well as all events that occur during those semester months (regardless of what semester they are for) "gray out" dates that aren't for that particular semester, so users understand the distinction

* consider having one continuous calendar, use javascript to scroll the calendar and only show "active events"  
