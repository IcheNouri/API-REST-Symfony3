<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 04/06/2017
 * Time: 17:39
 */

namespace AppBundle\Controller;

use AppBundle\Entity\AuthToken;
use AppBundle\Entity\Credentials;
use AppBundle\Form\Type\CredentialsType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AuthTokenController extends Controller
{
    /**
     * @param Request $request
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"auth-token"})
     * @Rest\Post("/auth-tokens")
     */
    public function postAuthTokenAction(Request $request) {

        $credentials = new Credentials();
        $form = $this->createForm(CredentialsType::class, $credentials);
        $form->submit($request->request->all());

        if(!$form->isValid())
            return $form;

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Users')->findOneByEmail($credentials->getLogin());

        if (empty($user))
            return $this->invalidCredentials();

        $encoder = $this->get('security.password_encoder');
        if ($encoder->isPasswordValid($user, $credentials->getPassword())) {
            $authToken = new AuthToken();
            $authToken->setValue(base64_encode(random_bytes(50)));
            $authToken->setUser($user);
            $authToken->setCreatedDate(new \DateTime("now"));

            $em->persist($authToken);
            $em->flush();

            return $authToken;
        }
        else
            return $this->invalidCredentials();
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"auth-token"})
     * @Rest\DELETE("/auth-tokens/{id}")
     */
    public function deleteAuthTokenAction(Request $request) {
        $em = $this->get('doctrine.orm.entity_manager');
        $auth_token = $em->getRepository('AppBundle:AuthToken')->find($request->get('id'));

        if (!$auth_token)
            return $this->authTokenNotFound();

        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($connectedUser->getId() === $auth_token->getUser()->getId()) {
            $em->remove($auth_token);
            $em->flush();
        }
        else
            return $this->authTokenNotFound();
    }

    private function invalidCredentials()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('User not found');
    }

    private function authTokenNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('auth token not found');
    }

}