<?php

namespace PizzaNight\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PizzaNight\ManagementBundle\Entity\Attendee;

class IntranetController extends Controller
{
    /**
     * @Route("/whois/{event_id}/{slug}", name="_intranet_checkin", defaults={"retry"="1"})
     * @Route("/whois/{event_id}/{slug}/{retry}", name="_intranet_checkin_with_retry")
     * @Template()
     */
    public function whoisAction($event_id, $slug, $retry)
    {
        $attendee = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findOneBy(array('event_id' => $event_id, 'slug' => $slug, 'status' => Attendee::STATUS_IN_THE_EVENT));

        if(!($attendee instanceof Attendee)) {
            if($retry<3) {
                $retry++;

                $new_slug = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findNextSlug($event_id);

                return $this->redirect($this->generateUrl('_intranet_checkin_with_retry', array('event_id' => $event_id, 'slug' => $new_slug, 'retry' => $retry)));
            } else {
                return $this->redirect($this->generateUrl('_intranet_error'));
            }
        }

        return array(
            'attendee'  => $attendee
        );
    }

    /**
     * @Route("/whois/error", name="_intranet_error")
     * @Template()
     */
    public function whoisErrorAction()
    {
        return array();
    }
}
