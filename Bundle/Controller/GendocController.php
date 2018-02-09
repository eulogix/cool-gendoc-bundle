<?php
namespace Eulogix\Cool\Gendoc\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class GendocController extends Controller
{
    /**
     * @Route("/manager", name="GendocManager", options={"expose"=true})
     * @param Request $request
     *
     * @Template(engine="twig")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manager(Request $request)
    {
        return $this->render('EulogixCoolGendocBundle:manager:manager.html.twig', $request->query->all());
    }

    /**
     * @Route("/manageJobs", name="GendocJobsManager", options={"expose"=true})
     * @Template(engine="twig")
     */
    public function jobsAction()
    {
        return $this->render('EulogixCoolGendocBundle:manager:jobs.html.twig');
    }

    /**
     * @Route("/manageDocuments", name="GendocQueuedDocumentsManager", options={"expose"=true})
     * @Template(engine="twig")
     */
    public function documentsAction(Request $request)
    {
        return $this->render('EulogixCoolGendocBundle:manager:documents.html.twig', ['listerParameters' => $request->query->all()]);
    }
}
