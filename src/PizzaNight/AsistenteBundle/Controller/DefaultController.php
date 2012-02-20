<?php

namespace PizzaNight\AsistenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PizzaNight\ManagementBundle\Entity\Attendee;

/**
 * @Route("/asistente")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/error", name="_error")
     * @Template()
     */
    public function errorAction()
    {
        return array();
    }

    /**
     * @Route("/", name="_gestionar")
     * @Template()
     */
    public function indexAction($slug)
    {
        return $this->redirect($this->generateUrl('_error'));
    }

    /**
     * @Route("/{slug}", name="_gestionar_asistencia")
     * @Template()
     */
    public function asistenteAction($slug)
    {
        $attendee = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findOneBy(array('slug' => $slug));

        if(!($attendee instanceof Attendee)) {
            return $this->redirect($this->generateUrl('_error'));
        }

        return array('attendee' => $attendee);
    }

    /**
     * @Route("/confirm/{slug}", name="_confirmar_asistencia")
     */
    public function confirmarAction($slug)
    {
        $attendee = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findOneBy(array('slug' => $slug));

        if($attendee instanceof Attendee) {
            $attendee->setStatus(Attendee::STATUS_CONFIRMED);
            $this->saveAttendee($attendee);
        } else {
            return $this->redirect($this->generateUrl('_error'));
        }

        return $this->redirect($this->generateUrl('_gestionar_asistencia', array('slug' => $attendee->getSlug())));
    }

    /**
     * @Route("/unconfirm/{slug}", name="_desconfirmar_asistencia")
     */
    public function desconfirmarAction($slug)
    {
        $attendee = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findOneBy(array('slug' => $slug));

        if($attendee instanceof Attendee) {
            $attendee->setStatus(Attendee::STATUS_UNCONFIRMED);
            $this->saveAttendee($attendee);
        } else {
            return $this->redirect($this->generateUrl('_error'));
        }

        return $this->redirect($this->generateUrl('_gestionar_asistencia', array('slug' => $attendee->getSlug())));
    }

    /**
     * @param \PizzaNight\ManagementBundle\Entity\Attendee $attendee
     */
    private function saveAttendee(Attendee $attendee)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($attendee);
        $em->flush();
    }
}
