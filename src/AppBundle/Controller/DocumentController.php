<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Workflow;

class DocumentController extends Controller
{
    /**
     * @Route("/document/details/{id}", name="document_details")
     * @Template("document/details.html.twig")
     * @Method("GET")
     */
    public function detailsAction($id)
    {
        $document = $this->getDoctrine()
            ->getRepository(Document::class)
            ->find($id);

        if (!$document) {
            return $this->redirectToRoute('homepage');
        }

        return [
            'document' => $document
        ];
    }

    /**
     * @Route("/document/remove/{id}", name="document_remove")
     * @Method("POST")
     */
    public function removeAction(Request $request, Document $document)
    {
        $manager = $this->getDoctrine()
            ->getManager();

        $manager->remove($document);
        $manager->flush();

        $this->addFlash(
            'success',
            sprintf(
                "Document \"%s\" was removed.",
                $document->getName()
            )
        );

        return $this->redirectToReferer($request);
    }

    /**
     * @Route("/document/togglePin/{id}", name="document_toggle_pin")
     * @Method("POST")
     */
    public function togglePinAction(Request $request, Document $document)
    {
        $workflow = $this->getDocumentWorkflow();

        if ($workflow->can($document, 'pin')) {
            $workflow->apply($document, 'pin');
        } else {
            $workflow->apply($document, 'unpin');
        }

        $this->getDoctrine()
            ->getManager()
            ->flush();

        return $this->redirectToReferer($request);
    }

    /**
     * @Route("/document/toggleDone/{id}", name="document_toggle_done")
     * @Method("POST")
     */
    public function toggleDoneAction(Request $request, Document $document)
    {
        $workflow = $this->getDocumentWorkflow();

        if ($workflow->can($document, 'finish')) {
            $workflow->apply($document, 'finish');
        } else {
            $workflow->apply($document, 'reset');
        }

        $this->getDoctrine()
            ->getManager()
            ->flush();

        return $this->redirectToReferer($request);
    }

    /**
     * @return Workflow
     */
    private function getDocumentWorkflow()
    {
        return $this->get('state_machine.document');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectToReferer(Request $request)
    {
        return $this->redirect($request->headers->get('referer'));
    }
}