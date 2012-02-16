<?php

namespace PizzaNight\ManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PizzaNight\ManagementBundle\Entity\Attendee
 */
class Attendee
{
    /**
     * Attendee status
     */
    const STATUS_REGISTERED   = 0;
    const STATUS_ACCEPTED     = 1;
    const STATUS_REJECTED     = 2;
    const STATUS_CONFIRMED    = 3;
    const STATUS_IN_THE_EVENT = 4;

    /**
     * @var integer $event_id
     */
    private $event_id;

    /**
     * @var integer $contact_id
     */
    private $contact_id;

    /**
     * @var datetime $date
     */
    private $date;

    /**
     * @var string $slug
     */
    private $slug;

    /**
     * @var smallint $status
     */
    private $status;

    /**
     * @var datetime $register_date
     */
    private $register_date;


    /**
     * Set event_id
     *
     * @param integer $eventId
     */
    public function setEventId($eventId)
    {
        $this->event_id = $eventId;
    }

    /**
     * Get event_id
     *
     * @return integer 
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * Set contact_id
     *
     * @param integer $contactId
     */
    public function setContactId($contactId)
    {
        $this->contact_id = $contactId;
    }

    /**
     * Get contact_id
     *
     * @return integer 
     */
    public function getContactId()
    {
        return $this->contact_id;
    }

    /**
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set status
     *
     * @param smallint $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return smallint 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set register_date
     *
     * @param datetime $registerDate
     */
    public function setRegisterDate($registerDate)
    {
        $this->register_date = $registerDate;
    }

    /**
     * Get register_date
     *
     * @return datetime 
     */
    public function getRegisterDate()
    {
        return $this->register_date;
    }
    /**
     * @var PizzaNight\ManagementBundle\Entity\Event
     */
    private $event;

    /**
     * @var PizzaNight\ManagementBundle\Entity\Contact
     */
    private $contact;


    /**
     * Set event
     *
     * @param PizzaNight\ManagementBundle\Entity\Event $event
     */
    public function setEvent(\PizzaNight\ManagementBundle\Entity\Event $event)
    {
        $this->event = $event;
        $this->setEventId($event->getId());
    }

    /**
     * Get event
     *
     * @return PizzaNight\ManagementBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set contact
     *
     * @param PizzaNight\ManagementBundle\Entity\Contact $contact
     */
    public function setContact(\PizzaNight\ManagementBundle\Entity\Contact $contact)
    {
        $this->contact = $contact;
        $this->setContactId($contact->getId());
    }

    /**
     * Get contact
     *
     * @return PizzaNight\ManagementBundle\Entity\Contact 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @ORM\prePersist
     */
    public function generateSlug()
    {
        $slug = md5($this->getEventId() . ':' . $this->getContactId() . ':' . mt_rand(5000, 9999) . ':' . microtime(false));

        $this->setSlug(substr($slug, 5, 20));
    }
}