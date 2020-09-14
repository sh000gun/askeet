<?php

namespace App\Controller;

use App\Entity\QuestionQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController
{
    /**
     * @Route("feed/popular", name="feed_popular")
     */
    public function popular(Request $request)
    {
        $questions = QuestionQuery::getPopularQuestions(10, $request->attributes->get('app_permanent_tag'));

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');

        return $this->render(
            'feed/popularSuccess.xml.twig',
            [
            'questions' => $questions,
            'guid' => uniqid(),
            'time' => time(), ],
            $response
        );
    }
}
