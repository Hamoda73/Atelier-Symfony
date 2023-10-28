<?php

namespace App\Controller;
use App\Repository\AuthorRepository;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\MinmaxType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
    );



    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    
    #[Route('/showauthor/{name}', name: 'app_showauthor')]
    public function showAuthor($name): Response
    {
        
        return $this->render('author/show.html.twig', [
           'name'=>$name
        ]);
        
    }

    #[Route('/showtable', name: 'showtable')]
    public function showtable(): Response
    {
       
        
        return $this->render('author/showtable.html.twig', [
            'authors' => $this->authors
        ]);
        
    }

    #[Route('/showbyidauthor/{id}', name: 'showbyidauthor')]
    public function showbyidauthor($id): Response
    {
        //var_dump($id).die();
        $author=null;
        foreach($this->authors as $authorD)
        {
                if($authorD['id']==$id){
                    $author=$authorD;
                }

        }
        //var_dump($author).die();

        return $this->render('author/showbyidauthor.html.twig', [
           'author'=>$author
        ]);
        
    }

    #[Route('/showdb', name: 'app_showdb')]
    public function showdb(AuthorRepository $authorRepository, Request $req): Response
    {
        $author = $authorRepository->findAll();

        $form = $this->createForm(MinmaxType::class);
        $form->handleRequest($req);

        if($form->isSubmitted()) {
            $min = $form->get('min')->getData();
            $max = $form->get('max')->getData();
            //$auhor = $authorRepository->searchbyusername($data);
            $author= $authorRepository->minmax($min, $max);

            return $this->renderForm('author/showdb.html.twig', [
                'author' => $author,
                'f' => $form
            ]);
            
        }
        return $this->renderForm('author/showdb.html.twig', [
            'author' => $author,
            'f' => $form
        ]);


    }

    #[Route('/showdbasc', name: 'app_showdbasc')]
    public function showdbasc(AuthorRepository $authorRepository): Response
    {
        $author = $authorRepository->orderbyemail();
        return $this->render('author/showdbasc.html.twig', [
            'author' => $author
        ]);
    }

    #[Route('/showdbzero', name: 'app_showdbzero')]
    public function showdbzero(AuthorRepository $authorRepository): Response
    {

        $author = $authorRepository->findAll();
        $supprimerzerobook = $authorRepository->supprimerzerobook();
        return $this->render('author/showdbzero.html.twig', [
            'author' => $author,
            'supprimerzerobook' => $supprimerzerobook
        ]);
    }



    #[Route('/addauthor', name: 'app_addauthor')]
    public function addauthor(ManagerRegistry $managerRegistry): Response
    {
        $em=$managerRegistry->getManager();
        $author=new Author();
        $author->setUsername("3a54new");
        $author->setEmail("3a54new@esprit.tn");
        $em->persist($author);
        $em->flush();

        return new Response("great add");
    }

    #[Route('/addformauthor', name: 'app_addformauthor')]
    public function addformauthor(ManagerRegistry $managerRegistry,Request $req): Response
    {
        $em=$managerRegistry->getManager();
        $author=new Author();
        $form=$this->createForm(AuthorType::class,$author);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid())
        {
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute('app_showdb');
        }

        return $this->renderForm('author/addformauthor.html.twig', [
            'f'=>$form
        ]);
    }

    #[Route('/editauthor/{id}', name: 'app_editauthor')]
    public function editauthor($id,AuthorRepository $authorRepository, ManagerRegistry $managerRegistry,Request $req): Response
    {
        //var_dump($id).die();

        $em=$managerRegistry->getManager();
        $dataid=$authorRepository->find($id);
        //var_dump($dataid).die();
        $form=$this->createForm(AuthorType::class,$dataid);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid())
        {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_showdb');
             
        }
       

        return $this->renderForm('author/editauthor.html.twig', [
            'x'=>$form
        ]);
    }

    #[Route('/deleteauthor/{id}', name: 'app_deleteauthor')]
    public function deleteauthor($id,AuthorRepository $authorRepository, ManagerRegistry $managerRegistry): Response
    {
        $em=$managerRegistry->getManager();
        $dataid=$authorRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('app_showdb');

        
    }






}
