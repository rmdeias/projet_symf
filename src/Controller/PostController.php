<?php

namespace App\Controller;

use App\Form\PostType;
use App\Repository\PostRepository;
use App\Entity\Post;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;

class PostController extends AbstractController
{
    private $_em;

    public function __construct (ManagerRegistry $managerRegistry){
        $this->_em = $managerRegistry;
    }

    #[Route('/post', name: 'post')]
    public function list()
    {
        $post    = $this->_em->getRepository(Post::class);

        $posts   = $post->findAll();
        return $this->render('post/index.html.twig', [
           'posts' => $posts
        ]);

    }


    /**
     * @Route("post/delete/{slug}", name="post_delete", methods={"GET"}))
     */
    public function delete(Request $request) : Response
    {
        $slug_request = $request->get('slug');
        $post    = $this->_em->getRepository(Post::class);
        $thePost = $post->findOneBy(['slug' => $slug_request]);
        $deletePost= $post->remove($thePost);
        return $this->redirectToRoute('post');
    }

    /**
     * @Route("post/new", name="post_new", methods={"GET" ,"POST"}))
     */
    public function new(Request $request, FileUploader $fileUploader) :Response
    {


        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            /** @var  UploadedFile $brochureFile */
            $brochureFile = $form->get('image')->getData();
            if($brochureFile){
                $brochureFileName = $fileUploader->upload($brochureFile);
                $post->setImage($brochureFileName);
            }

            $entityManager = $this->_em->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'post created'
            );
            return $this->redirectToRoute('post');

        }

        return $this->render('post/new.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("post/{slug}", name="post_show", methods={"GET"}))
     */
    public function show(Request $request) : Response
    {
        $slug_request = $request->get('slug');
        $post    = $this->_em->getRepository(Post::class);
        $thePost = $post->findOneBy(['slug' => $slug_request]);

        return $this->render('post/show.html.twig', [
            'thePost' => $thePost
        ]);

    }

}
