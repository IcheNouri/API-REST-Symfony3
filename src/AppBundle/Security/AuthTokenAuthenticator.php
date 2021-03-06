<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 11/06/2017
 * Time: 21:37
 */

namespace AppBundle\Security;


use Psr\Log\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Http\HttpUtils;

class AuthTokenAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{

    const TOKEN_VALIDITY_DURATION = 12 * 3600;
    private $httpUtils;

    public function __construct(HttpUtils $httpUtils)
    {
        $this->httpUtils = $httpUtils;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof AuthTokenUserProvider) {
            throw new InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of AuthTokenUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $authTokenHeader = $token->getCredentials();
        $authToken = $userProvider->getAuthToken($authTokenHeader);

        if (!$authToken || !$this->isValidToken($authToken))
            throw new BadCredentialsException("Invalid authentication token");

        $user = $authToken->getUser();

        $pre = new PreAuthenticatedToken($user, $authToken, $providerKey, $user->getRoles());
        $pre->setAuthenticated(true);
        return$pre;
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken  && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $providerKey)
    {
        $targetUrl = '/auth-tokens';
        if ($request->getMethod() === 'POST' && $this->httpUtils->checkRequestPath($request, $targetUrl))
            return;

        $authTokenHeader = $request->headers->get('X-Auth-Token');
        if (!$authTokenHeader)
            throw new BadCredentialsException("X-Auth-Token header is required");

        return new PreAuthenticatedToken('anon.', $authTokenHeader, $providerKey);

    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw $exception;
    }

    private function isValidToken($authToken)
    {
        return (time() - $authToken->getCreatedDate()->getTimesTamp() < self::TOKEN_VALIDITY_DURATION);
    }
}