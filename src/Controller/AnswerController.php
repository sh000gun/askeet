<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\AnswerQuery;

use App\Form\Type\QuestionType;

class AnswerController extends AbstractController
{ 
    /**
     * @Route("answer/recent/{page}", name="answer_recent", requirements={"page"="\d+"})
     */
    public function recent($page = 1)
    {
      $pager = AnswerQuery::getRecentPager($page);

      return $this->render('answer/recentSuccess.html.twig',
              array('answer_pager' => $pager));
    }

}
