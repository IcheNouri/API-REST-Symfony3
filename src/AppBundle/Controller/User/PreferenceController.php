<?php

namespace AppBundle\Controller\User;

use AppBundle\Entity\Preference;
use AppBundle\Form\Type\PreferenceType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PreferenceController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"preference"})
     * @Rest\Get("/users/{id}/preferences")
     */
    public function getPreferenceAction(Request $request) {

        $user = $this->get("doctrine.orm.entity_manager")
                    ->getRepository("AppBundle:Users")
                    ->find($request->get("id"));

        if (empty($user))
            return $this->UserNotFound();

        return $user->getPreferences();
    }

    /**
     * @param Request $request
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"preference"})
     * @Rest\Post("/users/{id}/preferences")
     */
    public function postPreferenceAction(Request $request) {
        $user = $this->get("doctrine.orm.entity_manager")
            ->getRepository("AppBundle:Users")
            ->find($request->get("id"));

        if (empty($user))
            return $this->UserNotFound();

        $preference = new Preference();
        $preference->setUser($user);

        $form = $this->createForm(PreferenceType::class, $preference);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($preference);
            $em->flush();
            return $preference;
        }
        else
            return $form;
    }

    private function UserNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('User not found');
    }
}