<?php
function write_log($log_file,$type, $log_item) {
		if (is_array($log_item)) {
			foreach ($log_item AS $name=>$value) {
				$log_item_temp_arr[] = $name . ': ' . $value;
			}
			$log_line = date('Y-m-d H:i:s') . ' [' . ucfirst($type) . '] ' . implode('; ', $log_item_temp_arr);
		}
		else {
			$log_line = date('Y-m-d H:i:s') . ' [' . ucfirst($type) . '] ' . $log_item;
		}
		if ($type == 'stats') echo "\n";
		echo $log_line . "\n";
		$fh = fopen($log_file, 'a') or die("Can't open log file\n");
		fwrite($fh, $log_line . "\n");
		fclose($fh);
}
?>
