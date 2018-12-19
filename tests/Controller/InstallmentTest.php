<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InstallmentTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        /**
         * Should return a status code of 302 when there is NOT
         * a user stored in the Session.
         */
        $client->request('GET', '/installments');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}