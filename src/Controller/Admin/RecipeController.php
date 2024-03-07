<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route("/admin/recettes", name: 'admin.recipe.')]
class RecipeController extends AbstractController
{
//    #[Route('/demo')]
 //   public function demo(ValidatorInterface $validator)
//    {
 //       $recipe = new Recipe();
 //       $errors = $validator->validate($recipe);
 //       dd((string)$errors);
 //   }

    #[Route('/', name: 'index')]
    public function index(RecipeRepository $repository): Response
    {
        $recipes = $repository->findAll();
        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/{slug}-{id}', name: 'show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(string $slug, int $id, RecipeRepository $repository): Response
    {
        $recipe = $repository->find($id);
        if($recipe->getSlug() !== $slug){
            return $this->redirectToRoute('admin.recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe-> getId()]);
        }
        return $this->render('admin/recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em){
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été créée');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/create.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em) {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée.');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function remove(Recipe $recipe, EntityManagerInterface $em){
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('danger', 'La recette a bien été supprimée');
        return $this->redirectToRoute('admin.recipe.index');
    }
}