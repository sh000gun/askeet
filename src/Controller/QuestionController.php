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
     * @Route("/question", name="question_list")
     */
    public function index()
    {
        $questionList = QuestionQuery::create()
            ->find();

        return $this->render('question/index.html.twig',
                array('questionList' => $questionList)
        );
    }

    /**
     * @Route("/question/new", name="question_new")
     */

    public function new(Request $request)
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

        return $this->render('question/new.html.twig',
                array('form' => $form->createView())
        );
    }
}
