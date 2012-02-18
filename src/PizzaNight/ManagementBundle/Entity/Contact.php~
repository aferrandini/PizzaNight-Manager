<?php

namespace PizzaNight\ManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PizzaNight\ManagementBundle\Entity\Contact
 */
class Contact
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $phone
     */
    private $phone;

    /**
     * @var string $type
     */
    private $type;

    /**
     * @var text $about_me
     */
    private $about_me;

    /**
     * @var datetime $created_at
     */
    private $created_at;


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
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set about_me
     *
     * @param text $aboutMe
     */
    public function setAboutMe($aboutMe)
    {
        $this->about_me = $aboutMe;
    }

    /**
     * Get about_me
     *
     * @return text 
     */
    public function getAboutMe()
    {
        return $this->about_me;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getGravatarUrl()
    {
        echo "http://www.gravatar.com/avatar/" . md5(strtolower(trim($this->getEmail()))) . "?s=150";
    }
}