<?php

namespace Shakaran\FaqBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class QuestionController
 *
 * @package Shakaran\FaqBundle\Controller
 */
class QuestionController extends AbstractController
{
    /**
     * shows question if active
     *
     * @param string $slug
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        $question        = $this->getQuestionRepository()->findOneBySlug($slug);

        if (!$question || (!$question->isPublic() && !$securityContext->isGranted('ROLE_EDITOR'))) {
            throw $this->createNotFoundException('question not found');
        }

        return $this->render(
            'ShakaranFaqBundle:Question:show.html.twig',
            array(
                'question' => $question
            )
        );
    }

    /**
     * list most recent added questions based on publishAt
     *
     * @param int $max
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listMostRecentAction($max = 3)
    {
        $questions = $this->getQuestionRepository()->retrieveMostRecent($max);

        return $this->render(
            'ShakaranFaqBundle:Question:list_most_recent.html.twig',
            array(
                'questions' => $questions,
                'max'       => $max
            )
        );
    }

    /**
     * list questions which fitting the query
     *
     * @param string $query
     * @param int    $max
     * @param array  $whereFields
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listByQueryAction($query, $max = 30, $whereFields = array('headline', 'body'))
    {
        $questions = $this->getQuestionRepository()->retrieveByQuery($query, $max, $whereFields);

        return $this->render(
            'ShakaranFaqBundle:Question:list_by_query.html.twig',
            array(
                'questions' => $questions,
                'max'       => $max
            )
        );
    }

    /**
     * @param int       $id
     * @param \stdClass $object
     * @param string    $style
     * @param string    $source
     * @param string    $headline
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function teaserByIdOrObjectAction($id = null, $object = null, $style = null, $source = null, $headline = null)
    {
        $question = $object;

        if ($id !== null) {
            $question = $this->getQuestionRepository()->findOneById($id);
        }

        return $this->render(
            'ShakaranFaqBundle:Question:teaser_by_id_or_object.html.twig', array(
                'question' => $question,
                'style'    => $style,
                'source'   => $source,
                'headline' => $headline
            )
        );
    }

    /**
     * @return \Shakaran\FaqBundle\Entity\QuestionRepository
     */
    protected function getQuestionRepository()
    {
        return $this->container->get('shakaran_faq.entity.question_repository');
    }
}
