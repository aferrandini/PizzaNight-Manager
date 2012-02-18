<?php

namespace PizzaNight\AsistenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/asistente")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/{slug}", name="_gestionar_asistencia")
     * @Template()
     */
    public function indexAction($slug)
    {
        return array('slug' => $slug);
    }
}
