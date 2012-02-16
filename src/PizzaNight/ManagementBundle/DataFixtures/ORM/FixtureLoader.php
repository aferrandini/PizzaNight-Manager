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
        /*
        $contact = new Contact();
        $contact->setCreatedAt(new \DateTime());
        $contact->setName("Pedro Perez");
        $contact->setEmail("p.perez@neosistec.com");
        $contact->setPhone("610000000");
        $contact->setType("Profesional");
        $contact->setAboutMe("Un poco sobre mi!");
        $manager->persist($contact);

        $contact = new Contact();
        $contact->setCreatedAt(new \DateTime());
        $contact->setName("Manuel Martinez");
        $contact->setEmail("m.martinez@neosistec.com");
        $contact->setPhone("611000000");
        $contact->setType("Profesional");
        $contact->setAboutMe("Un poco sobre mi!");
        $manager->persist($contact);

        $contact = new Contact();
        $contact->setCreatedAt(new \DateTime());
        $contact->setName("Carlos Lopez");
        $contact->setEmail("c.lopez@neosistec.com");
        $contact->setPhone("612000000");
        $contact->setType("Profesional");
        $contact->setAboutMe("Un poco sobre mi!");
        $manager->persist($contact);
        */

        $event = new Event();
        $event->setName("22-Feb");
        $event->setUrl("http://pizzanight.neosistec.com");
        $event->setDate(new \DateTime('2012-02-22 19:00:00', new \DateTimeZone('Europe/Madrid')));
        $event->setMaxPeople(25);
        $manager->persist($event);

        $manager->flush();

        $contacts = $manager->getRepository('PizzaNightManagementBundle:Contact')->findAll();
        $event = $manager->getRepository('PizzaNightManagementBundle:Event')->findOneBy(array());

        foreach ($contacts as $contact) {
            $attendee = new Attendee();
            $attendee->setContact($contact);
            $attendee->setEvent($event);
            $attendee->setDate(new \DateTime());
            $attendee->setStatus(Attendee::STATUS_REGISTERED);
            $attendee->setSlug(substr(md5($contact->getEmail()), 0, 20));
            $manager->persist($attendee);
        }

        $manager->flush();
    }
}