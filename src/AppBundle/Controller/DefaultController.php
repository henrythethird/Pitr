<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tag;
use AppBundle\Form\SearchType;
use AppBundle\Value\Folder;
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
     * @Route("/folder/{folder}", name="folder")
     * @Template("default/index.html.twig")
     */
    public function folderAction($folder = Folder::INBOX)
    {
        return [
            'folder' => $folder,
            'requestURL' => $this->generateUrl('filtered_folder', [
                'folder' => $folder
            ])
        ];
    }

    /**
     * @Route("/tag/{tag}", name="tag")
     * @Template("default/index_tag.html.twig")
     */
    public function tagAction(Tag $tag)
    {
        return [
            'tag' => $tag->getId(),
            'folder' => Folder::TAGS,
            'requestURL' => $this->generateUrl('filtered_tag', [
                'tag' => $tag->getId()
            ])
        ];
    }

    /**
     * @Route("/filtered/{tag}", name="filtered_tag")
     * @Template("default/content.html.twig")
     */
    public function filteredTagAction(Request $request, Tag $tag = null)
    {
        $searchTerm = $request->query->get('search_term');

        $documents = $this
            ->get('repository.document')
            ->matchWithTag($searchTerm, $tag);

        return [
            'documents' => $documents,
            'title' => $tag ? sprintf("Tag: %s", $tag->getName()) : "Documents",
        ];
    }

    /**
     * @Route("/filtered/{folder}", name="filtered_folder")
     * @Template("default/content.html.twig")
     */
    public function filteredFolderAction(Request $request, $folder)
    {
        $searchTerm = $request->query->get('search_term');

        $documents = $this
            ->get('repository.document')
            ->matchWithFolder($searchTerm, $folder);

        return [
            'documents' => $documents,
            'title' => "Documents",
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
     * @Route("/navigation/{folder}/{tag}", name="navigation")
     * @Template("default/tag_navigation.html.twig")
     * @Method()
     */
    public function navigationAction($folder, Tag $tag = null)
    {
        return [
            'tags' => $this->getAllTags(),
            'tag' => $tag,
            'folder' => $folder
        ];
    }

    /**
     * @return Tag[]|array
     */
    private function getAllTags()
    {
        return $this->getDoctrine()
            ->getRepository(Tag::class)
            ->findAll();
    }
}
