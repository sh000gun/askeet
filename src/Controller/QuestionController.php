<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Entity\Question;
use App\Entity\QuestionQuery;

use App\Form\Type\QuestionType;
use App\Form\Type\AnswerType;
use App\Lib\myQuestionValidator;
use App\Lib\myAnswerValidator;
use App\Lib\myQuestionTagValidator;
use App\Form\Type\QuestionTagType;

class QuestionController extends AbstractController
{ 
    private $params;
    
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @Route("/question/{page}", name="question_list", requirements={"page"="\d+"})
     */
    public function list(Request $request, $page = 1)
    {
      $pager = QuestionQuery::getHomepagePager($page, $this->params->get('app_pager_homepage_max'), $request->attributes->get('app_permanent_tag'));
      
      return $this->render('question/listSuccess.html.twig',
                array('question_pager' => $pager));
    }

    /**
     * @Route("question/recent/{page}", name="question_recent", requirements={"page"="\d+"})
     */
    public function recent(Request $request, $page = 1)
    {
      $pager = QuestionQuery::getRecentPager($page, $this->params->get('app_pager_homepage_max'), $request->attributes->get('app_permanent_tag'));

      return $this->render('question/recentSuccess.html.twig',
              array('question_pager' => $pager));
    }

    /**
     * @Route("/question/add", name="question_add")
     */
    public function add(Request $request)
    { 
        $data = new myQuestionValidator();
        $form = $this->createForm(QuestionType::class, $data);
        $form->handleRequest($request);

                               
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $question = new Question();
            $question->setTitle($data->title);
            $question->setBody($data->body);
            $question->setUser($this->getUser());
            
            // save into database
            $question->save();

            $this->getUser()->isInterestedIn($question);

            if ($request->attributes->get('app_permanent_tag'))
            {
                 $question->addTagsForUser($request->attributes->get('app_permanent_tag'), $this->getUser()->getId());
            }

            return $this->redirectToRoute('question_show', array('stripped_title' => $question->getStrippedTitle()));
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

      $data = new myAnswerValidator();
      $data->question_id = $question->getId();
      $form = $this->createForm(AnswerType::class, $data);
    
      $tagData = new myQuestionTagValidator();
      $tagData->question_id = $question->getId();
      $tagForm = $this->createForm(QuestionTagType::class, $tagData);

      return $this->render('question/showSuccess.html.twig', [
          'question' => $question,
          'form' => $form->createView(),
          'tagForm' => $tagForm->createView(),
      ]
      );
    }
}
