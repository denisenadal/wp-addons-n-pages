<?php
//////////////////    HELPERS  /////////////////////////

function getEndOfTerm($monthNum){
	//set date to selected month
	$date = new DateTime();
	$date->setDate($date->format('Y'), $monthNum, 1);
	if($monthNum < 6){
		$date->modify('second fri of this month');
		//return date object for 2nd friday of month
	}
	else{
		$date->modify('third fri of this month');
		//return date object for 2nd friday of month
	}
	return $date;
}



//////////////////    MONTH OBJECT   /////////////////////////
//creates html markup for a month that is i months after the starting date. Full notes in the documentation.md
class Month {
	private $calStart;
	private $startDate;//first date of month
	private $endDate;//last date of month
	private $num;//the num of the month
	private $length;//num of days in month
	private $year;//the year
	private $startingDayOfWeek; //number of the day of week of the first day
	private $monthName; //string name of month
	public $html = '<div class="calendar-month">';

	function __construct($calStartDate, $i ){
		$this->calStart = $calStartDate;
		$this->year = $this->calStart->format('Y');
		$this->startDate = clone $calStartDate;
		$this->startDate->modify('+'.$i.' months');

		$this->startingDayOfWeek = $this->startDate->format('w');
		$this->endDate = clone $this->startDate;
		$this->endDate->modify('last day of this month');
		$this->length = $this->endDate->format('t');
		$this->monthName = $this->startDate->format('F');
		$this->year = $this->startDate->format('Y');
		$this->addMonthHeader();
		$this->addMonthDays();
	}

	private function addMonthHeader(){
		$days_labels = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
		//print the month's header
		$this->html .= '<div class="month">'. $this->monthName . '</div>';
		$this->html.= '<div class="calendar-header">';
		for ($i = 0; $i <= 6; $i++) {
			$this->html .= '<div class="dow">';
			$this->html .= $days_labels[$i];
			$this->html .= '</div>';
		}
		$this->html .= '</div>';
	}

	private function addMonthDays(){
		$day = 1;
		$date = clone $this->startDate;
		// this loop is for is weeks (rows)
		for ($i = 0; $i < 8; $i++) {
		  	$this->html .= '<div class="week clearfix">';
			// this loop is for weekdays (cells)
			for ($j = 0; $j <= 6; $j++) {
				//create a day div for each day of week
				if ($day <= $this->length && ($i > 0 || $j >= $this->startingDayOfWeek)) {
					//if this day is actually part of this month, fill in the content
					$timestamp = date_format($date, 'n/j/Y');
					$this->html .= '<div class="day" id="'. $timestamp .'" >';
					$this->html .='<span class="day-number">'.$day.'</span><div class="events-wrap"></div>';
					$day++;
					$date->modify('+1 day');
				}else{
					//else just put in blank cell
					$this->html .= '<div class="day">';
				}
				$this->html .= '</div>';//close day div
			}
			$this->html .= '</div>';//end week div
			// stop making rows if we've run out of days
			if ($day > $this->length) {
				//if end of month, close the month div
				$this->html .= '</div>';
				break;
			}
		}
	}

	public function getHTML(){
		return $this->html;
	}
}



//////////////////   CALENDAR OBJECT  /////////////////////////
//this function generates the dates for the calendar and calls the month object to build each month. Full notes in the documentation.md
class Calendar{
	public $calHTML;
	public $calLength;
	public $startDate;
	public $endDate;
	public $semesters;
	public $semesterFilter;
	public function __construct(){
		date_default_timezone_set ( 'America/Denver' );
		$this->setDateRange();
		$this->buildCalendar();
		$this->buildSemesterFilter();
	}

	/*get a start and end to the calendar*/
	public function setDateRange($date = NULL ){
		$date = isset($date) ? $date : new DateTime();
		$thisYear = $date->format('Y');
		if($this->whichSemester($date) == "spring"){
			$this->startDate = new DateTime($thisYear.'-1-1');
			$this->endDate = new DateTime($thisYear.'-5-31');
			$this->calLength = 12;
		}
		else if($this->whichSemester($date) == "summer"){
			$this->startDate = new DateTime($thisYear.'-5-1');
			$this->endDate = new DateTime(($thisYear+1).'-8-31');
				$this->calLength = 13;
		}
		else{
			$this->startDate = new DateTime($thisYear.'-8-1');
			$this->endDate = new DateTime(($thisYear+1).'-8-31');
			$this->calLength = 13;
		}
	}
	/* returns which semester a given date is in, or current semester for today*/
	public function whichSemester($date ){
		$date = isset($date) ? $date : new DateTime();
		$thisYear = date('Y');

		if($date >= new DateTime($thisYear.'-1-1') && $date <= getEndOfTerm(5) ){
			return "spring";
		}
		else if($date > getEndOfTerm(5)  && $date <= getEndOfTerm(8) ){
			return "summer";
		}
		else if($date < getEndOfTerm(12) ){
			return "fall";
		}
		else {return "spring";}
	}

	/*returns an aray of the current semester, calendar-year, and terms*/
	public function getSemesters(){
		$semesterList;
		$thisYear = date('Y');
		$terms = ['full', 'block1', 'block2'];
		$today = new DateTime();
		$currentSemester = $this->whichSemester($today);
		$spring = array('name' => 'spring', 'year' =>  $thisYear);
		$summer = array('name' => 'summer', 'year' => $thisYear);
		$fall = array('name' => 'fall', 'year' => $thisYear);

		if($currentSemester == "spring"){
			$semesterList = array( $spring, $summer, $fall);
			//set current spring semester to start at end of last fall

		}
		else if($currentSemester == "summer"){
			$spring['year'] ++;
			$semesterList = array($summer, $fall, $spring);
		}
		else{
			$spring['year']++;
			$summer['year']++;
			$semesterList = array($fall, $spring, $summer);
		}
		//echo $currentSemester;
		return array(
			'currentSemester' => $currentSemester,
			'semesterList' => $semesterList,
			'terms' => $terms,
		);
	}

	private function buildCalendar(){
		//loop over each month in the semester
		for($i=0;$i < $this->calLength; $i++){
			$month = new Month($this->startDate,$i);
			$this->calHTML .= $month->getHTML();
		}
	}

	private function buildSemesterFilter(){
		$this->semesters = $this->getSemesters();
		foreach ($this->semesters['semesterList'] as $semester) {
			//add each semester to the semester filter
			$classes = $semester["name"] ." semester-option";
			$item = '<li class="'. $classes .'" data-semester="'. $semester['name'] .'" data-year="'. $semester['year'] .'">'.ucfirst($semester['name']) .' '. $semester['year'] . '</li>';
			$this->semesterFilter .= $item;
		}
	}

	public function displaySemesterFilter(){
		return $this->semesterFilter;
	}

	public function displayCalendar(){
		return $this->calHTML;
	}

	public function displayCalendarHeader(){
		$startYear =$this->semesters['semesterList'][0]['year'];
		$endYear = $this->semesters['semesterList'][2]['year'];

		//if calendar covers only one year, return it, otherwise, seperate the years with a hypen(eg: 2017 or 2017 - 2018)
		$years = $startYear!= $endYear ? $startYear .' - '. $endYear : $startYear;

		//display the calendar
		return '<h2 class="SemesterTitle"> Academic Calendar '. $years .'</h2> <hr> ';
	}



}


$cal = new Calendar();
