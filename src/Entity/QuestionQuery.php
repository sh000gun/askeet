<?php

namespace App\Entity;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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
    private static function addPermanentTagToCriteria($query, $tag = null)
    {
        if ($tag) {
            $query->useQuestionTagQuery('a', 'left join')
                ->filterByNormalizedTag($tag)
                ->endUse();
        }

        return $query;

    }

    public static function getHomepagePager($page, $maxPage = 2, $tag = null)
    {
        $query = QuestionQuery::create()
            ->OrderBy('interested_users', 'desc');

        $query = self::addPermanentTagToCriteria($query, $tag);

        return $query->paginate($page, $maxPage);
    }

  public static function getQuestionFromTitle($title)
  {
    return QuestionQuery::create()
        ->filterByStrippedTitle($title)
        ->findOne();
  }

    public static function getRecentPager($page, $maxPage = 2, $tag = null)
    {
        $query = QuestionQuery::create()
            ->orderByCreatedAt('desc');

        $query = self::addPermanentTagToCriteria($query, $tag);

        return $query->paginate($page, $maxPage);
    }

    public static function getPopularQuestions($max, $tag = null)
    {

        $query = QuestionQuery::create()
            ->orderByCreatedAt('desc')
            ->limit($max);
        $query = self:: addPermanentTagToCriteria($query, $tag);

        return $query->find();
    }
}
