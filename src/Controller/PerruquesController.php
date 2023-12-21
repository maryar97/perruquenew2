<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PerruquesController extends AbstractController
{
    #[Route('/perruques', name: 'app_perruques')]
    public function index(CategorieRepository $categorieRepository, ProduitRepository $produitRepository): Response
    {
        $cat = $categorieRepository->find(2);

        return $this->render('perruques/index.html.twig', [
            'categories'=> $cat,
        ]);
    }
}
