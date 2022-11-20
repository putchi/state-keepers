<?php

namespace Putchi\StateKeepers\Exceptions;

use Exception;
use Throwable;

abstract class CustomException extends Exception {

    /**
     * @var string
     */
    protected string $_type;
    /**
     * @var array
     */
    protected array $_data;
    /**
     * @var string[]
     */
    static protected array $errorTypes = [
        'emergency', // #0
        'alert',     // #1
        'critical',  // #2
        'error',     // #3
        'warning',   // #4
        'notice',    // #5
        'info',      // #6
        'debug',     // #7
        'log',       // #8
    ];

    /**
     * CustomException constructor.
     *
     * @param string|null    $message
     * @param string|null    $type      - the type of the CustomException (see $errorTypes)
     * @param int            $code
     * @param array|null     $data
     * @param Throwable|null $previous
     */
    public function __construct(string $message = null, string $type = null, int $code = 500, array $data = null, Throwable $previous = null) {
        $this->setMessage($message);
        $this->setType($type);
        $this->setCode($code);
        $this->setData($data);

        // make sure everything is assigned properly
        parent::__construct($this->message, $this->code, $previous);
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function setMessage(string $message = null) {
        if (is_null($message) || empty(trim($message))) {
            $this->message = 'Unknown ' . get_called_class();
        } else {
            $this->message = $message;
        }

        return $this;
    }

    /**
     * @param int $code
     * @return $this
     */
    public function setCode(int $code = 500) {
        $this->code = $code;

        return $this;
    }

    /**
     * @param string|null $type
     * @return $this
     */
    public function setType(string $type = null) {
        if (is_null($type) || empty(trim($type)) || !in_array(strtolower(trim($type)), self::$errorTypes)) {
            $this->_type = self::$errorTypes[3]; // #3 = error (default value)
        } else {
            $this->_type = strtolower(trim($type));
        }

        return $this;
    }

    /**
     * Get the type of the exception (e.g: error|notice|info)
     * @return string
     */
    public function getType() {
        return $this->_type ?? self::$errorTypes[3];
    }

    /**
     * @param array|null $data
     * @return $this
     */
    public function setData(array $data = null) {
        if (!isset($data) || empty($data)) {
            $this->_data = [];
        } else {
            if (is_object($data)) {
                $this->_data = json_decode(json_encode($data), true);
            } else if (is_string($data) || is_numeric($data)) {
                $this->_data = [$data];
            } else {
                $this->_data = $data;
            }
        }

        return $this;
    }

    /**
     * @param bool $stringify
     * @return array|false|string
     */
    public function getData(bool $stringify = false) {
        if ($stringify) {
            return json_encode($this->_data);
        }

        return $this->_data;
    }

    /**
     * @return string
     */
    public function __toString() {
        return "exception '" . get_class($this) . "' with message '{$this->message}' in {$this->file}:{$this->line}\n"
        . "Type:\n{$this->getType()}\n"
        . "Data:\n{$this->getData(true)}\n"
        . "Stack trace:\n{$this->getTraceAsString()}\n"
        . '<---------------------------------------------------------[O.o]--------------------------------------------------------->';
    }
}
