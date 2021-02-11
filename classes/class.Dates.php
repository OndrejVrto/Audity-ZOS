<?php
/**
 * This class encapsulates various date and time functionality.
 *
 * @version 2.0
 * @author Jeff Williams
 */
class Dates {

	// Default time zone used as a default parameter for date functions
	// Time zones are listed here: http://php.net/manual/en/timezones.php
	const DEFAULT_TIMEZONE = 'UTC'; // UTC±00:00 Coordinated Universal Time
	// const DEFAULT_TIMEZONE = 'America/New_York';    // Eastern
	// const DEFAULT_TIMEZONE = 'America/Chicago';     // Central
	// const DEFAULT_TIMEZONE = 'America/Denver';      // Mountain
	// const DEFAULT_TIMEZONE = 'America/Phoenix';     // Mountain no DST
	// const DEFAULT_TIMEZONE = 'America/Los_Angeles'; // Pacific

	/**
	 * Converts any English textual datetimes into a date object
	 *
	 * @param string $date Date string
	 * @param string $timezone [OPTIONAL] Default timezone
	 * @param boolean $forceFixDate [OPTIONAL] Force fixing all dates with dashes
	 * (this might be incompatible with some countries and may default to false)
	 * @return date Date if valid and false if not
	 */
	public static function convertToDate($date,
		$timezone = self::DEFAULT_TIMEZONE, $forceFixDate = true) {

		// If the input was not a DateTime object
		if (!$date instanceof DateTime) {

			// Set the timezone to default
			date_default_timezone_set($timezone);

			// If we need to use the date fix for United States dates
			// and there are no characters as in 02-JAN-03 then...
			if (($forceFixDate || self::isTimeZoneInCountry($timezone, 'US')) &&
				is_string($date) && !preg_match('/[a-z]/i', $date)) {

				// U.S. dates with '-' do not convert correctly so replace them with '/'
				$datevalue = self::fixUSDateString($date);

			} else { // No fix needed...

				// Use the date passed in
				$datevalue = $date;

			}

			// Convert the string into a linux time stamp
			$timestamp = strtotime($datevalue);

			// If this was a valid date
			if ($timestamp) {

				// Convert the UNIX time stamp into a date object
				$date = DateTime::createFromFormat('U', $timestamp);

			} else { // Not a valid date

				// This was not a valid date
				$date = false;

			}
		}

		// Make sure the date isn't a converted "0000-00-00 00:00:00"
		if ($date instanceof DateTime) {
			if (intval($date->format('Y')) <= 0) {
				$date = false;
			}
		}

		// Return the date object or false if invalid
		return $date;

	}

	/**
	 * Add or subtract an interval of time to a date
	 *
	 * @param DateTime/String $date1 Date to be modified
	 * @param Integer $interval The amount of time
	 * @param String $type The DateInterval format
	 * @param Boolean $is_time TRUE if interval is hours, minutes, or seconds
	 * @return DateTime Returns the new date
	 */
	private static function modifyDateInterval($date, $interval, $type, $is_time) {
		// Let's make sure we have a date
		if ($date instanceof DateTime) {
			// Don't make changes to the original date
			$new_date = clone $date;
		} else {
			// Convert the date
			$new_date = self::convertToDate($date);
		}
		// If we have a valid date
		if ($new_date) {

			// Is this an hour, minute, or second?
			if ($is_time) {
				$pre = 'PT';
			} else { // This is a year, month, or day
				$pre = 'P';
			}

			// If the interval of time is negative
			if (intval($interval) < 0) {
				// Subtract the interval
				$new_date->sub(new DateInterval($pre . strval($interval * -1) . $type));
			} else {
				// Add the interval
				$new_date->add(new DateInterval($pre . strval($interval) . $type));
			}
		}
		return $new_date;
	}

	/**
	 * Add days to a date
	 *
	 * @param DateTime/String $date1 Date to be modified
	 * @param Integer $days The number of days to add (negative subtracts)
	 * @return DateTime Returns the new date
	 */
	public static function addDays($date, $days) {
		return self::modifyDateInterval($date, intval($days), 'D', false);
	}

	/**
	 * Add hours to a date
	 *
	 * @param DateTime/String $date1 Date to be modified
	 * @param Integer $hours The number of hours to add (negative subtracts)
	 * @return DateTime Returns the new date
	 */
	public static function addHours($date, $hours) {
		return self::modifyDateInterval($date, intval($hours), 'H', true);
	}

	/**
	 * Add minutes to a date
	 *
	 * @param DateTime/String $date1 Date to be modified
	 * @param Integer $minutes The number of minutes to add (negative subtracts)
	 * @return DateTime Returns the new date
	 */
	public static function addMinutes($date, $minutes) {
		return self::modifyDateInterval($date, intval($minutes), 'M', true);
	}

	/**
	 * Add months to a date
	 *
	 * @param DateTime/String $date1 Date to be modified
	 * @param Integer $months The number of months to add (negative subtracts)
	 * @return DateTime Returns the new date
	 */
	public static function addMonths($date, $months) {
		return self::modifyDateInterval($date, intval($months), 'M', false);
	}

