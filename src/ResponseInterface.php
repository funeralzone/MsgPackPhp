<?php
/**
 * Created by MsgPackPhp.
 * User: decebal
 * Date: 3/9/18
 */

namespace MsgPackPhp;

interface ResponseInterface
{
    public function setMessage($result);

    public function getMessage();

    public function setErrors($errors);

    public function getErrors();
}
