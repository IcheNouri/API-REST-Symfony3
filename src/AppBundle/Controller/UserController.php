<?php
/**
 * Created by PhpStorm.
 * Users: Nouri
 * Date: 10/04/2017
 * Time: 23:57
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Users;
use AppBundle\Form\Type\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @Rest\View(serializerGroups={"Users"})
     * @Rest\Get("/users")
     * @return array
     */
    public function getUsersAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:Users")->findAll();

        return $users;
    }

    /**
     * @param Request $request
     * @Rest\View(serializerGroups={"Users"})
     * @Rest\Get("/users/{id}")
     * @return JsonResponse
     */
    public function getUserAction($id, Request $request)
    {

        $user = $this->get("doctrine.orm.entity_manager")
            ->getRepository("AppBundle:Users")
            ->find($id);

        if (empty($user))
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('User not found');

        return $user;
    }

    /**
     * @param Request $request
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"Users"})
     * @Rest\Post("/users")
     * @return Users|\Symfony\Component\Form\Form
     */
    public function postUsersAction(Request $request)
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user, ['validation_groups' => ['Default', 'New']]);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $user;
        } else
            return $form;

    }

    /**
     * @param $id
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"Users"})
     * @Rest\DELETE("/users/{id}")
     */
    public function removeUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("AppBundle:Users")->find($id);

        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }

    /**
     * @Rest\View(serializerGroups={"Users"})
     * @Rest\PUT("/users/{id}")
     * @param Request $request
     * @return object|\Symfony\Component\Form\Form
     */
    public function updateUserAction(Request $request)
    {
        return $this->updateUser($request, true);
    }

    /**
     * @param Request $request
     * @Rest\View(serializerGroups={"Users"})
     * @Rest\Patch("/users/{id}")
     * @return object|\Symfony\Component\Form\Form
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    private function updateUser(Request $request, $clearMissing)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Users')
            ->find($request->get('id'));

        if (empty($user)) {
            return $this->UserNotFound();
        }
        if ($clearMissing) {
            $options = ['validation_groups' => ['Default', 'FullUpdate']];
        }
        else
            $options = [];

        $form = $this->createForm(UsersType::class, $user, $options);
        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            if (!empty($user->getPlainPassword())) {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
            }
            $em->merge($user);
            $em->flush();
            return $user;
        }
        return $form;
    }

    /**
     * @param Request $request
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/users/{id}/suggestions")
     */
    public function getSuggestionsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("AppBundle:Users")->find($request->get('id'));

        if (empty($user))
            return $this->UserNotFound();

        $places = $em->getRepository("AppBundle:Place")->findAll();
        $suggestions = [];
        foreach ($places as $place) {
            if ($user->preferencesMatch($place->getThemes())) {
                $suggestions[] = $place;
            }
        }

        return $suggestions;
    }

    private function UserNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("User not found");
    }
}