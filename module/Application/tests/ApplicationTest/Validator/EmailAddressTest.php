<?php
/**
 * Created by PhpStorm.
 * User: fred
 * Date: 09/03/16
 * Time: 15:13
 */

namespace ApplicationTest\Validator;


use Application\Validator\EmailAddress;

class EmailAddressTest extends \PHPUnit_Framework_TestCase
{

    public function getFixtures()
    {
        return [
            ['toto@example.com', true],
            ['toto@toto@example.com', false],
            ['toto', false],
        ];
    }
    
    /**
     * @dataProvider getFixtures()
     */
    public function testAddress($address, $isValid)
    {
        $validator = new EmailAddress();
        
        $this->assertEquals($isValid, $validator->isValid($address));
    }
}