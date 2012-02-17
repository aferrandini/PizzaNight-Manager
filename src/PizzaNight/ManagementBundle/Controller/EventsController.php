<?php

namespace PizzaNight\ManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

use PizzaNight\ManagementBundle\Entity\Attendee;

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

        $attendeesRepository = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee');
        $attendees = count($attendeesRepository->findEventAttendees($event));
        $resgistered = $attendeesRepository->findBy(array('event' => $event->getId()), array('status' => 'ASC'));

        return array(
            'event' => $event,
            'attendees' => $attendees,
            'registered' => $resgistered,
        );
    }

    /**
     * @Route("/{id}/attendees", name="_attendees")
     * @Template()
     */
    public function attendeesAction($id)
    {
        $event = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Event')->find($id);
        $attendees = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findBy(
            array(
                'event' => $event->getId(),
                'status' => Attendee::STATUS_ACCEPTED
            )
        );

        return array(
            'event' => $event,
            'attendees' => $attendees
        );
    }

    /**
     * @Route("/{id}/attendees/export", name="_attendees_export")
     * @Template()
     */
    public function attendeesExportAction($id)
    {
        $event = $this->getDoctrine()
            ->getRepository('PizzaNightManagementBundle:Event')
            ->find($id);

        $attendees = $this->getDoctrine()
            ->getRepository('PizzaNightManagementBundle:Attendee')
            ->findEventAttendees($event);

        //Abrimos la plantilla excel para el informe seleccionado
        $reader = new \PHPExcel_Reader_Excel2007();
        $excel = $reader->load($this->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'PizzaNightTemplate.xlsx');
        $excel->setActiveSheetIndex(0);

        $row = 3;
        foreach ($attendees as $attendee) {
            $coll = 'A';
            $excel->getActiveSheet()->setCellValue($coll.$row, $attendee->getContact()->getName());$coll++;
            $excel->getActiveSheet()->setCellValue($coll.$row, $attendee->getContact()->getEmail());$coll++;
            $excel->getActiveSheet()->setCellValue($coll.$row, $attendee->getContact()->getPhone());$coll++;

            $row++;
        }

        $tmp_filename = $this->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . time() . '.xlsx';

        $output = new \PHPExcel_Writer_Excel5($excel);
        $output->save($tmp_filename);

        $stream = file_get_contents($tmp_filename);
        @unlink($tmp_filename);

        //Establecemos las cabeceras
        $response = new Response();
        $response->headers->set('Pragma', '');
        $response->headers->set('Cache-Control', '');
        $response->headers->set('Content-Type', 'application/ms-excel');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $event->getName() . '.xls"');
        $response->setContent($stream);

        return $response;
    }

    /**
     * @Route("/{id}/{id_attendee}/attendee/info", name="_attendee_info")
     * @Template()
     */
    public function attendeeInfoAction()
    {
        // Your code goes here!

        return array();
    }

    /**
     * @Route("/{event_id}/{contact_id}/validate/attendee", name="_validate_attendee")
     */
    public function validateAttendeeAction($event_id, $contact_id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        if($contact_id==='all') {
            $attendees = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findBy(array('event_id' => $event_id));
            foreach($attendees as $attendee) {
                $attendee->setStatus(Attendee::STATUS_ACCEPTED);
                $em->persist($attendee);
            }
        } else {
            $attendee = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findOneBy(array('event_id' => $event_id, 'contact_id' => $contact_id));
            if($attendee instanceof Attendee) {
                $attendee->setStatus(Attendee::STATUS_ACCEPTED);
                $em->persist($attendee);
            }
        }

        $em->flush();

        return $this->redirect($this->generateUrl('_registered', array(
            'id' => $event_id
        )));
    }

    /**
     * @Route("/{event_id}/{contact_id}/reject/attendee", name="_reject_attendee")
     */
    public function rejectAttendeeAction($event_id, $contact_id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        if($contact_id==='all') {
            $attendees = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findBy(array('event_id' => $event_id));
            foreach($attendees as $attendee) {
                $attendee->setStatus(Attendee::STATUS_REJECTED);
                $em->persist($attendee);
            }
        } else {
            $attendee = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee')->findOneBy(array('event_id' => $event_id, 'contact_id' => $contact_id));
            if($attendee instanceof Attendee) {
                $attendee->setStatus(Attendee::STATUS_REJECTED);
                $em->persist($attendee);
            }
        }

        $em->flush();

        return $this->redirect($this->generateUrl('_registered', array(
            'id' => $event_id
        )));
    }

}
