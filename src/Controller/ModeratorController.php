<?php

namespace App\Controller;

use App\Entity\AnswerQuery;
use App\Entity\QuestionQuery;
use App\Entity\QuestionTagQuery;
use App\Entity\ReportQuestionQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\ReportAnswerQuery;
use Propel\Runtime\Propel;
use Monolog\Logger;

class ModeratorController extends AbstractController
{
    /**
     * @Route("moderator/reportedQuestions", name="moderator_reportedQuestions")
     */
    public function reportedQuestions(Request $request)
    {
        $pager = QuestionQuery::getReportPager();

        return $this->render(
            'question/listSuccess.html.twig',
            ['question_pager' => $pager]
        );
    }

    /**
     * @Route("moderator/reportedAnswers", name="moderator_reportedAnswers")
     */
    public function reportedAnswers(Request $request)
    {
        $pager = AnswerQuery::getReportPager();

        return $this->render(
            'answer/recentSuccess.html.twig',
            ['answer_pager' => $pager]
        );
    }

    /**
     * @Route("moderator/unpopularTags", name="moderator_unpopularTags")
     */
    public function unpopularTags(Request $request)
    {
        $tags = QuestionTagQuery::getUnpopularTags();

        return $this->render('tag/popularSuccess.html.twig', [
            'tags' => $tags,
      ]);
    }

    /**
     * @Route("moderator/resetQuestionReports/{questionId}", name="moderator_resetQuestionReports")
     */
    public function resetQuestionReports(Request $request, $questionId)
    {
        if (ReportQuestionQuery::resetReport($questionId)) {
            return new Response('success');
        }

        return new Response('failed');
    }

    /**
     * @Route("moderator/deleteQuestion/{questionId}", name="moderator_deleteQuestion")
     */
    public function deleteQuestion(Request $request, $questionId)
    {

        $question = QuestionQuery::create()
        ->filterById($questionId)
        ->findOne();

        if (!$question)
        {
            return new Response('failed');
        }

        $con = Propel::getConnection();

        try
        {
            $con->beginTransaction();

            $user = $question->getUser();
            $user->setDeletions($user->getDeletions() + 1);
            $user->save();
 
            $question->delete();
 
            $con->commit();
 
            // log the deletion
            $log = 'moderator "%s" deleted question "%s"';
            $log = sprintf($log, $this->getUser()->getNickname(), $question->getTitle());
            Propel::log($log, Logger::WARNING);

        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }


        return new Response('success');
    }

    /**
     * @Route("moderator/resetAnswerReports/{answerId}", name="moderator_resetAnswerReports")
     */
    public function resetAnswerReports(Request $request, $answerId)
    {
        if (ReportAnswerQuery::resetReport($answerId)) {
            return new Response('success');
        }

        return new Response('failed');
    }

    /**
     * @Route("moderator/deleteAnswer/{answerId}", name="moderator_deleteAnswer")
     */
    public function deleteAnswer(Request $request, $answerId)
    {
        if (AnswerQuery::deleteAnswer($answerId)) {
            return new Response('success');
        }

        return new Response('failed');
    }

}
