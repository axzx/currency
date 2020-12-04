<?php

namespace App\Tests\Controller\v1;

use App\Tests\Controller\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private $em;

    protected function setUp(): void
    {
        parent::setUp();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testRegisterUnauthorized()
    {
        $this->client->request('POST', '/api/v1/user/register');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    public function testRegister()
    {
        $params = [];

        $this->client->request('POST', '/api/v1/user/register', $params, [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.$this->getUserToken(),
        ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}
