<?php

namespace App\Entity;

use App\Entity\Base\Question as BaseQuestion;
use App\Lib\myTools;

use Michelf\Markdown;
use App\Entity\QuestionTagQuery;
use App\Lib\Tag;
use App\Entity\QuestionTag;

/**
 * Skeleton subclass for representing a row from the 'ask_question' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Question extends BaseQuestion
{
    public function setTitle($v)
    {
        parent::setTitle($v);
 
        $this->setStrippedTitle(myTools::stripText($v));
    }

    public function setBody($v)
    {
        parent::setBody($v);

        $v = htmlentities($v, ENT_QUOTES, 'UTF-8');
        $this->setHtmlBody(Markdown::defaultTransform($v));
  }

    public function getTags()
    {
        
       return QuestionTagQuery::create()
            ->filterByQuestionId($this->getId())
            ->select('NormalizedTag')
            ->distinct()
            ->orderByNormalizedTag('asc')
            ->find();    
     }

    public function getPopularTags($max = 5)
    {

        $popularTags = QuestionTagQuery::create()
            ->filterByQuestionId($this->getId())
            ->withColumn('count(QuestionTag.NormalizedTag)', 'popular')
            ->select('NormalizedTag')
            ->groupByNormalizedTag()
            ->orderBy('popular','desc')
            ->limit($max)
            ->find();

        $result = array();
        foreach ($popularTags as $tag)
        {
            array_push($result, $tag['NormalizedTag']);
        }

        return $result;
    }

    public function addTagsForUser($phrase, $userId)
    {
        // split phrase into individual tags
        $tags = Tag::splitPhrase($phrase);
 
        // add tags
         foreach ($tags as $tag)
        {
            $questionTag = new QuestionTag();
            $questionTag->setQuestionId($this->getId());
            $questionTag->setUserId($userId);
            $questionTag->setTag($tag);
            $questionTag->save();
        }
    }

}
