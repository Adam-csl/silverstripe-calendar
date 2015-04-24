<?php
/**
 * Calendar Helper
 * Helper class for calendar related calculations
 *
 * @package calendar
 * @subpackage core
 */
class CalendarHelper {

	
	
	/**
	 * Get all coming public events
	 */
	static function coming_events($from = false){
		$time = ($from ? strtotime($from) : mktime(0, 0, 0, date('m'), date('d'), date('Y')));
		$sql = "(StartDateTime >= '".date('Y-m-d', $time)." 00:00:00')";
		$events = PublicEvent::get()->where($sql);
		$array = new ArrayList();
		foreach ($events as $event) {
			if ($event->canView()){
				$array->push($event);
			}
		}
		return $array;
	}

	/**
	 * Get all coming public events - with optional limit
	 */
	static function coming_events_limited($from=false, $limit=30){
		$events = self::coming_events($from)->limit($limit);
		$array = new ArrayList();
		foreach ($events as $event) {
			if ($event->canView()){
				$array->push($event);
			}
		}
		return $array;
	}
	
	/**
	 * Get all past public events
	 */
	static function past_events(){
		$events = PublicEvent::get()
			->filter(array(
					'StartDateTime:LessThan' => date('Y-m-d',time())
				)
			);
		
		$array = new ArrayList();
		foreach ($events as $event) {
			if ($event->canView()){
				$array->push($event);
			}
		}
		return $array;
	}

	/**
	 * Get all events
	 */
	static function all_events(){
		$events = PublicEvent::get();
		$array = new ArrayList();
		foreach ($events as $event) {
			if ($event->canView()){
				$array->push($event);
			}
		}
		return $array;
	}

	/**
	 * Get all events - with an optional limit
	 */
	static function all_events_limited($limit = 30){
		$events = self::all_events()->limit($limit);
		$array = new ArrayList();
		foreach ($events as $event) {
			if ($event->canView()){
				$array->push($event);
			}
		}
		return $array;
	}

	/**
	 * Get events for a specific month
	 * Format: 2013-07
	 * @param type $month
	 */
	static function events_for_month($month){
		$nextMonth = strtotime('last day of this month', strtotime($month));
		
		$currMonthStr = date('Y-m-d',strtotime($month));
		$nextMonthStr = date('Y-m-d',$nextMonth);
		
		$sql =	"(StartDateTime BETWEEN '$currMonthStr' AND '$nextMonthStr')" .
						" OR " .
						"(EndDateTime BETWEEN '$currMonthStr' AND '$nextMonthStr')";


		$events = PublicEvent::get()
			->where($sql);
		
		$array = new ArrayList();
		foreach ($events as $event) {
			if ($event->canView()){
				$array->push($event);
			}
		}
		return $array;
	}
}
