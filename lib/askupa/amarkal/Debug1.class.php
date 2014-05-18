<?php

class Debug {
	
	function benchmark( $obj, $func, $repeat = 1 ) {
		
		ob_start();
		
		$time = microtime(true);
		for($i = 0; $i < $repeat; $i++) {
			$obj->$func;
		}
		$time = microtime(true) - $time;
		
		ob_clean();

		echo "<p>Benchmark running $obj\->$func took $time seconds</p>";
	}
}
