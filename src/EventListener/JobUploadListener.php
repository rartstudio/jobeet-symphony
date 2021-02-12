<?php

namespace App\EventListener;

use App\Entity\Job;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class JobUploadListener
{
    
    /**
     * @var FileUploader
     */
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
    }

    /**
     * @param PreUpdateEventArgs $args
     * @return void
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    public function uploadFile($entity)
    {
        //upload only works for job entities
        if(!$entity instanceof Job){
            return;
        }

        $logoFile = $entity->getLogo();

        //only upload new files
        if ($logoFile instanceof UploadedFile){
            $fileName= $this->uploader->upload($logoFile);
            $entity->setLogo($fileName);
        }
    }
}