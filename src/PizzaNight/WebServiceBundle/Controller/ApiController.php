<?php

namespace PizzaNight\WebServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use PizzaNight\ManagementBundle\Entity\Attendee;

class ApiController extends Controller
{
    /**
     * @Route("/checkin/{slug}", name="_api_access")
     */
    public function checkinAction($slug)
    {
        $status_code = 403;
        $response = array(
            'response' => 'Código no válido! Lo siento no eres un PizzaNighter!',
        );

        $attendee = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findOneBy(array('slug' => $slug));

        if($attendee instanceof Attendee) {
            if($attendee->getStatus()==Attendee::STATUS_ACCEPTED) {
                $attendee->setStatus(Attendee::STATUS_IN_THE_EVENT);
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($attendee);
                $em->flush();

                $status_code = 200;
                $response['response'] = $attendee->getContact()->getName() . ' bienvenid@ al PizzaNight!';
            }
        }

        return new Response(json_encode($response), $status_code);
    }

}
