<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tag;
use AppBundle\Form\SearchType;
use AppBundle\Value\SearchValue;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("default/index.html.twig")
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/filtered", name="filtered_homepage")
     * @Template("default/content.html.twig")
     */
    public function filteredAction(Request $request)
    {
        $tagId = $request->query->get('tag_id');
        $searchTerm = $request->query->get('search_term');

        $tag = $tagId ? $this->get('repository.tag')->find($tagId) : null;

        $documents = $this
            ->get('repository.document')
            ->matchDocuments($searchTerm, $tag);

        return [
            'documents' => $documents,
            'title' => $tag ? sprintf("Tag: %s", $tag->getName()) : "Documents"
        ];
    }

    /**
     * @Route("/search", name="search")
     * @Template("default/search.html.twig")
     */
    public function searchAction(Request $request)
    {
        $form = $this->createForm(SearchType::class, null, [
            'method' => 'GET'
        ]);
        $form->handleRequest($request);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/navigation", name="navigation")
     * @Template("default/tag_navigation.html.twig")
     * @Method()
     */
    public function navigationAction()
    {
        $tags = $this->getDoctrine()
            ->getRepository(Tag::class)
            ->findAll();

        return [
            'tags' => $tags
        ];
    }
}
