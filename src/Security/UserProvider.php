<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Load user by identifier (email or username)
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // Recherchez d'abord l'utilisateur par email
        $user = $this->userRepository->findOneBy(['email' => $identifier]);

        // Si aucun utilisateur n'est trouvé, recherchez par username
        if (!$user) {
            $user = $this->userRepository->findOneBy(['username' => $identifier]);
        }

        // Si aucun utilisateur n'est trouvé, lancez une exception
        if (!$user) {
            throw new UserNotFoundException("Utilisateur avec l'identifiant $identifier non trouvé.");
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        // Rafraîchissez l'utilisateur en utilisant son identifiant unique
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        // Assurez-vous que la classe prise en charge est celle de l'entité User
        return $class === \App\Entity\User::class;
    }
}
