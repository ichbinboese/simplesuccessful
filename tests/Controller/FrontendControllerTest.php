<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontendControllerTest extends WebTestCase
{
    public function testOnepagerResponds(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testAdminSpaRouteFallsBackToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin/app');

        $this->assertResponseStatusCodeSame(302);
        $this->assertTrue(str_contains($client->getResponse()->headers->get('location') ?? '', '/login'));
    }
}
