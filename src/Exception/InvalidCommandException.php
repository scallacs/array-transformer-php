<?php
namespace ArrayConverter\Exception;

class InvalidCommandException extends ArrayConverterException {

    public function __construct($cmdId) {
        $this->message = 'Invalid command: ' . $cmdId;
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
