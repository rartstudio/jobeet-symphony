<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Job;
use App\Entity\Category;
use App\Form\JobType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/job")
 */
class JobController extends AbstractController 
{

    /**
     * Creates a new job entity
     * 
     * @Route("/create", name="job.create", methods={"GET","POST"})
     * 
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function create(Request $request,EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var UploadedFile|null $logoFile
             */
            $logoFile = $form->get('logo')->getData();
            if($logoFile instanceOf UploadedFile){
                $fileName  = $fileUploader->upload($logoFile);

                $job->setLogo($fileName);
            }

            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute(
                'job.preview',
                ['token' => $job->getToken()]
            );
        }

        return $this->render('job/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * Lists all job entities
     * 
     * @Route("/", name="job.list")
     * @return Response
     */
    public function list(EntityManagerInterface $em): Response
    {
        //using repository 
        $categories = $em->getRepository(Category::class)->findWithActiveJobs();
        
        //filter a data by active job
        // $query = $em->createQuery(
        //     'SELECT j FROM App:Job j WHERE j.expiresAt > :date'
        // )->setParameter('date',new \DateTime('-30 days'));
        
        //get all data job
        // $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();

        // $jobs = $query->getResult();

        return $this->render('job/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Finds and display a job entity
     * @Route("/{id}", name="job.show", methods="GET", requirements={"id"="\d+"})
     *
     * @Entity("job", expr="repository.findActiveJob(id)")
     * @param Job $job
     * @return Response
     */
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig',[
            'job' => $job,
            'hasControlAccess'=> true
        ]);        
    }

    /**
     * Edit existing job entity
     *
     * @Route("/{token}/edit", name="job.edit", methods={"GET", "POST"}, requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(Request $request, Job $job, EntityManagerInterface $em, FileUploader $fileUploader) : Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var UploadedFile|null $logoFile
             */
            $logoFile = $form->get('logo')->getData();
            if($logoFile instanceOf UploadedFile){
                $fileName  = $fileUploader->upload($logoFile);

                $job->setLogo($fileName);
            }

            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute(
                'job.preview',
                ['token' => $job->getToken()]
            );
        }

        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays the preview page for a job entity
     * 
     * @Route("/{token}", name="job.preview", methods="GET", requirements={"token" = "\w+"})
     *
     * @param Job $job
     * @return Response
     */
    public function preview(Job $job): Response
    {
        return $this->render('job/show.html.twig',[
            'job'=> $job
        ]);
    }
}