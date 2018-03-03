<?php
/**
 * Created by memoria.
 * User: decebal.dobrica
 * Date: 2/19/18
 */

namespace MsgPackPhp;

class Response
{
    public $message = null;
    public $errors = null;

    public function __construct()
    {
    }

    public function setMessage($result)
    {
        $this->message = $result;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
