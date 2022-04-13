<?php

class FuncTiming {
	var $timers = array();
	var $start;
	function __construct() {
		$this->start = microtime(true);
	}
	function __destruct() {
		$timeArr = array();
		foreach( $this->timers as $name=>$t) {
			$timeArr[$name] = $t[1];
		}
		array_multisort( $timeArr, SORT_DESC, $this->timers );
		echo "<br>total time: ". ( microtime(true) - $this->start );
		//arsort( $this->timers );
		echo "<pre>";
		foreach( $this->timers as $name=>$t) {
			echo sprintf("%s %.4f %d %.6f\n", $name, $t[1], $t[0], $t[1] / $t[0]);
		}

		//var_dump( $this->timers );
		echo "</pre>";
	}

}
$_funcTimer = new FuncTiming();

class FuncTimer {
	var $name;
	var $start;
	function __construct( $name ) {
		$this->name = $name;
		$this->start = microtime( true );
	}
	function __destruct() {
		global $_funcTimer;
		if( !isset( $_funcTimer->timers[ $this->name ] ) ) {
			$_funcTimer->timers[ $this->name ] = array(0, 0);
		}
		++$_funcTimer->timers[ $this->name ][0];
		$_funcTimer->timers[ $this->name ][1] += microtime( true ) - $this->start;
	}
}


?>