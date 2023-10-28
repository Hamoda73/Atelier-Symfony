<?php

namespace App\Controller;

use App\Controller\AuthorController;
use App\Repository\AuthorRepository;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\SearchType;
use App\Repository\BookRepository;
use App\Entity\Book;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/showbook', name: 'app_showbook')]
    public function showbook(BookRepository $bookRepository, Request $req): Response
    {
        $book = $bookRepository->findAll();
        
        
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($req);
        $nbcategory =$bookRepository->sommescifi();

        if($form->isSubmitted()) {
            $data = $form->get('ref')->getData();
            $book = $bookRepository->searchbyref($data);

            return $this->renderForm('book/showbook.html.twig', [
               
                'book' => $book,
                'f' => $form,
                'nbcategory' => $nbcategory
            ]);
        }
    return $this->renderForm('book/showbook.html.twig', [
        'book' => $book,
        'f' => $form,
        'nbcategory' => $nbcategory
    ]);

    }

    #[Route('/showbookasc', name: 'app_showbookasc')]
    public function showbookasc(BookRepository $bookRepository): Response
    {
        
        $book = $bookRepository->orderbyauthor();

        return $this->render('book/showbookasc.html.twig', [
            'book' => $book
        ]);
    }

    #[Route('/showlistdate', name: 'app_showlistdate')]
    public function showlistdate(BookRepository $bookRepository): Response
    {
        
        $book = $bookRepository->listedate();

        return $this->render('book/showlistdate.html.twig', [
            'book' => $book
        ]);
    }



    #[Route('/showlistpublie', name: 'app_showlistpublie')]
    public function listepublie(BookRepository $bookRepository): Response
    {
        
        $book = $bookRepository->listepublie();

        return $this->render('book/showlistpublie.html.twig', [
            'book' => $book
        ]);
    }



    
    #[Route('/addbookform', name: 'app_addbookform')]
    public function addbookform(ManagerRegistry $managerRegistry, Request $request): Response
    {
        $em = $managerRegistry->getManager();
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

           

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('app_showbook'); // Replace 'app_book' with the appropriate route name

        }
        

        return $this->renderForm('book/addbookform.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/editbookform/{id}', name: 'app_editbookform')]
    public function editbookform($id, ManagerRegistry $managerRegistry, BookRepository $bookRepository, Request $req): Response
    {
        $em=$managerRegistry->getManager();
        $dataid=$bookRepository->find($id);
        $form=$this->createForm(bookType::class,$dataid);
        $form->handleRequest($req);

        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_showbook');
             
        }


        return $this->renderForm('book/editbookform.html.twig', [
            'x'=>$form
        ]);
    }

    #[Route('/deletebook/{id}', name: 'app_deletebook')]
    public function deletedeletebook($id,ManagerRegistry $managerRegistry,BookRepository $bookRepository ): Response
    {
        $em=$managerRegistry->getManager();
        $dataid=$bookRepository->find($id);
        $em->remove($dataid);
        $em->flush();

        return $this->redirectToRoute('app_showbook');

       
    }

    #[Route('/bookdetails/{id}', name: 'app_bookdetails')]
    public function bookdetails($id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);
        return $this->render('book/bookdetails.html.twig', [
            'book' => $book
        ]);
    }






    



}
