<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Job;

/**
 * @Route("/job")
 */
class JobController extends AbstractController 
{
    /**
     * Lists all job entities
     * 
     * @Route("/", name="job.list")
     * @return Response
     */
    public function list(): Response
    {
        $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs
        ]);
    }

    /**
     * Finds and display a job entity
     * @Route("/{id}", name="job.show")
     *
     * @param Job $job
     * @return Response
     */
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig',[
            'job' => $job
        ]);        
    }


}