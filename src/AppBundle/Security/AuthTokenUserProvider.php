<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 11/06/2017
 * Time: 21:27
 */

namespace AppBundle\Security;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AuthTokenUserProvider implements UserProviderInterface
{
    protected $userRepository;
    protected $authTokenRepository;

    public function __construct(EntityRepository $authTokenRepository, EntityRepository $userRepository)
    {
        $this->authTokenRepository = $authTokenRepository;
        $this->userRepository = $userRepository;
    }

    public function getAuthToken($authTokenHeader) {
        return $this->authTokenRepository->findOneByValue($authTokenHeader);
    }

    public function loadUserByUsername($username)
    {
        return $this->userRepository->findByEmail();
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'AppBundle\Entity\User' === $class;
    }
}