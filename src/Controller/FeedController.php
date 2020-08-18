<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\QuestionQuery;

class FeedController extends AbstractController
{ 
    /**
     * @Route("feed/popular", name="feed_popular")
     */
    public function popular(Request $request)
    {
        $questions = QuestionQuery::getPopularQuestions(10);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');
        return $this->render('feed/popularSuccess.xml.twig',[
            'questions' => $questions,
            'guid' => uniqid(),
            'time' => time(),],
            $response
        );
    }
}
