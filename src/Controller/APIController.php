<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{
    /**
     * @Route("/api/courses", name="api_liste", methods={"GET"})
     */
    public function list(CourseRepository $cr): Response
    {
        $courses = $cr->findAll();
        return $this->json($courses);
    }

    /**
     * @Route("/api/courses", name="api_add", methods={"PUT"})
     */
    public function add(Request $req, EntityManagerInterface $em, CourseRepository $cr): Response
    {
        $objet = json_decode($req->getContent());
        $c = new Course();
        $c->setNom($objet->nom);
        $c->setPris($objet->pris);
        $em->persist($c);
        $em->flush();
        
        $courses = $cr->findAll();
        return $this->json($courses);
    }

    /**
     * @Route("/api/courses/{id}", name="api_edit", methods={"POST"})
     */
    public function edit(Course $c, Request $req, EntityManagerInterface $em, CourseRepository $cr): Response
    {
       $objet = json_decode($req->getContent());
       $c->setNom($objet->nom);
       $c->setPris($objet->pris);
       $em->persist($c);
       $em->flush();
       
       $courses = $cr->findAll();
       return $this->json($courses);
    }

    /**
     * @Route("/api/courses/{id}", name="api_delete", methods={"DELETE"})
     */
    public function delete(Course $c, EntityManagerInterface $em, CourseRepository $cr): Response
    {
        $em->remove($c);
        $em->flush();

        $courses = $cr->findAll();
        return $this->json($courses);
    }
}
