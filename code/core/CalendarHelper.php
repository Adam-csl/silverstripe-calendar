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
		if (!Permission::check('ACCESS_PRIVATE_EVENT')){
			
			$sql = "(StartDateTime >= '".date('Y-m-d', $time)." 00:00:00' AND Private = '0')";
		}
		$events = PublicEvent::get()->where($sql);
		
		return $events;
	}

	/**
	 * Get all coming public events - with optional limit
	 */
	static function coming_events_limited($from=false, $limit=30){
		$events = self::coming_events($from)->limit($limit);
		if (!Permission::check('ACCESS_PRIVATE_EVENT')){
			$events = self::coming_events($from)->filter(array("Private" => "0"))->limit($limit);
		}
		return $events;
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
		
		if (!Permission::check('ACCESS_PRIVATE_EVENT')){
			$events = PublicEvent::get()
			->filter(array(
					'StartDateTime:LessThan' => date('Y-m-d',time()),
					"Private" => "0"
				)
			);
		}
		return $events;
	}

	/**
	 * Get all events
	 */
	static function all_events(){
		$events = PublicEvent::get();
		if (!Permission::check('ACCESS_PRIVATE_EVENT')){
			$events = PublicEvent::get()->filter(array("Private" => "0"));
		}
		return $events;
	}

	/**
	 * Get all events - with an optional limit
	 */
	static function all_events_limited($limit = 30){
		$events = self::all_events()->limit($limit);
		if (!Permission::check('ACCESS_PRIVATE_EVENT')){
			$events = self::all_events()->filter(array("Private" => "0"))->limit($limit);
		}
		return $events;
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

		if (!Permission::check('ACCESS_PRIVATE_EVENT')){
			$sql =	"(StartDateTime BETWEEN '$currMonthStr' AND '$nextMonthStr' AND Private ='0')" .
						" OR " .
						"(EndDateTime BETWEEN '$currMonthStr' AND '$nextMonthStr' AND Private ='0')";
		}
		$events = PublicEvent::get()
			->where($sql);
		
		return $events;
	}
}