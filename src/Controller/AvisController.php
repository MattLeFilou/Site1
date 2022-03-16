<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Avis;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'avis')]
    public function Avis(): Response
    {
        $repoAvis = $this->getDoctrine()->getRepository(Avis::class);
        $avis = $repoAvis->findAll();
        return $this->render('avis/avis.html.twig', [
            'avis' => $avis
        ]);
    }
}
