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
     * @Route("/", name="Accueil")
     */

    public function Accueil(): Response
    {
        return $this->render('Defaut/Accueil.html.twig', [
            'controller_name' => 'DefautController',
        ]);
    }


    /**
     * @Route("/liste/{id}", name="liste")
     */

    public function liste(Liste $liste): Response
    {
        return $this->render('Defaut/Liste.html.twig', [
            'controller_name' => 'DefautController',
            'liste'=>$liste
        ]);
    }

}
