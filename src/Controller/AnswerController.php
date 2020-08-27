<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Entity\AnswerQuery;
use App\Entity\Answer;
use App\Entity\UserQuery;

use App\Form\Type\QuestionType;
use App\Form\Type\AnswerType;
use App\Lib\myAnswerValidator;

class AnswerController extends AbstractController
{ 
    private $params;
    
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @Route("answer/recent/{page}", name="answer_recent", requirements={"page"="\d+"})
     */
    public function recent(Request $request, $page = 1)
    {
      $pager = AnswerQuery::getRecentPager($page, $this->params->get('app_pager_homepage_max'), $request->attributes->get('app_permanent_tag'));

    //dd($pager);


      return $this->render('answer/recentSuccess.html.twig',
              array('answer_pager' => $pager));
    }
    
   /**
     * @Route("answer/add", name="answer_add")
     */
    public function add(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $data = new myAnswerValidator();
    
            $form = $this->createForm(AnswerType::class, $data);
            $form->handleRequest($request);
                               
            if ($form->isSubmitted()) 
            {
                $data = $form->getData();
                $answer = new Answer();

                if ($data->body != null)
                {
                    $user_id = $this->getUser() ? $this->getUser()->getId() : UserQuery::retrieveByNickname('anonymous')->getId();

                    $answer->setBody($data->body);
                    $answer->setUserId($user_id);
                    $answer->setQuestionId($data->question_id);
                    $answer->save();
                    
                    return new Response('success');
;
                }

            } 
        }

         return new Response('failed');
    }
}
