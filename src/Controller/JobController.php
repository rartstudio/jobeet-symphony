<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Job;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
            'job' => $job
        ]);        
    }


}