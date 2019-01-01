<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SphinxSearchService;
use App\Form\SearchFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SphinxSearchService $sphinxSearchService, Request $request)
    {
        $searchForm = $this->createForm(SearchFormType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $posts = $sphinxSearchService->getList();
        } else {
            $postRepository = $this->getDoctrine()->getRepository(Post::class);
            $posts = $postRepository->findAll();
        }

        return $this->render('site/index.html.twig', [
            'posts' => $posts,
            'searchForm' => $searchForm->createView()
        ]);
    }
}
