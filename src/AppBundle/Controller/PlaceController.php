<?php
/**
 * Created by PhpStorm.
 * MyUser: Nouri
 * Date: 10/04/2017
 * Time: 23:35
 */

namespace AppBundle\Controller;


use AppBundle\Form\Type\PlaceType;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Place;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class PlaceController extends Controller
{

    /**
     * @param Request $request
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/places")
     * @Rest\QueryParam(name="offset", requirements="\d+", default="", description="Index de début de la pagination")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="", description="Index de fin de la pagination")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="Ordre de tri basé sur le nom")
     * @return array
     */
    public function getPlacesAction(Request $request, ParamFetcher $paramFetcher) {

        $offset = $paramFetcher->get("offset");
        $limit = $paramFetcher->get("limit");
        $sort = $paramFetcher->get("sort");

        $qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();
        $qb->select('p')->from("AppBundle:Place", "p");

        if ($offset != "")
            $qb->setFirstResult($offset);

        if ($limit != "")
            $qb->setMaxresults($limit);

        if (in_array($sort, ["asc", "desc"]))
            $qb->orderBy("p.name", $sort);

        $places = $qb->getQuery()->getResult();

        return $places;
    }

    /**
     * @param $id
     * @param Request $request
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/places/{id}")
     * @return object|JsonResponse
     */
    public function getPlaceAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $place = $em->getRepository("AppBundle:Place")
                    ->find($id);

        if(empty($place))
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Place not found');

        return $place;
    }

    /**
     * @param Request $request
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"place"})
     * @Rest\Post("/places/")
     * @return Place|\Symfony\Component\Form\Form
     */
    public function postPlacesAction(Request $request) {

        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all()); // validation des données

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($place->getPrices() as $price) {
                $price->setPlace($place);
                $em->persist($price);
            }
            $em->persist($place);
            $em->flush();
            return $place;
        }
        else
            return $form;
    }

    /**
     * @param Request $request
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"place"})
     * @Rest\DELETE("/places/{id}")
     */
    public function removePlaceAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $place = $em->getRepository("AppBundle:Place")->find($request->get('id'));

        if ($place) {
            $em->remove($place);
            $em->flush();
        }
    }

    /**
     * @param Request $request
     * @Rest\View(serializerGroups={"place"})
     * @Rest\PUT("/places/{id}")
     * @return object|\Symfony\Component\Form\Form|JsonResponse
     */
    public function updatePlaceAction(Request $request) {
        return $this->updatePlace($request, true);
    }

    /**
     * @param Request $request
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Patch("/places/{id}")
     * @return object|\Symfony\Component\Form\Form|JsonResponse
     */
    public function patchPlaceAction(Request $request) {
        return $this->updatePlace($request, false);
    }

    public function updatePlace(Request $request, $clearMissing) {

        $em = $this->getDoctrine()->getManager();
        $place = $em->getRepository("AppBundle:Place")->find($request->get('id'));

        if (empty($place)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Place not found');
        }

        $form = $this->createForm(PlaceType::class, $place);
        $form->submit($request->request->all(), $clearMissing);
        if ($form->isValid()) {
            $em->merge($place);
            $em->flush();
            return $place;
        }
        return $form;
    }
}