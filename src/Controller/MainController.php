<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\AddCourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{


    /**
     * @Route("/", name="accueil")
     */
    public function accueil(Request $req, EntityManagerInterface $em, CourseRepository $cr): Response
    {
        $course = new Course();
        $form = $this->createForm(AddCourseType::class,$course);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            $course->setPris(false);
            $em->persist($course);
            $em->flush();
        }

        $courses = $cr->findAll();

        return $this->render('main/accueil.html.twig', [
            "courses" => $courses,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/got/{id}", name="got")
     */
    public function got(Course $c, EntityManagerInterface $em): Response
    {
        if ($c->getPris() == true) $c->setPris(false);
        else $c->setPris(true);
        $em->persist($c);
        $em->flush();
        
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/del/{id}", name="del")
     */
    public function del(Course $c, Request $req, EntityManagerInterface $em, CourseRepository $cr): Response
    {
        $em->remove($c);
        $em->flush();
        
        return $this->redirectToRoute('accueil');;
    }
}
