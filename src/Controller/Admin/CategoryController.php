<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/admin/categories", name: 'admin.category.')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/{slug}-{id}', name: 'show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(string $slug, int $id, CategoryRepository $repository): Response
    {
        $category = $repository->find($id);
        if($category->getSlug() !== $slug){
            return $this->redirectToRoute('admin.category.show', ['slug' => $category->getSlug(), 'id' => $category-> getId()]);
        }
        return $this->render('admin/category/show.html.twig', [
            'category' => $category
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em){
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été créée');
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/create.html.twig',[
            'form' => $form
        ]);
    }
}
