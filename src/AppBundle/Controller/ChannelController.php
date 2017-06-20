<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 05/06/2017
 * Time: 18:43
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Channel;
use AppBundle\Form\Type\ChannelType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ChannelController extends Controller
{

    /**
     * @param Request $request
     * @Rest\View(serializerGroups={"channel"})
     * @Rest\Get("/channels")
     * @return array
     */
    public function getChannelsAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $channels = $em->getRepository("AppBundle:Channel")->findAll();

        return $channels;
    }

    /**
     * @param Request $request
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"channel"})
     * @Rest\Post("/channels")
     * @return Channel|\Symfony\Component\Form\Form
     */
    public function postChannelAction(Request $request) {
        $channel = new Channel();
        $form = $this->createForm(ChannelType::class, $channel);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($channel);
            $em->flush();
            return $channel;
        }
        else
            return $form;
    }
}