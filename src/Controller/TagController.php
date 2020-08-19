<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\QuestionQuery;

class TagController extends AbstractController
{ 
    /**
     * @Route("tag/{normalizedTag}", name="tag_popular")
     */
    public function recent($normalizedTag, $page = 1)
    {
      $pager = QuestionQuery::getPopularByTag($normalizedTag, $page);

      return $this->render('tag/listSuccess.html.twig', [
          'question_pager' => $pager,
          'tag' => $normalizedTag,
      ]);
    }
}
