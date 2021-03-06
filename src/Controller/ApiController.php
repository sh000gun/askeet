<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     *  @Route ("api/question/{stripped_title}", name="api_question")
     */
    public function question($stripped_title)
    {
        $question = QuestionQuery::getQuestionFromTitle($stripped_title);

        if (!$question) {
            throw $this->createNotFoundException('Question does not exist');
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');

        return $this->render(
            'api/questionSuccess.xml.twig',
            [
            'question' => $question,
            ],
            $response
        );
    }
}
