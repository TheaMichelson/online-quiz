<?php
class Logger {
    private $log_file;

    public function __construct() {
        $this->log_file = '../logs/app.log';
    }

    public function log($message) {
        $time = date('Y-m-d H:i:s');
        $log_entry = "[" . $time . "] " . $message . PHP_EOL;
        file_put_contents($this->log_file, $log_entry, FILE_APPEND);
    }
}
?>
