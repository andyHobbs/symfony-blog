<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * PostController
 *
 * @author Andy Hobbs <andyhobbs92@gmail.com>
 */
class PostController extends Controller
{
    /**
     * @Route("/{category}", name="homepage")
     *
     * @param Category $category
     *
     * @return mixed
     */
    public function indexAction(Category $category = null)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if ($category) {
            $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->findByCategory($category);
        } else {
            $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->getLatestPosts(10);
        }
        return $this->render('AppBundle::post/index.html.twig', [
            'categories' => $categories,
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/post/store", name="post_store")
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function storeAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('post_show', [
                'id' => $post->getId()
            ]));
        }

        return $this->render('AppBundle::post/store.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/edit/{id}", name="post_edit")
     *
     * @param Request $request
     * @param Post $post
     *
     * @return mixed
     */
    public function editAction(Request $request, Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->find($post);
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('post_show', [
                'id' => $post->getId()
            ]));
        }

        return $this->render('AppBundle::post/edit.html.twig', [
            'form' => $form->createView(),
            'model' => $post
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_show")
     *
     * @param Post $post
     *
     * @return mixed
     */
    public function showAction(Post $post)
    {
        return $this->render('AppBundle::post/show.html.twig', [
            'post' => $this->getDoctrine()->getRepository('AppBundle:Post')->find($post)
        ]);

    }

    /**
     * @Route("/post/remove/{id}", name="post_remove")
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function removeAction($id)
    {
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/ajax/add-comment", name="ajax_add_comment")
     *
     * @param Request $request
     *
     * @throws BadRequestHttpException
     *
     * @return JsonResponse
     */
    public function ajaxAddComment(Request $request)
    {

        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException('Bad Request');
        }

        $em = $this->getDoctrine()->getManager();

        $author  = $request->get('author');
        $content = $request->get('content');
        $postId = $request->get('post');
        $post = $em->getRepository('AppBundle:Post')->find($postId);

        $comment = (new Comment())
            ->setPost($post)
            ->setAuthor($author)
            ->setContent($content);

        $em->persist($comment);
        $em->flush();

        return new JsonResponse([
            'author' => $comment->getAuthor(),
            'content' => $comment->getContent(),
            'time' => $comment->getCreatedAt()->format('H:i d-m-Y'),
            'status'  => true,
            'message' => 'Success',
        ]);
    }
}
