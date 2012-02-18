<?php

namespace PizzaNight\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PizzaNight\ManagementBundle\Entity\Attendee;

class IntranetController extends Controller
{
    /**
     * @Route("/whois/{event_id}/{slug}", name="_intranet_whois", defaults={"retry"="1"})
     * @Route("/whois/{event_id}/{slug}/{retry}", name="_intranet_whois_with_retry")
     * @Template()
     */
    public function whoisAction($event_id, $slug, $retry)
    {
        $session = $this->get('session');
        $attendee = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findOneBy(array('event_id' => $event_id, 'slug' => $slug, 'status' => Attendee::STATUS_IN_THE_EVENT));

        if(!($attendee instanceof Attendee)) {
            if($retry<3) {
                $new_slug = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findNextSlug($event_id, ($retry==1 ? $session->get('intranet_last_slug') : ''));
                $retry++;

                return $this->redirect($this->generateUrl('_intranet_whois_with_retry', array('event_id' => $event_id, 'slug' => $new_slug, 'retry' => $retry)));
            } else {
                return $this->redirect($this->generateUrl('_intranet_error'));
            }
        }


        $session->set('intranet_last_slug', $attendee->getSlug());

        $attendee->setHits($attendee->getHits()+1);
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($attendee);
        $em->flush();

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