	/**
	 * Add seconds to a date
	 *
	 * @param DateTime/String $date1 Date to be modified
	 * @param Integer $seconds The number of seconds to add (negative subtracts)
	 * @return DateTime Returns the new date
	 */
	public static function addSeconds($date, $seconds) {
		return self::modifyDateInterval($date, intval($seconds), 'S', true);
	}

	/**
	 * Add years to a date
	 *
	 * @param DateTime/String $date1 Date to be modified
	 * @param Integer $years The number of years to add (negative subtracts)
	 * @return DateTime Returns the new date
	 */
	public static function addYears($date, $years) {
		return self::modifyDateInterval($date, intval($years), 'Y', false);
	}

	/**
	 * Get the interval of time between two dates
	 *
	 * @param DateTime/String $date1 First date
	 * @param DateTime/String $date2 Second date
	 * @return interval Returns an interval object
	 */
	private static function differenceInterval($date1, $date2) {
		// Make sure our dates are DateTime objects
		$datetime1 = self::convertToDate($date1);
		$datetime2 = self::convertToDate($date2);

		// If both variables were valid dates...
		if ($datetime1 && $datetime2) {

			// Get the time interval between the two dates
			return $datetime1->diff($datetime2);

		// The dates were invalid
		} else {

			// Return false
			return false;
		}
	}

	/**
	 * Get the number of days between two dates
	 *
	 * @param DateTime/String $date1 First date
	 * @param DateTime/String $date2 Second date
	 * @return integer Returns the number of days or false if invalid dates
	 */
	public static function differenceDays($date1, $date2) {
		// Get the difference between the two dates
		$interval = self::differenceInterval($date1, $date2);
		if ($interval) {
			// Return the number of days
			return $interval->days;
		} else {
			// The passed in values were not dates
			return false;
		}
	}

	/**
	 * Get the number of hours between two dates
	 *
	 * @param DateTime/String $date1 First date
	 * @param DateTime/String $date2 Second date
	 * @return integer Returns the number of hours or false if invalid dates
	 */
	public static function differenceHours($date1, $date2) {
		// Get the difference between the two dates
		$interval = self::differenceInterval($date1, $date2);
		if ($interval) {
			// Return the number of hours
			return ($interval->days * 24) + $interval->h;
		} else {
			// The passed in values were not dates
			return false;
		}
	}

	/**
	 * Get the number of minutes between two dates
	 *
	 * @param DateTime/String $date1 First date
	 * @param DateTime/String $date2 Second date
	 * @return integer Returns the number of minutes or false if invalid dates
	 */
	public static function differenceMinutes($date1, $date2) {
		// Get the difference between the two dates
		$interval = self::differenceInterval($date1, $date2);
		if ($interval) {
			// Return the number of minutes
			return ((($interval->days * 24) + $interval->h) * 60) + $interval->i;
		} else {
			// The passed in values were not dates
			return false;
		}
	}

	/**
	 * Get the number of months between two dates
	 *
	 * @param DateTime/String $date1 First date
	 * @param DateTime/String $date2 Second date
	 * @return integer Returns the number of months or false if invalid dates
	 */
	public static function differenceMonths($date1, $date2) {
		// Get the difference between the two dates
		$interval = self::differenceInterval($date1, $date2);
		if ($interval) {
			// Return the number of months
			return ($interval->y * 12) + $interval->m;
		} else {
			// The passed in values were not dates
			return false;
		}
	}

	/**
	 * Get the number of seconds between two dates
	 *
	 * @param DateTime/String $date1 First date
	 * @param DateTime/String $date2 Second date
	 * @return integer Returns the number of seconds or false if invalid dates
	 */
	public static function differenceSeconds($date1, $date2) {
		// Get the difference between the two dates
		$interval = self::differenceInterval($date1, $date2);
		if ($interval) {
			// Return the number of minutes
			return ((((($interval->days * 24) + $interval->h) * 60) +
				$interval->i) * 60)  + $interval->s;
		} else {
			// The passed in values were not dates
			return false;
		}
	}

	/**
	 * Get the number of years between two dates
	 *
	 * @param DateTime/String $date1 First date
	 * @param DateTime/String $date2 Second date
	 * @return integer Returns the number of years or false if invalid dates
	 */
	public static function differenceYears($date1, $date2) {
		// Get the difference between the two dates
		$interval = self::differenceInterval($date1, $date2);
		if ($interval) {
			// Return the number of years
			return $interval->y;
		} else {
			// The passed in values were not dates
			return false;
		}
	}

