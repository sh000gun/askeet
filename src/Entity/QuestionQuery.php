<?php

namespace App\Entity;

use App\Entity\Base\QuestionQuery as BaseQuestionQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'ask_question' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class QuestionQuery extends BaseQuestionQuery
{
  public static function getHomepagePager($page)
  {
     $query = QuestionQuery::create()
        ->orderByInterestedUsers('desc');

     return $query->paginate($page, 2);
  }

  public static function getQuestionFromTitle($title)
  {
    return QuestionQuery::create()
        ->filterByStrippedTitle($title)
        ->findOne();
  }

   public static function getRecentPager($page)
  {
     $query = QuestionQuery::create()
        ->orderByCreatedAt('desc');

     return $query->paginate($page, 2);
  }

}
