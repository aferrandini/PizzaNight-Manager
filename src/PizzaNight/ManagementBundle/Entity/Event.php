<?php

namespace PizzaNight\ManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PizzaNight\ManagementBundle\Entity\Event
 */
class Event
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var datetime $date
     */
    private $date;

    /**
     * @var integer $max_people
     */
    private $max_people;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $url
     */
    private $url;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set max_people
     *
     * @param integer $maxPeople
     */
    public function setMaxPeople($maxPeople)
    {
        $this->max_people = $maxPeople;
    }

    /**
     * Get max_people
     *
     * @return integer 
     */
    public function getMaxPeople()
    {
        return $this->max_people;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

}