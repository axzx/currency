<?php

namespace App\Tests\Controller;

use App\Entity\Token;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class WebTestCase extends BaseWebTestCase
{
    /** @var KernelBrowser obiekt klienta */
    protected $client = null;

    /** @var Token */
    private $user;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * Zwraca zalogowanego użytkownika.
     *
     * @return Token zalogowany użytkownik
     */
    public function getUser(): Token
    {
        return $this->user;
    }

    protected function getUserToken(string $username = 'app1')
    {
        return $this->client->getContainer()
            ->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => $username]);
    }
}
