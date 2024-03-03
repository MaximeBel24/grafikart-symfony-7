<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    #[Route("/", name: "home")]
    function index (Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        $recipes = $repository->findWithDurationLowerThan(40);

        //Met à jour le titre de la recette 0
        // $recipes[0]->setTitle('Pâtes bolognaise');

        //Créer un nouvel objet en BDD
        // $recipe = new Recipe();
        // $recipe->setTitle('Barbe à papa')
        //     ->setSlug('barbe-papa')
        //     ->setContent('Mettez du sucre')
        //     ->setDuration(2)
        //     ->setCreatedAt(new \DateTimeImmutable())
        //     ->setUpdatedAt(new \DateTimeImmutable());
        //Persist le nouvel objet 
        // $em->persist($recipe);

        //Suprimer en BBD
        // $em->remove($recipes[3]);

        //Execute l'envoie de la requete en BDD
        // $em->flush();
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes
        ]);     
        //return new Response('Bonjour ' . $request->query->get('name', 'Anonyme'));
    }
}
