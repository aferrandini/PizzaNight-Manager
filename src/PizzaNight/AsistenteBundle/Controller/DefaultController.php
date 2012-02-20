<?php

namespace PizzaNight\AsistenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="_gestionar")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('_error'));
    }

}
