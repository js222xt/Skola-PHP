<?php

namespace view;

/**
 * Is it allowed to print/calculate date and use the fix-methods or 
 * should they be in a separate class in model?
 */
class DateTimeStamp {

	/**
	 * @return string DateTimeString
	 */
	public function getStamp() {
		return "<p>" . $this->fixDay(date('l')) . ", den " . date("j ") . $this->fixMonth(date('m')) . 
			" år " . date("Y") . ". Klockan är [" . date("H:i:s") . "].</p>";
	}
	
	/**
	 * @param  date $monthEng example 01
	 * @return string $correctMonth Month in swedish
	 */
	private function fixMonth($monthEng) {
		$monthNames = array(
			'Janurari',
			'Februari',
			'Mars',
			'April',
			'Maj',
			'Juni',
			'Juli',
			'Augusti',
			'September',
			'Oktober',
			'November',
			'December'
			);

		$correctMonth = $monthNames[$monthEng-1];

		return $correctMonth;
	}

	/**
	 * @param  date $dayEng 
	 * @return string $correctDay Day in swedish
	 */
	 private function fixDay($dayEng) {
		$dayNames = array(
			'Monday'=>'Måndag',
			'Tuesday'=>'Tisdag',
			'Wednesday'=>'Onsdag',
			'Thursday'=>'Torsdag',
			'Friday'=>'Fredag',
			'Saturday'=>'Lördag',
			'Sunday'=>'Söndag'
			);

		$correctDay = $dayNames[$dayEng];

		return $correctDay;
	}
}