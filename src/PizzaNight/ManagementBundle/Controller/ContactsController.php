<?php

namespace PizzaNight\ManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

use PizzaNight\ManagementBundle\Entity\Attendee;
use PizzaNight\ManagementBundle\Form\ContactType;
use PizzaNight\ManagementBundle\Entity\Contact;

class ContactsController extends Controller
{
    /**
     * @Route("/contacts", name="_contacts")
     * @Template()
     */
    public function contactsAction()
    {
        $contacts = $this->getDoctrine()
            ->getRepository('PizzaNightManagementBundle:Contact')
            ->findAllForAdminList();

        return array(
            'contacts' => $contacts,
        );
    }

    /**
     * @Route("/contacts/edit/{id}", name="_contacts_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $contact = $this->getDoctrine()
            ->getRepository('PizzaNightManagementBundle:Contact')
            ->find($id);

        if(!($contact instanceof Contact)) {
            return $this->redirect($this->generateUrl('_contacts'));
        }

        $form = $this->createForm(new ContactType(), $contact);

        if($this->getRequest()->getMethod()=='POST') {
            $form->bindRequest($this->getRequest());

            if($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($contact);
                $em->flush();

                return $this->redirect($this->generateUrl('_contacts'));
            }
        }

        return array(
            'contact' => $contact,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/contacts/delete/{id}", name="_contacts_delete")
     */
    public function contactsDeleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $attendees = $this->getDoctrine()
            ->getRepository('PizzaNightManagementBundle:Attendee')
            ->findBy(array('contact_id' => $id));
        foreach($attendees as $attendee) {
            $em->remove($attendee);
        }

        $contact = $this->getDoctrine()
            ->getRepository('PizzaNightManagementBundle:Contact')
            ->find($id);
        $em->remove($contact);

        $em->flush();

        return $this->redirect($this->generateUrl('_contacts'));
    }
}
