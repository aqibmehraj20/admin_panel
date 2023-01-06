<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CategoryRepository $categoryRepository,
    ) {
    }

    #[Route('/', name: 'dashboard')]
    public function dashboard():Response
    {
        return $this->render('dashboard.html.twig');
    }


    #[Route('/categories_list', name: 'categoriesList')]
    public function categoriesList():Response
    {
        $categories = $this->categoryRepository->findAll();
        return $this->render('categoriesList.html.twig',[
            'categories' => $categories
        ]);
    }

    #[Route('/create_category', name: 'createCategory')]
    public function createCategory(Request $request):Response
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $category = new Category();
            $category->setCategoryName($data->getCategoryName());
            $category->setCategoryType($data->getCategoryType());
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'Category saved successfully');

            return $this->redirect('/edit_category/'.$category->getId());
        }
        return $this->render('createCategory.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/edit_category/{id}', name: 'editCategory')]
    public function editCategory(Request $request, int $id):Response
    {
        $category = $this->categoryRepository->find(['id' => $id]);
        if(!$category){
            throw new \RuntimeException('Category not found!');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $category->setCategoryName($data->getCategoryName());
            $category->setCategoryType($data->getCategoryType());
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'Category saved successfully');

            return $this->redirect('/edit_category/'.$category->getId());
        }
        return $this->render('createCategory.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete_category/{id}', name: 'deleteCategory')]
    public function deleteCategory(Request $request, int $id):Response
    {
        $category = $this->categoryRepository->find(["id" => $id]);
        if (!$category){
            throw new \RuntimeException('Category not found');
        }
        $this->entityManager->remove($category);
        $this->entityManager->flush();
        return $this->redirect('/');
    }

    #[Route('/product_list', name: 'productList')]
    public function productList():Response
    {
        return $this->render('productList.html.twig');
    }

}