<?php

namespace Mucahit\ParameterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('ParameterBundle:Default:index.html.twig', array('param' => $request->query->get('parameter')));
    }
}
