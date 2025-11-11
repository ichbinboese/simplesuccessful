<?php

namespace App\Tests\Security;

use App\Security\EnvUserProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class EnvUserProviderTest extends TestCase
{
    public function testReturnsUserWhenConfigured(): void
    {
        $hash = password_hash('secret-password', PASSWORD_BCRYPT);
        $provider = new EnvUserProvider('admin@example.test', $hash, ['ROLE_ADMIN']);

        $user = $provider->loadUserByIdentifier('admin@example.test');

        $this->assertSame('admin@example.test', $user->getUserIdentifier());
        $this->assertSame($hash, $user->getPassword());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function testThrowsWhenNotConfigured(): void
    {
        $provider = new EnvUserProvider(null, null);

        $this->expectException(UserNotFoundException::class);
        $provider->loadUserByIdentifier('someone@example.test');
    }
}
