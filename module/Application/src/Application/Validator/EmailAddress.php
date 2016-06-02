<?php
/**
 * Created by PhpStorm.
 * User: fred
 * Date: 09/03/16
 * Time: 15:12
 */

namespace Application\Validator;


class EmailAddress
{
    public function isValid($address)
    {
        return preg_match("/^[^@]+@[^@]+$/", $address);
    }
}