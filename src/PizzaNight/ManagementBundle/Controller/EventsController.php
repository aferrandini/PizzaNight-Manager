<?php

namespace PizzaNight\ManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

class EventsController extends Controller
{
    /**
     * @Route("/", name="_events")
     * @Template()
     */
    public function eventsAction()
    {
        $events = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Event')->findAll();

        return array('events' => $events);
    }

    /**
     * @Route("/{id}/registered", name="_registered")
     * @Template()
     */
    public function registeredAction($id)
    {
        $event = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Event')->find($id);
        $attendees = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findBy(array('event' => $event->getId()));

        return array('attendees' => $attendees);
    }

    /**
     * @Route("/{id}/import/registered", name="_import_registered")
     * @Template()
     */
    public function importAttendeesAction($id)
    {

    }

    /**
     * @Route("/{id}/attendees", name="_attendees")
     * @Template()
     */
    public function attendeesAction($id)
    {
        $event = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Event')->find($id);
        $attendees = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findBy(array('event' => $event->getId()));

        return array('attendees' => $attendees);
    }

}
