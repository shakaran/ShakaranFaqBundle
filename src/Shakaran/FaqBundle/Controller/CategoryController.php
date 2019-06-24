<?php

namespace Shakaran\FaqBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CategoryController
 *
 * @package Shakaran\FaqBundle\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * shows questions within 1 category
     *
     * @param string $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug)
    {
        $category = $this->getCategoryRepository()->retrieveActiveBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('category doesnt exists');
        }

        return $this->render(
            'ShakaranFaqBundle:Category:show.html.twig',
            array(
                'category' => $category
            )
        );
    }

    /**
     * list all active categories
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listActiveAction()
    {
        $categories = $this->getCategoryRepository()->retrieveActive();

        return $this->render(
            'ShakaranFaqBundle:Category:list_active.html.twig',
            array(
                'categories' => $categories
            )
        );
    }

    /**
     * @return \Shakaran\FaqBundle\Entity\CategoryRepository
     */
    protected function getCategoryRepository()
    {
        return $this->container->get('shakaran_faq.entity.category_repository');
    }
}
