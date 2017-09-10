<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * CategoryController
 *
 * @author Andy Hobbs <andyhobbs92@gmail.com>
 */
class CategoryController extends Controller
{
    /**
     * @Route("/category/store", name="category_store")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function storeAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('category_show', [
                'id' => $category->getId()
            ]));
        }

        return $this->render('AppBundle::category/store.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Category $category
     *
     * @Route("/category/{id}", name="category_show")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Category $category)
    {
        return $this->render('AppBundle::category/show.html.twig', [
            'category' => $this->getDoctrine()->getRepository('AppBundle:Category')->find($category)
        ]);

    }

    /**
     * @Route("/category/edit/{id}", name="category_edit")
     *
     * @param Request $request
     * @param Category $category
     * @return mixed
     */
    public function editAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($category);
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('category_show', [
                'id' => $category->getId()
            ]));
        }

        return $this->render('AppBundle::category/edit.html.twig', [
            'form' => $form->createView(),
            'model' => $category
        ]);
    }

    /**
     * @Route("/category/remove/{id}", name="category_remove")
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function removeAction($id)
    {
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }
}