	/**
	 * U.S. dates with - do not convert correctly in PHP so replace them with /
	 * (See comments http://php.net/manual/en/function.strtotime.php)
	 *
	 * @param string $date A date string
	 * @return string If the passed in value is a string, returns fixed date
	 *	               otherwise return the value passed in to the function
	 */
	public static function fixUSDateString($date) {
		// If the passed in value is a string and there are not alpha
		// characters that hold month names (as in 02-JAN-03) then...
		if (is_string($date) && !preg_match('/[a-z]/i', $date)) {

			// Replace '-' with '/'
			$return = str_replace('-', '/', $date);

		} else { // No fix needed...

			// Use the date passed in
			$return = $date;

		}
		return $return;
	}

	/**
	 * Formats a date
	 *
	 * @param string/date $date A string or date object
	 * @param string $format A valid PHP date format
	 *                       http://php.net/manual/en/function.date.php
	 *                       'Y-m-d H:i:s' is the MySQL date format
	 * @param string $timezone [OPTIONAL] The timezone to use
	 * @return string The date formatted as a string or false if not a date
	 */
	public static function formatDate($date, $format, $timezone = self::DEFAULT_TIMEZONE) {
		// Convert the string to a date
		$new_date = self::convertToDate($date, $timezone);

		// If the string was successfully converted into a date
		if ($new_date) {

			// Format it
			$output = $new_date->format($format);

		} else {

			// Return false
			$output = false;

		}
		return $output;
	}

	/**
	 * Returns the current year
	 *
	 * @param string $timezone [OPTIONAL] The timezone to use
	 * @return integer The current year formatted as an integer
	 */
	public static function getCurrentYear($timezone = self::DEFAULT_TIMEZONE) {
		return intval(self::formatDate('Today', 'Y', $timezone));
	}

	/**
	 * Returns the last day of this month or for a given date
	 *
	 * @param string/DateTime $date [OPTIONAL] A date
	 * @return integer The number of the last day of the month
	 */
	public static function getLastDayOfMonth($date = 'Today') {
		$datetime = self::convertToDate($date);
		if ($datetime) {
			$return = intval($datetime->format('t'));
		} else {
			$return = false;
		}
		return $return;
	}

	/**
	 * Determines if a string contains a valid date
	 *
	 * @param string $value The value to inspect
	 * @param boolean $us_fix Fix U.S. dates?
	 * @return boolean TRUE if the value is a date, FALSE if not
	 */
	public static function isDate($value, $timezone = self::DEFAULT_TIMEZONE) {
		return (self::convertToDate($value, $timezone) instanceof DateTime);
	}

	/**
	 * Determines if a time zone is in the specified country
	 *
	 * @param string $timezone Time zone from http://php.net/manual/en/timezones.php
	 * @param string $country The name of the country
	 * @return boolean TRUE if in the country and FALSE if not
	 */
	public static function isTimeZoneInCountry($timezone, $country) {
		// Get an array of all the timezones (for example, in the U.S.)
		$timezone_identifiers = array_map('strtolower',
			DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country));

		// Determine if the passed in time zone is in that list
		return in_array(strtolower(trim($timezone)), $timezone_identifiers);
	}

	/**
	 * Builds a DateTime object from date parts
	 *
	 * @param integer $day [OPTIONAL] The day or if not specified today
	 * @param integer $month [OPTIONAL] The month or if not specified this month
	 * @param integer $year [OPTIONAL] The year or if not specified this year
	 * @return DateTime Returns a DateTime object with the specified date
	 */
	public static function makeDate($day = false, $month = false, $year = false) {
		if ($day === false || $month === false || $year === false) {
			$date_parts  = explode('-', self::convertToDate('Today')->format('d-m-Y'));
			if ($day === false) {
				$day = $date_parts[0];
			}
			if ($month === false) {
				$month = $date_parts[1];
			}
			if ($year === false) {
				$year =  $date_parts[2];
			}
		}
		return self::convertToDate($year . '/' . $month . '/' . $day);
	}

	/**
	 * Returns the current date and time as a DateTime object or formatted string
	 *
	 * @param string $format [OPTIONAL] If specified, will format the date as a string
	 *                       If not specified, returns a DateTime object
	 *	                     (example: 'Y-m-d H:i:s')
	 * @param string $timezone [OPTIONAL] Default timezone
	 * @return DateTime/string The DateTime object or a formatted string
	 */
	public static function now($format = false, $timezone = self::DEFAULT_TIMEZONE) {
		$now = new DateTime();
		$now->setTimeZone(new DateTimeZone($timezone));
		if ($format) {
			return $now->format($format);
		} else {
			return $now;
		}
	}

	/**
	 * Returns the current date (not time) as a DateTime object or formatted string
	 *
	 * @param string $format [OPTIONAL] If specified, will format the date as a string
	 *                       If not specified, returns a DateTime object
	 *	                     (example: 'Y-m-d')
	 * @param string $timezone [OPTIONAL] Default timezone
	 * @return DateTime/string The DateTime object or a formatted string
	 */
	public static function today($format = false, $timezone = self::DEFAULT_TIMEZONE) {
		$today = self::convertToDate('Today', $timezone);
		if ($format) {
			return $today->format($format);
		} else {
			return $today;
		}
	}

}