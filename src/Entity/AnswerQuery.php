<?php

namespace App\Entity;

use App\Entity\Base\AnswerQuery as BaseAnswerQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'ask_answer' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class AnswerQuery extends BaseAnswerQuery
{
  public static function getRecentPager($page)
  {
    $query = AnswerQuery::create()
      ->orderByCreatedAt('desc');

    return $query->paginate($page, 2);
  }
}
