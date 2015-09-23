<?php

namespace view;

class DateTimeView {


	public function show() {

		$t=time();
		$timeString = '<p>' . date("l",$t) . ', the ' . date("jS",$t) . ' of ' . date("F Y",$t) . ', The time is ' . date("H:i:s",$t) . '</p>';

		return $timeString;
	}
}