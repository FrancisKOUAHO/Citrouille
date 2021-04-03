<?php


namespace App\Controller;
use App\Entity\Liste;
use phpDocumentor\Reflection\Types\AbstractList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefautController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */

    public function Accueil(): Response
    {
        return $this->render('Defaut/Accueil.html.twig', [
            'controller_name' => 'DefautController',
        ]);
    }

    /**
     * @Route("/question", name="question")
     */

    public function Question(): Response
    {
        return $this->render('Defaut/Question.html.twig', [
            'controller_name' => 'DefautController',
        ]);
    }


    /**
     * @Route("/liste/{id}", name="liste")
     */

    public function Liste(Liste $liste): Response
    {
        return $this->render('Defaut/Liste.html.twig', [
            'controller_name' => 'DefautController',
            'liste' => $liste
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     */

    public function Admin(): Response
    {
        return $this->render('Defaut/Admin.html.twig', [
            'controller_name' => 'DefautController',
        ]);
    }



}
