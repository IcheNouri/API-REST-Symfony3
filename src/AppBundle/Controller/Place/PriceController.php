<?php

namespace AppBundle\Controller\Place;

use AppBundle\Entity\Price;
use AppBundle\Form\Type\PriceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class PriceController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"price"})
     * @Rest\Get("/places/{id}/prices")
     */
    public function getPricesAction(Request $request) {

        $place = $this->get("doctrine.orm.entity_manager")
                      ->getRepository('AppBundle:Place')
                      ->find($request->get('id'));
        if (empty($place))
            return $this->PlaceNotFound();

        return $place->getPrices();
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"price"})
     * @Rest\Post("/places/{id}/prices")
     */
    public function postPricesAction(Request $request) {

        $place = $this->get("doctrine.orm.entity_manager")
                        ->getRepository('AppBundle:Place')
                        ->find($request->get('id'));
        if (empty($place))
            return $this->PlaceNotFound();

        $price = new Price();
        $price->setPlace($place);
        $form = $this->createForm(PriceType::class, $price);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();
            $place->addPrice($price);
            return $price;
        }
        else
            return $form;

    }

    private function PlaceNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Place not found');
    }
}