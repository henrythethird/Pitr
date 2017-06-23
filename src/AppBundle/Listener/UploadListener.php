<?php

namespace AppBundle\Listener;

use AppBundle\Service\DocumentCreatorService;
use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Event\PreUploadEvent;
use Oneup\UploaderBundle\Uploader\File\FilesystemFile;
use Symfony\Component\HttpFoundation\File\File;

class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    private $documentCreator;

    private $originalName;

    public function __construct(DocumentCreatorService $documentCreator, ObjectManager $om)
    {
        $this->om = $om;
        $this->documentCreator = $documentCreator;
    }

    public function onUpload(PostPersistEvent $event)
    {
        /** @var File $file */
        $file = $event->getFile();

        $document = $this->documentCreator->fromFile($event->getFile());
        $document->setName($this->originalName);
        $document->setMimeType($file->getMimeType());
        $document->setPath($file->getRealPath());

        $this->om->persist($document);
        $this->om->flush();

        $response = $event->getResponse();
        $response['success'] = true;
        return $response;
    }

    public function preUpload(PreUploadEvent $event)
    {
        /** @var FilesystemFile $file */
        $file = $event->getFile();

        $this->originalName = $file->getClientOriginalName();
    }
}