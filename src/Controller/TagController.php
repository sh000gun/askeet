<?php

namespace App\Controller;

use App\Entity\QuestionQuery;
use App\Entity\QuestionTagQuery;
use App\Form\Type\QuestionTagType;
use App\Lib\myQuestionTagValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @Route("tag/{normalizedTag}", name="tag_recent")
     */
    public function recent(Request $request, $normalizedTag, $page = 1)
    {
        $pager = QuestionQuery::getHomepagePager($page, $this->params->get('app_pager_homepage_max'), $normalizedTag);

        return $this->render('tag/listSuccess.html.twig', [
          'question_pager' => $pager,
          'tag' => $normalizedTag,
      ]);
    }

    /**
     * @Route("tag/autocomplete/{tag}", methods="GET", name="tag_autocomplete")
     */
    public function autocomplete($tag)
    {
        $tags = [];
        if ($this->getUser()) {
            $tags = QuestionTagQuery::getTagsForUserLike($this->getUser()->getId(), $tag, 10);
        }

        return $this->json($tags);
    }

    /**
     * @Route("tag/form/add", name="tag_add")
     */
    public function add(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $data = new myQuestionTagValidator();

            $form = $this->createForm(QuestionTagType::class, $data);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $this->getUser()) {
                $data = $form->getData();

                if (null != $data->tag) {
                    $question = QuestionQuery::create()
                        ->findPk($data->question_id);

                    if ($question) {
                        $question->addTagsForUser($data->tag, $this->getUser()->getId());

                        return new Response('success');
                    }
                }
            }
        }

        return new Response('failed');
    }

    /**
     * @Route("popular_tags", name="tag_popular")
     */
    public function popular(Request $request)
    {
        $tags = QuestionTagQuery::getPopularTags(40, $request->attributes->get('app_permanent_tag'));

        $response = $this->render('tag/popularSuccess.html.twig', [
            'tags' => $tags,
      ]);

        // cache publicly for 600 seconds
        $response->setPublic();
        $response->setMaxAge(600);

        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }
}
