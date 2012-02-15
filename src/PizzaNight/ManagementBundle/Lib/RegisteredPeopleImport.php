<?php
/**
 * RegisteredPeopleImport.php
 *
 * Created by arielferrandini
 */
namespace PizzaNight\ManagementBundle\Lib;

use Symfony\Component\DependencyInjection\Container;

class RegisteredPeopleImport
{
    protected $container = null;
    protected $filename  = '';

    public function __construct(Container $container, $filename='')
    {
        $this->setContainer($container);

        $this->setFilename($filename);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Symfony\Component\DependencyInjection\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param $filename
     * @throws \InvalidArgumentException
     */
    public function setFilename($filename)
    {
        if(!is_file($filename)) {
            throw new \InvalidArgumentException("The filename must be an existant file");
        } else {
            $this->filename = $filename;
        }
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }


}
