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
 * CommentController
 *
 * @author Andy Hobbs <andyhobbs92@gmail.com>
 */
class CommentController extends Controller
{

    /**
     * @Route("/comment/ajax-add-comment", name="ajax_add_comment")
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
        $parentId = $request->get('parent');
        $className = $request->get('type');

        $parentPost = null;
        if ($className === Post::class) {
            $parentPost = $em->getRepository('AppBundle:Post')->find($parentId);
        }

        $parentCategory = null;
        if ($className === Category::class) {
            $parentCategory = $em->getRepository('AppBundle:Category')->find($parentId);
        }

        $comment = (new Comment())
            ->setParentCategory($parentCategory)
            ->setParentPost($parentPost)
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
