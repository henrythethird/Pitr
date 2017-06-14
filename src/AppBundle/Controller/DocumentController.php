<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends Controller
{
    /**
     * @Route("/document/details/{id}", name="document_details")
     * @Template("document/details.html.twig")
     * @Method("GET")
     */
    public function detailsAction(Document $document)
    {
        return [
            'document' => $document
        ];
    }
}