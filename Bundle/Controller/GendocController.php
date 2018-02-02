<?php
namespace Eulogix\Cool\Gendoc\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GendocController extends Controller
{
    /**
     * @Route("/manager", name="QueuedDocumentsManager", options={"expose"=true})
     * @Template(engine="twig")
     */
    public function managerAction()
    {
        return $this->render('EulogixCoolGendocBundle:manager:manager.html.twig');
    }
}
