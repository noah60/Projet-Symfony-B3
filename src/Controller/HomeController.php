<?php

namespace App\Controller;

use App\Repository\PrestataireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PrestataireRepository $prestataireRepository): Response
    {
        // Récupérer les prestataires avec leur utilisateur (ROLE_PRESTATAIRE) et leur statut
        $prestataires = $prestataireRepository->createQueryBuilder('p')
            ->innerJoin('p.utilisateur', 'u')
            ->addSelect('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_PRESTATAIRE%')
            ->orderBy('p.note', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();

        // Le statutdisponible est déjà inclus via l'entité Prestataire

        return $this->render('home/index.html.twig', [
            'prestataires' => $prestataires,
            'controller_name' => 'HomeController',
        ]);
    }
}
