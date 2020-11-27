<?php

namespace App\Entity;

use App\Entity\Base\QuestionQuery as BaseQuestionQuery;
use App\Lib\myTools;

/**
 * Skeleton subclass for performing query and update operations on the 'ask_question' table.
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

    public static function getReportCount()
    {
        return QuestionQuery::create()
            ->where('Question.Reports > ?', 0)
            ->count();
    }

    public static function getReportPager()
    {
        $query = QuestionQuery::create()
             ->where('Question.Reports > ?', 0);

        return $query->paginate();
    }

    public static function deleteQuestion($questionId)
    {
        return QuestionQuery::create()
            ->filterById($questionId)
            ->delete();
    }

    public static function search($phrase, $exact = false, $offset = 1, $max = 10)
    {
        $words    = array_values(myTools::stemPhrase($phrase));
        $nb_words = count($words);
 
        if (!$words)
        {
            return array();
        }

        $query = QuestionQuery::create()
            ->useSearchIndexQuery()
                ->distinct()
                ->withColumn('COUNT(SearchIndex.Word)', 'nb')
                ->withColumn('SUM(SearchIndex.Weight)', 'total_weight');
        
        // https://stackoverflow.com/questions/52123758/multiple-propel-like-filters
        foreach($words as $i => $word)
        {
            if ($i > 0)
            { // Not the first item in the array
                $query->_or();
            }
            $query->where('SearchIndex.Word = ?', $word);
        }

        return $query->groupBy('SearchIndex.QuestionId')
            ->_if($exact)
                ->having('nb', $nb_words)
            ->_endif()
            ->orderBy('nb', 'desc')
            ->orderBy('total_weight', 'desc')
            ->endUse()
            ->paginate($offset, $max);
    }
}
