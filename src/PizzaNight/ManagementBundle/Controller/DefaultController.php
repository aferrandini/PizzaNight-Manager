<?php

namespace PizzaNight\ManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/events", name="_events")
     * @Template()
     */
    public function eventsAction()
    {
        $events = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Event')->findAll();

        return array('events' => $events);
    }

    /**
     * @Route("/attendees/{id}", name="_attendees")
     * @Template()
     */
    public function attendeesAction($id)
    {
        $event = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Event')->find($id);
        $attendees = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findBy(array('event' => $event->getId()));

        return array('attendees' => $attendees);
    }
}
