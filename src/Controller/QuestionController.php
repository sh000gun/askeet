<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Question;
use App\Entity\QuestionQuery;

use App\Form\Type\QuestionType;

class QuestionController extends AbstractController
{ 
    /**
     * @Route("/question/{page}", name="question_list", requirements={"page"="\d+"})
     */
    public function list($page = 1)
    {
      $pager = QuestionQuery::getHomepagePager($page);
      
      return $this->render('question/listSuccess.html.twig',
                array('question_pager' => $pager));
    }

    /**
     * @Route("question/recent/{page}", name="question_recent", requirements={"page"="\d+"})
     */
    public function recent($page = 1)
    {
      $pager = QuestionQuery::getRecentPager($page);

      return $this->render('question/recentSuccess.html.twig',
              array('question_pager' => $pager));
    }

    /**
     * @Route("/question/add", name="question_add")
     */

    public function add(Request $request)
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
              // save into database
              $question = $form->getData();
              $question->save();

              return $this->redirectToRoute('question_list');
            }
        }

        return $this->render('question/editSuccess.html.twig',
                array('form' => $form->createView())
              );
    }

    /**
     *  @Route ("question/show/{stripped_title}", name="question_show")
     */
    public function show($stripped_title)
    {
      $question = QuestionQuery::getQuestionFromTitle($stripped_title);

      return $this->render('question/showSuccess.html.twig',
              array('question' => $question)
      );
    }
}
