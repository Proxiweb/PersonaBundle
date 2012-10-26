<?php
namespace Proxiweb\Bundle\PersonaBundle\Security\Authentication\Provider;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Proxiweb\Bundle\PersonaBundle\Security\Authentication\Token\PersonaUserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use FOS\UserBundle\Security\EmailUserProvider;

class PersonaAuthenticationProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(EmailUserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        
        if ($user) {
            $authenticatedToken = new PersonaUserToken($user->getRoles());
            $authenticatedToken->setUser($user);
            $authenticatedToken->setAuthenticated(true);
            
            return $authenticatedToken;
        }

        throw new AuthenticationException('The Persona authentication failed.'.$token->getUsername());
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof PersonaUserToken;
    }
}