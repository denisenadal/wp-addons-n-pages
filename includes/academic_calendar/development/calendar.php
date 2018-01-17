<?php
# PHP Calendar (version 2.3), written by Keith Devens
# http://keithdevens.com/software/php_calendar
#  see example at http://keithdevens.com/weblog
# License: http://keithdevens.com/software/license


class calendar {

	private $valid_css_pattern;
	private $valid_css_replace;
	private $database_connection;
	/**
	 * Creates new calendar object 
	 * @param string|db_connect|null $database string of the database to connect to (using default options) OR a db_connect object OR null (for when you just want to use some none database related member function)
	 * 
	 * */
	public function __construct($database = null) {
		if(!defined('MY_PHP_DIR'))
		{
			include_once($this->find_included_dir() . '/global_functions.php');
		}
		if(!function_exists('db_connect'))
		{
			include_once(MY_PHP_DIR . "/database.php");
		}

		if(isset($database) && is_object($database) && get_class($database) == 'db_connect')
		{
			$this->database_connection = $database;
		}
		elseif(isset($database) && is_string($database))
		{
			$this->database_connection = new db_connect($database);
		}
		else 
		{
			$this->database_connection = null;
		}

		//replace anything that isn't valid css names with underscores
		$this->valid_css_pattern = '/[^\w\d\_\.\#]*/';
		$this->valid_css_replace = '_';
		
	}
	public function getDatabaseConnection()
	{
		return $this->database_connection;
	}
	public function find_included_dir()
	{
		// For the script that is running:
		$script_directory = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/'));
		// If your script is included from another script:
		$included_directory =  substr(__FILE__, 0, strrpos(__FILE__, '\\')); /*__FILE__;*/
		return $included_directory;
	}
	/**
	 * 
	 * Generates an HTML calendar based on the input arrays, etc.
	 * @param string $year
	 * @param string $month
	 * @param array $days
	 * @param int $day_name_length
	 * @param string $month_href
	 * @param int $first_day
	 * @param array $pn
	 * @param array $inline_styles
	 * @param bool $pdf_safe_version
	 * 
	 * @return string $calendar HTML calendar
	 */
	public function generate_calendar($year, $month, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array(), $inline_styles = array(),$pdf_safe_version = false){
		global $valid_css_pattern, $valid_css_replace;
	
		$calendar = "";
		
		$first_of_month = gmmktime(0,0,0,$month,1,$year);
		#remember that mktime will automatically correct if invalid dates are entered
		# for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
		# this provides a built in "rounding" feature to generate_calendar()
	
		$day_names = array(); #generate all the day names according to the current locale
		for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
			$day_names[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name
	
		list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
		$weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
		$title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names
	
		#Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
		@list($p, $pl) = each($pn); @list($n, $nl) = each($pn); #previous and next links, if applicable
		if($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
		if($n) $n = '&nbsp;<span class="calendar-next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
		
		
		if(!$pdf_safe_version)
		{//TCPDF creates an extra line for each <div>, so I need to take this one out for PDF version
			$calendar .= "<div class=\"calendar\" style=\"{$inline_styles['.calendar']}\">";
		}
		$calendar .= "<div class=\"calendar-month\" style=\"{$inline_styles['.calendar-month']}\">&nbsp;" . $p . ($month_href ? '<a href="'.htmlspecialchars($month_href).'">&nbsp;'.$title.'</a>' : "$title") . $n . "&nbsp;</div>\n";
		$calendar .= "<table border=\"1\" style=\"{$inline_styles['.calendar table']}\">\n".
			"<tr>";
	
		if($day_name_length){ #if the day names should be shown ($day_name_length > 0)
			#if day_name_length is >3, the full name of the day will be printed
			foreach($day_names as $d)
				$calendar .= '<th style="'.$inline_styles['.calendar th'].'" abbr="'.htmlentities($d).'">'.htmlentities($day_name_length < 4 ? substr($d,0,$day_name_length) : $d).'</th>';
			$calendar .= "</tr>\n<tr>";
		}
		$most_codes_this_week = 0;
		if($weekday > 0) $calendar .= '<td class="non_day" style="'.$inline_styles['.non_day'].' '.$inline_styles['.calendar td'].'" colspan="'.$weekday.'">&nbsp;</td>'; #initial 'empty' days
		for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
			if($weekday == 7){
				$weekday   = 0; #start a new week
				$calendar .= "</tr>\n<tr>";
				$most_codes_this_week = 0;
				for($ndays = 0; $ndays < 7; $ndays++)
				{
					if(count($days[$day + $ndays]) > $most_codes_this_week)
					{
						$most_codes_this_week = count($days[$day + $ndays]);
					}
				}
			}
			if(isset($days[$day]) and is_array($days[$day])){
				
				
				$calendar .= '<td>';
					//put the code in the cell (as many codes as there are for that day)
					$day_code_number = count($days[$day]);
					$font_size = ((50 / $day_code_number) + 50) / 100;//POTENTIAL DIVIDE BY ZERO!!!!
					$td_height = "height: 100%;";
					if($day_code_number > 1)
					{
						$td_height = '';
					}
	
	 				$calendar .= "<table border=\"0\" class=\"sub_table\" style=\"{$inline_styles['table.sub_table']}\">\n";//tcpdf requires that I use double quotes for attributes!!!
	 				$td_counter = 0;
	 				$calendar .= "<tr>";//start off with the first row, the foreach will handle the rest.
	 				
					foreach ( $days[$day] as $code ) 
					{
						if($td_counter >= 3)
						{
							$calendar .= "</tr>\n<tr>";
							$td_counter = 0;
						}
						
						@list($link, $classes, $content, $alt, $date, $id) = $code;
						if(is_null($content))  $content  = $day;
						$calendar .= "<td " . ($classes ? ' title="'.preg_replace("/\"/","&quot;",$alt).' - '.$date.'" style="'.$inline_styles['.' . $classes] .' '.$inline_styles['.calendar td'].' '.$td_height.' font-size:'.$font_size.'em;" class="'.htmlspecialchars($classes).' day day_code" rev="date_id=' . $id . '" onmouseover="addClassName(this,\'date_hover\')" onmouseout="removeClassName(this,\'date_hover\')">' : ' style="'.$inline_styles['.day'] .' '.$inline_styles['.calendar td'].'" class="day">') . ($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : $content)."</td>";
						$td_counter++;
					}
					$calendar .= "</tr></table>\n";
				$calendar .= '</td>';
			}//put the day number in the cell
			else $calendar .= "<td style=\"".$inline_styles['.day']." ".$inline_styles['.calendar td']."\" class='day' rev='date=$year-$month-$day'>$day</td>";
		}
		if($weekday != 7) $calendar .= '<td style="'.$inline_styles['.non_day'].' '.$inline_styles['.calendar td'].'" class="non_day" colspan="'.(7-$weekday).'">&nbsp;</td>'; #remaining "empty" days
	
		$calendar .= "</tr>\n</table>\n";
		if(!$pdf_safe_version)
		{
			$calendar .= "</div>\n";
		}
		return $calendar;
		
	}
	/**
	 * creates the necessary CSS for our table to work (different colors for different codes, etc)
	 * @param array $classes_array
	 * 		must be in this format
	 * 		array[0] => array('Code'=>'unique identifier','Descripton'=>'some name for the class','Color'=>'#FFFFFF')
	 * 		array[1] => array('Code'=>'unique identifier','Descripton'=>'some name for the class','Color'=>'#FFFFFF')
	 * 		etc...
	 * @return string correctly formatted CSS code
	 */
	public function generate_calendar_css($classes_array,$output_array = false)
	{
		global $valid_css_pattern, $valid_css_replace;
		if(!$output_array)
		{//this is for normal webviewing
			$css = "<style>\n";
		
			$css .=
			".calendar-month
			{
				background-color: #00224F;
				color: #ffffff;
				font-weight: bold;
				font-size: large;
				padding-left: 10px;
			}
			.calendar table
			{
				border: 1px solid #000000;
				border-collapse: collapse;
				
				cursor: default;
				width: 100%;
			}
			.calendar td, th
			{
				border: 1px solid #000000;
				border-collapse: collapse;
				/*padding: 3px 8px;*/
				padding: 0;
				text-align: center;
				vertical-align:middle;
				height:2em;
				width:50px;
			}
		    .calendar th
		    {
		    	background-color: #791112;
		    	color: #FFFFFF;
		    }
		    .calendar
		    {
		    	/*border: solid 1px #000000;*/
		    	/*height: 171px;/*I decided that hieght wasn't looking very good anymore, just let it be more fluid*/
		    }
		    .calendar_title
		    {
		    	font-size: large;
		    	text-align: center;
		    }
			.calendar_menu_title
			{
				font-size: large;
				font-weight: bold;
			}
			.day
			{
				background-color: #FFFFFF;
			}
			table.sub_table
			{/*these are the sub-tables for each day that has a day code in it*/
				border: none;
				height:100%;
				width:100%;
			}
			table.sub_table td
			{
				border: none;
			}
			.non_day
			{
				background-color: #CFCFCF;
			}";
			foreach ( $classes_array as $class )
			{
				$css .= '.' . preg_replace($this->valid_css_pattern,$this->valid_css_replace,$class['description']) . "_calendar {background-color: #{$class['color']}; cursor:help; font-weight: bold;}\n";
			}
			//change the hover color
			$css .=
			".date_hover
			{
				background-color: #7F2222;
				color: #FFFFFF;
			}
			.legend_hover
			{
				color: #7F2222;
				text-decoration: underline;
			}
			.my_hr
			{
				border-bottom:1px solid #999999; margin:4px;
			}
			";
			$css .= "</style>\n";
			return $css;
			
		}
		else
		{//this is for the pdf output, which requires inline styles
			$css = array();

			$css['.calendar-month'] = "background-color: #00224F; color: #ffffff; font-weight: bold; font-size: large; cursor: default; width: 100%;";
			$css['.calendar table'] = "border: 1px solid #000000; border-collapse: collapse; cursor: default; margin: 0px; padding:0px;";
			$css['.calendar td'] = "text-align: center; height: 2em; padding: 0px;";
			$css['.calendar th'] = "border: 1px solid #000000; border-collapse: collapse; padding: 0; text-align: center; background-color: #791112; color: #FFFFFF; word-wrap: normal;";
	

			$css['.day'] = "background-color: #FFFFFF;";
			$css['.non_day'] = "background-color: #CFCFCF;";
			foreach ( $classes_array as $class ) 
			{
				$css['.' . preg_replace($this->valid_css_pattern,$this->valid_css_replace,$class['description']) . "_calendar"] = "background-color: #{$class['color']}; cursor:help; font-weight: bold; border:1px solid #ffffff;";
			}
			$css['.date_hover'] = "background-color: #7F2222; color: #FFFFFF;";
	
			return $css;
		}
	}
	/**
	 * returns a properly formatted array of dates within the range specified by the calendars_array
	 */
	public function generate_calendar_dates($calendar_id,$calendars_array,$dates_array,$codes_array = array(),$date_types = array(""))
	{
		global $valid_css_pattern, $valid_css_replace;
		$temp_array = array();
		$dates_used = array();
		foreach ( $dates_array as $date ) 
		{
			if($date['calendar_id'] == $calendar_id && in_array($date['type'],$date_types))
			{
				preg_match("/(\d+)\/(\d+)\/(\d+)/", $date['date'],$matches);
				$day = $matches[2];
				$month = $matches[1];
				$year = $matches[3];
				if(!is_array($temp_array[$year]))
				{
					$temp_array[$year] = array();
				}
				if(!is_array($temp_array[$year][$month]))
				{
					$temp_array[$year][$month] = array();
				}
				$class_name = $title = $codes_array[$date['date_code']]['description'];
				if($date['message'])
				{
					$title = $date['message'];
				}
				$dates_used[$date['date']]++;
				$temp_array[$year][$month][$day][$dates_used[$date['date']]] = array($date['link'],preg_replace($this->valid_css_pattern,$this->valid_css_replace,$class_name) . "_calendar",$date['date_code'],$title,$date['date'],$date['id']);
		    	
		    	
			}
	    }


	    return $temp_array;
	}
	public function generate_calendar_from_csv($calendars,$dates,$codes,$calendar_id = '',$printable = false,$date_types = array("Calendar",""))
	{
		$calendars_array = csv2array($calendars);
		$dates_array = csv2array($dates);
		$codes_array = csv2array($codes);
		
		return $this->generate_calendar_from_arrays($calendars_array,$dates_array,$codes_array,$calendar_id,$printable,$date_types);
	}
	public function generate_calendar_from_sql($calendar_id = '',$printable = false,$date_types = array("Calendar",""))
	{
		 //I know this could be done better with sql that it is, but I'm trying to keep the csv functionality for now, as it would take a long time to figure out how to convert all the php code over to sql
		 $calendars_array = $this->database_connection->getQueryArray("select calendar_id,year,calendar_name,url,date_format(start_date,'%c/%e/%Y') as start_date,date_format(end_date,'%c/%e/%Y') as end_date,active from calendars");
		 $dates_array = $this->database_connection->getQueryArray("select dates.id,calendar_id,date_code,date_format(date,'%c/%e/%Y') as date, message, link, type from dates order by calendar_id,date_code,dates.date",'id');
		 $codes_array = $this->database_connection->getQueryArray("select date_code,description,color from codes");
		 return $this->generate_calendar_from_arrays($calendars_array,$dates_array,$codes_array,$calendar_id,$printable,$date_types);
	}
	public function generate_calendar_from_arrays($calendars_array,$dates_array,$codes_array,$calendar_id = '',$printable = false,$date_types = array("Calendar",""))
	{
		
		$calendars_assoc_array = array2assoc($calendars_array);
		$codes_assoc_array = array2assoc($codes_array);
		
			 //decide what calendar id to use
		 
		 //decide whether to use inline styles or embedded
		 	//get stylesheet or inline styles
		 	
		 //get all the dates that match the calender_id and that are between that calendar_id's start and end dates
		
		
		//initialize html code that will eventually be returned
		$html = '';
		$start_date = '';
		$end_date = '';
		$calendar_title = '';
		
		if(!$calendar_id)
		{//if no calendar_id is specified, we need to decide what calendar to generate, based off of the current date
			$today = strtotime("now");
			foreach ( $calendars_array as $key => $calendar )
			{
				if((strtotime($calendar['end_date']) > $today) && (strtotime($calendar['start_date']) < $today))
				{
					$calendar_id = $calendar['calendar_id'];
					$start_date = $calendar['start_date'];
					$end_date = $calendar['end_date'];
					$calendar_title = $calendar['calendar_name'];
				}
			}
		}
		else
		{
			$start_date = $calendars_assoc_array[$calendar_id]['start_date'];
			$end_date = $calendars_assoc_array[$calendar_id]['end_date'];
			$calendar_title = $calendars_assoc_array[$calendar_id]['calendar_name'];
		}
	
		
		$calendar_dates = $this->generate_calendar_dates($calendar_id,$calendars_array,$dates_array,$codes_assoc_array,$date_types);
		
		$inline_styles = '';
		if(!$printable)
		{
			$html .= $this->generate_calendar_css($codes_array);
		}
		else
		{
			$inline_styles = $this->generate_calendar_css($codes_array,$printable);
		}
		
		preg_match('/(\d+)\/(\d+)\/(\d+)/',$start_date,$matches);
		$month_iterator = $matches[1];
		$year_iterator = $matches[3];
		preg_match('/(\d+)\/(\d+)\/(\d+)/',$end_date,$matches);
		$end_month = $matches[1];
		$end_year = $matches[3];
		
		if($year_iterator && $month_iterator /*&& count($calendar_dates)*/)
		{//only try to make the calendar if we were able to find dates
			if($calendars_assoc_array[$calendar_id]['active'] != 'Y')
			{
				$calendar_title .= " <span style=\"color: #BB9999; font-style:italic;\">Unofficial</span>";
			}
			$html .= "<div class='calendar_title'>$calendar_title</div>\n";
			$keep_going = true;
			while($keep_going && $year_iterator <= $end_year)
			{
				if($year_iterator == $end_year)
				{
					if($month_iterator >= $end_month)
					{
						$keep_going = false;
					}
				}
				
				$html .= $this->generate_calendar($year_iterator,$month_iterator,$calendar_dates[$year_iterator][$month_iterator],1,NULL,0,array(),$inline_styles,$printable);
				if($month_iterator >= 12)
				{
					$month_iterator = 1;
					$year_iterator++;
				}
				else
				{
					$month_iterator++;
				}
			}
			
		}
		return $html;
	}
	/**
	 * @param string $calendars_csv (filename of the csv file to load)
	 * @param bool $by_year (if true, this will output menu's based on year, and not by calendar)
	 */
	public function generate_calendar_menu_csv($calendars_csv,$sort_by = 'calendar_name', $group_identical_items = false, $custom_text = '', $text_position = "right", $show_menu_group = '')
	{
		$calendars = csv2array($calendars_csv);
		return $this->generate_calendar_menu($calendars,$sort_by, $group_identical_items, $custom_text, $text_position, $show_menu_group);
	}
	public function generate_calendar_menu_sql($sort_by = 'calendar_name', $group_identical_items = false, $custom_text = '', $text_position = "right", $show_menu_group = '')
	{
		$calendars = $this->database_connection->getQueryArray("select * from calendars order by calendar_id");
		return $this->generate_calendar_menu($calendars,$sort_by, $group_identical_items, $custom_text, $text_position, $show_menu_group);
	}
	public function generate_calendar_menu_sql_reverse($sort_by = 'calendar_name desc', $group_identical_items = false, $custom_text = '', $text_position = "right", $show_menu_group = '')
	{
		$calendars = $this->database_connection->getQueryArray("select * from calendars order by calendar_id desc");
		return $this->generate_calendar_menu($calendars,$sort_by, $group_identical_items, $custom_text, $text_position, $show_menu_group);
	}
	public function generate_calendar_menu($calendars,$sort_by = 'calendar_name', $group_identical_items = false, $custom_text = '', $text_position = "right",$show_menu_group = '')
	{
		$html = '<div id="calendar_list_menu">';
		$display = '';//determines if the menu sub items will show or not
		$navBarPlus = '';//determines if the menu will have a plus "+" or not
		$been_used = array();
		
		$left_text = '';
		$right_text = '';
		switch ( $text_position )
		{
		case "left":
			$left_text = $custom_text;
			break;
		case "right":
			$right_text = $custom_text;
			break;
		}
		
		foreach ( $calendars as $index => $calendar ) 
		{
			if(($been_used[$calendar[$sort_by]] && $group_identical_items) || $calendar['active'] == 'N')
			{//if they've chosen to group identical items together, then skip if it's been used before
				continue;
			}
		    if($calendar[$sort_by] < $show_menu_group)
		    {
		    	$display = 'none';
		    	$navBarPlus = 'navBarPlus';	
		    }
		    else
		    {
		    	$display = '';
		    	$navBarPlus = '';
		    }		
			$html .= "<div class='calendar_menu_item'>\n";
		    $html .= "<div class='calendar_menu_title'>$left_text{$calendar[$sort_by]}-". ($calendar[$sort_by] + 1) ."$right_text</div>\n";
		    $html .= "<div class='my_hr'></div>";
		    $html .= "<div class='calendar_menu_link'><a href='?page=calendar&yid={$calendar['year']}'>Web Version</a></div>\n";
		    $html .= "<div class='calendar_menu_link'><a href='".SITE_DIR."/content.php?yid={$calendar['year']}&pdf=true'>Printer Friendly Version</a></div>";
		    $html .= "<div id='calendar_menu_{$calendar['year']}' class='calendar_menu_link cursorExtra $navBarPlus' onclick='blind(\"{$calendar['year']}_semesters\"); addRemoveClass(\"calendar_menu_{$calendar['year']}\",\"navBarPlus\");' title='click to toggle semester list'>Individual Semesters:</div>";
		    $html .= "<ul class='calendar_menu_list' id='{$calendar['year']}_semesters'>";
		    foreach ( $calendars as $key => $value ) 
			{
			       if($value['year'] == $calendar['year'] && $value['active'] == 'Y')
			       {
			       		$html .= "<li class='calendar_menu_link'><a href='{$value['url']}'>{$value['calendar_name']}</a></li>\n";
			       }
			}
		    $html .= "</ul></div>\n";
	
		    $html .= "<script type='text/javascript'>$('#{$calendar['year']}_semesters').css('display','$display');</script>";//hide the individual menus
		    	
	
		    $been_used[$calendar[$sort_by]] = true;
		}
		$html .= "</div>";//close calendar_menu div
		return $html;
	}
	public function generate_calendar_legend_csv($codes_csv,$printable = false)
	{
		$codes = csv2array($codes_csv);
		return $this->generate_calendar_legend($codes,$printable);
	}
	public function generate_calendar_legend_sql($printable = false)
	{
		$codes = $this->database_connection->getQueryArray("select * from codes where show_in_legend = 1 order by date_code"); 
		return $this->generate_calendar_legend($codes,$printable);
	}
	public function generate_calendar_legend($codes,$printable = false)
	{
		global $valid_css_pattern, $valid_css_replace;
		//I'm using inline styles so that the pdf renderer with work correctly. It only recognizes inline styles
	
	
		$padding = 0;//cellpadding is the only way to do padding in TCPDF at the moment
		$html = "<div id=\"legend_control\" onmouseover=\"addClassName(this,'legend_hover')\" onmouseout=\"removeClassName(this,'legend_hover')\" onclick='blind(\"hideable_legend\"); addRemoveClass(\"legend_control\",\"navBarPlus\"); return false;' class='cursorExtra' title=\"show/hide legend\">Calendar Legend</div>";
		$html .= "<div class=\"my_hr\"></div>";

	
		$table_style = '';
		if($printable)
		{
			$table_style = "font-size: .5em;";
			$padding = 1;
		}
		$html .= "<table border=\"1\" cellpadding=\"$padding\" cellspacing=\"0\" style=\"border-collapse:collapse; border: 1px solid; padding: 10px; background-color: #FFFFFF; $table_style\" id=\"hideable_legend\">";
		
		foreach ( $codes as $index => $code ) 
		{
			$style = '';
			if($printable)
			{
				$style = "background-color: #{$code['color']}; cursor: pointer; font-weight: bold; width: 20px;";
			}
	       $html .= "<tr><td style=\"$style text-align: center;\" class=\"".preg_replace($this->valid_css_pattern,$this->valid_css_replace,$code['description']) . "_calendar\">{$code['date_code']}</td><td style=\"padding: 2px;\">{$code['description']}</td></tr>\n";
		}
		$html .= "</table>";

		if(!$printable)
		{//pdf generator doesn't like javascript
			//this script will hide the legend. if the user does not have javascript enabled, the legend will not be hidden
			$html .= "<script type=\"text/javascript\">\$('#hideable_legend').css('display','none'); addRemoveClass('legend_control','navBarPlus');</script>";		
		}
		return $html;
	}
	public function generate_schedule_from_csv($calendar_id,$type = array('Schedule',''),$calendar_csv = '', $codes_csv = '', $dates_csv = '',$add_title = false)
	{
		if(!$dates_csv || !$codes_csv || !$calendar_csv)
		{
			 $codes_csv = PAGES_DIR . "/Reg/Calendar/codes.csv";
			 $dates_csv = PAGES_DIR . "/Reg/Calendar/dates.csv";
			 $calendar_csv = PAGES_DIR . "/Reg/Calendar/semesters.csv";
		}
	 $calendar_array = $this->generate_array_of_dates_csv($calendar_csv,$dates_csv,$codes_csv,$calendar_id,true,$type,$add_title);
	 return array2html($calendar_array,false,"border=\"0\" style=\"border-collapse:collapse;\"");
	}
	public function generate_schedule_from_sql($calendar_id, $type = array('Schedule',''), $add_title = false)
	{
	 $array = array();
	 $calendar_array = $this->generate_array_of_dates_sql($calendar_id,true,$type,$add_title);

	 
	 $array = array2html($calendar_array,$add_title,"border=\"0\" style=\"border-collapse:collapse;\"") /*. highlight_string(print_r($calendar_array,true),true)*/;
	 
	 return $array;
	}
	public function generate_schedule($calendar_id,$type = array('Schedule',''),$add_title = false)
	{
	  return $this->generate_schedule_from_sql($calendar_id, $type,$add_title);
	}
	public function generate_array_of_dates_csv($calendar_csv,$dates_csv,$codes_csv,$calendar_id,$group_similar_dates = true,$date_type = array(''),$add_title = false)
	{
		$calendar_array = csv2array($calendar_csv);
		$dates_array = csv2array($dates_csv);
		$codes_array = csv2array($codes_csv);
		return $this->generate_array_of_dates($calendar_array,$dates_array,$codes_array,$calendar_id,$group_similar_dates,$date_type,$add_title);	
	}
	public function generate_array_of_dates_sql($calendar_id,$group_similar_dates = true, $date_type = array(''),$add_title = false)
	{
		 
		 //I know this could be done better with sql, but I'm trying to keep the csv functionality for now, as it would take a long time to figure out how to convert all the php code over to sql
		 //for now, I am grabbing the information from my database in the exact format as I was from the CSV files
		 $calendars_array = $this->database_connection->getQueryArray("select calendar_id,year,calendar_name,url,date_format(start_date,'%c/%e/%Y') as start_date,date_format(end_date,'%c/%e/%Y') as end_date,active from calendars");
		 $dates_array = $this->database_connection->getQueryArray("select id,calendar_id,date_code,date_format(date,'%c/%e/%Y') as date, message, link, type from dates order by calendar_id,dates.date,date_code",'id');
		 $codes_array = $this->database_connection->getQueryArray("select date_code,description,color from codes");
		 $array_of_dates = $this->generate_array_of_dates($calendars_array,$dates_array,$codes_array,$calendar_id,$group_similar_dates,$date_type, $add_title);
		 
		 return $array_of_dates;
	}
	public function generate_array_of_dates($calendars_array,$dates_array,$codes_array,$calendar_id,$group_similar_dates = true,$date_type = array(''),$calendar_name_as_first_row = false)
	{
		$array = array();
	
		$codes_assoc_array = array2assoc($codes_array);
		$calendars_assoc_array = array2assoc($calendars_array);
		if($group_similar_dates)
		{//this query will return a list of dates that should be grouped together
			$query = "SELECT DISTINCT dates.*
						FROM dates,
						  dates d2
						WHERE dates.date_code             = d2.date_code
						AND (DATEDIFF(dates.date,d2.date) = 1
						OR DATEDIFF(dates.date,d2.date)   = -1)
						AND dates.calendar_id             = d2.calendar_id
						AND dates.calendar_id             = '$calendar_id'
						ORDER BY dates.date";
			$grouped_dates = $this->database_connection->getQueryArray($query,'id');

		}
			
		$date_format_pattern = '#(\d+)/(\d+)/(\d+)#';
		$new_date_format_pattern = '/(\w+)&nbsp;(\d+)[\-\d]*$/';//helps us add dates on to grouped dates
		
		$array_index_counter = 0;
		$last_time = 0;
		
		//these variables will be used to determine if a date range spans across more than one month
		$last_month = 13;//just set it to something that would never be less than a valid month, to start.
		$last_month_code = '';
		
		$months = array(1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
		$previous_date_was_modified = false;
	
		foreach ( $dates_array as $dates_array_index => $date ) 
		{	

		    if($date['calendar_id'] == $calendar_id && in_array($date['type'],$date_type))
		    {//only get those dates that are of type $date_type
		    	
		    	$this_time = strtotime($date['date']);
	    		preg_match($date_format_pattern,$date['date'],$date_matches);
	    		$this_month = $date_matches[1];
	    		$this_day = $date_matches[2];

	
	    		if($group_similar_dates && is_array($grouped_dates[$dates_array_index]) && is_array($grouped_dates[$last_id]) && $date['date_code'] == $grouped_dates[$last_id]['date_code'])
		    	{//it's a date that needs to be grouped together ie "Apr 3", "Apr 4", "Apr 5" => "Apr 3-5"
		    	 //but only if it's not the first in the group

			    		if($this_month > $last_month && $last_month_code == $date['date_code'])
			    		{//this date spans at least one month

			    			$array[$array_index_counter-1][0] .= ", {$months[$this_month]}&nbsp;$this_day";
			    			$last_month = 13;
			    		}
			    		else
			    		{//normal grouping, run regex replace to add the new day at the end
			    			$array[$array_index_counter-1][0] = preg_replace($new_date_format_pattern,'$1&nbsp;$2-' . $this_day,$array[$array_index_counter - 1][0]);
			    			$last_month = $this_month;
			    		}
			    		
			    		$previous_date_was_modified = true;
			    	//all group dates must set these variables
		    		$last_time = $this_time;
		    		$last_month_code = $date['date_code'];
		    	}
		    	else
		    	{//not a grouped date (or the first of a grouped date), just push it onto the array
		    		if($group_similar_dates && is_array($grouped_dates[$dates_array_index]))
		    		{//first in the group still needs to set these variables
		    			$last_month = $this_month;
		    			$last_month_code = $date['date_code'];
		    		}
		    		//get the default description for this code type
			    	$description = $codes_assoc_array[$date['date_code']]['description'];
	
			    	//if there's a custom description, get it instead
			    	if($date['message'])
			    	{
			    		$description = $date['message'];
			    	}
			    	
			    	//convert dates like "2/17/2009" into "Feb 17"
			    	$new_date = date("M&\\nb\\sp;j",$this_time);
			    	
			    	//push the new date on the array
			    	array_push($array,array($new_date,"<span class='date_code_{$date['date_code']}'>$description</span>"));
			    	
			    	//keep track of what index we are on now
			    	$array_index_counter++;
			    	
			    	$last_time = $this_time;
		    		$previous_date_was_modified = false;
		    	}
		    	$last_id = $date['id'];
		    	
	    	}
		}//end foreach
		
		if($calendars_assoc_array[$calendar_id]['active'] != 'Y')
		{//if a calendar is not active, put some special markup in to make sure everyone knows it's not official
			array_unshift($array,array("","<span style=\"color: #660000; font-weight: bold;\">This calendar is preliminary, it is not an official calendar.</span>"));
		}
		if($calendar_name_as_first_row)
		{//add the calendar name in the first table row
			array_unshift($array,array(""=>"","<span class='calendar_list_title'>{$calendars_assoc_array[$calendar_id]['calendar_name']}</span>"=>""));
		}

	
		return $array;
	}
	/**
	 * returns the last day of a given semester as a string in the mysql date format
	 */
	public function getLastDay($calendar_id)
	{
		$query = "SELECT max(date) as last_day FROM dates WHERE calendar_id = '$calendar_id'";
		$date_array = $this->database_connection->getQueryArray($query);
	
		return $date_array[0]['last_day'];
	}
	public function getCurrentCalendar()
	{
		$query = "SELECT min(calendar_id) calendar FROM calendars WHERE end_date > curdate()";
		$calendar_array = $this->database_connection->getQueryArray($query);
		return $calendar_array[0]['calendar'];
	}
}

?>