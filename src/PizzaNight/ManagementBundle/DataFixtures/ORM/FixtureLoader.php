<?php
namespace PizzaNight\ManagementBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use PizzaNight\ManagementBundle\Entity\Event;
use PizzaNight\ManagementBundle\Entity\Attendee;
use PizzaNight\ManagementBundle\Entity\Contact;

class FixtureLoader implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $contact = new Contact();
        $contact->setCreatedAt(new \DateTime());
        $contact->setName("Ariel Ferrandini Price");
        $contact->setEmail("aferrandini@neosistec.com");
        $contact->setPhone("627550950");
        $contact->setType("Profesional");
        $contact->setAboutMe("Un poco sobre mi!");
        $manager->persist($contact);

        $event = new Event();
        $event->setName("22-Feb");
        $event->setUrl("http://pizzanight.neosistec.com");
        $event->setDate(new \DateTime());
        $event->setMaxPeople(25);
        $manager->persist($event);

        $manager->flush();

        $contact = $manager->getRepository('PizzaNightManagementBundle:Contact')->findOneBy(array());
        $event = $manager->getRepository('PizzaNightManagementBundle:Event')->findOneBy(array());

        $attendee = new Attendee();
        $attendee->setContact($contact);
        //$attendee->setContactId($contact->getId());
        $attendee->setEvent($event);
        //$attendee->setEventId($event->getId());
        $attendee->setDate(new \DateTime());
        $attendee->setStatus(Attendee::STATUS_REGISTERED);
        $attendee->setSlug(substr(md5($contact->getEmail()), 0, 20));
        $manager->persist($attendee);

        $manager->flush();
    }
}