<?php

namespace App\Entity;

use App\Entity\Base\QuestionTagQuery as BaseQuestionTagQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'ask_interest' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class QuestionTagQuery extends BaseQuestionTagQuery
{
    public static function getTags($questionId)
    {
        return QuestionTagQuery::create()
            ->filterByQuestionId($questionId)
            ->select('NormalizedTag')
            ->distinct()
            ->orderByNormalizedTag('asc')
            ->find();
    }

    public static function getTagsForUserLike($user_id, $tag, $max = 10)
    {
        return  QuestionTagQuery::create()
            ->where('QuestionTag.UserId = ?', $user_id)
            ->where('QuestionTag.Tag LIKE ?', $tag.'%')
            ->select('Tag')
            ->distinct()
            ->setLimit($max)
            ->find();

    }
    
    public static function getPopularTags($max = 5)
    {
        $tags = array();

        $popularTags = QuestionTagQuery::create()
            ->withColumn('count(QuestionTag.NormalizedTag)', 'count')
            ->select('NormalizedTag', 'count')
            ->groupBy('NormalizedTag')
            ->orderBy('count', 'desc')
            ->setLimit($max)
            ->find();
 

        $max_popularity = 0;

        foreach ($popularTags as $tag)
        {

            if (!$max_popularity)
            {
                $max_popularity = intval($tag['count']);
            }
 
            $tags[$tag['NormalizedTag']] = floor((intval($tag['count']) / $max_popularity * 3) + 1);
         }
 
        ksort($tags);
 
        return $tags;
    }

}
