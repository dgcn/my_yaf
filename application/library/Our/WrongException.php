<?php
/**
 * Created by PhpStorm.
 * User: jinbosc16514965
 * Date: 2020/1/6
 * Time: 10:17
 */

namespace Our;

class WrongException extends \Exception
{
    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, 40001+$code);
    }
}