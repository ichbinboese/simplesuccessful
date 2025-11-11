<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Allows logging in with a single admin account defined via environment variables.
 */
class EnvUserProvider implements UserProviderInterface
{
    private ?string $email;
    private ?string $passwordHash;
    /** @var string[] */
    private array $roles;

    /**
     * @param string[] $roles
     */
    public function __construct(?string $email, ?string $passwordHash, array $roles = [])
    {
        $this->email = $email ? strtolower(trim($email)) : null;
        $this->passwordHash = $passwordHash ? trim($passwordHash) : null;
        $this->roles = array_values(array_filter($roles)) ?: ['ROLE_ADMIN'];
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        if (!$this->isConfigured() || strtolower($identifier) !== $this->email) {
            throw new UserNotFoundException(sprintf('Env admin "%s" not found.', $identifier));
        }

        return $this->createUser();
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$this->supportsClass($user::class)) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s".', $user::class));
        }

        if (!$this->isConfigured()) {
            throw new UserNotFoundException('Env admin not configured.');
        }

        return $this->createUser();
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class || is_subclass_of($class, User::class);
    }

    private function isConfigured(): bool
    {
        return !empty($this->email) && !empty($this->passwordHash);
    }

    private function createUser(): User
    {
        return (new User())
            ->setEmail((string) $this->email)
            ->setPassword((string) $this->passwordHash)
            ->setRoles($this->roles);
    }
}
