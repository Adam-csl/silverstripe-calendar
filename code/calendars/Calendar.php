<?php
/**
 * Calendar Model
 * The calendar serves as a holder for events, but events can exist as instances on their own.
 * 
 * @package calendar
 * @subpackage calendars
 */
class Calendar extends DataObject {
	
	static $db = array(
		'Title' => 'Varchar',
	);
	
	static $has_many = array(
		'Events' => 'Event'
	);

	static $default_sort = 'Title';

	public static $summary_fields = array(
		'Title' => 'Title',
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		//Events shouldn't be editable from here by default
		$fields->removeByName('Events');
		return $fields;
	}

}
