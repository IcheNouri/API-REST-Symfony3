<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 02/06/2017
 * Time: 14:51
 */

namespace AppBundle\Controller\Place;


use AppBundle\Entity\Theme;
use AppBundle\Form\Type\ThemeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class ThemeController extends Controller
{
    /**
     * @param Request $request
     * @Rest\View(serializerGroups={"theme"})
     * @Rest\Get("/places/{id}/themes")
     * @return static
     */
    public function getThemeAction(Request $request) {
        $place = $this->get("doctrine.orm.entity_manager")
                    ->getRepository("AppBundle:Place")
                    ->find($request->get("id"));

        if (empty($place))
            return $this->PlaceNotFound();

        return $place->getThemes();
    }

    /**
     * @param Request $request
     * @Rest\View(statusCode= 201, serializerGroups={"theme"})
     * @Rest\Post("/places/{id}/themes")
     */
    public function postThemeAction(Request $request) {
        $place = $this->get("doctrine.orm.entity_manager")
                ->getRepository("AppBundle:Place")
                ->find($request->get('id'));

        if (empty($place))
            return $this->PlaceNotFound();

        $theme = new Theme();
        $theme->setPlace($place);
        $form = $this->createForm(ThemeType::class, $theme);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($theme);
            $em->flush();
            return $theme;
        }
        else
            return $form;
    }

    private function PlaceNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Place not found');
    }
}