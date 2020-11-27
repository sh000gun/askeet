<?php

namespace App\Entity;

use App\Entity\Base\Question as BaseQuestion;
use App\Lib\myTools;
use App\Lib\Tag;
use Michelf\Markdown;
use App\Lib\PorterStemmer;
use Propel\Runtime\Propel;

/**
 * Skeleton subclass for representing a row from the 'ask_question' table.
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

    public function getTags($permanentTag = null)
    {
        return QuestionTagQuery::create()
           ->filterByQuestionId($this->getId())
           ->_if($permanentTag)
               ->where('QuestionTag.Tag != ?', $permanentTag)
            ->_endif()
           ->select('NormalizedTag')
            ->distinct()
            ->orderByNormalizedTag('asc')
            ->find();
    }

    public function getPopularTags($max = 5, $permanentTag = null)
    {
        $popularTags = QuestionTagQuery::create()
            ->filterByQuestionId($this->getId())
            ->withColumn('count(QuestionTag.NormalizedTag)', 'popular')
            ->_if($permanentTag)
               ->where('QuestionTag.Tag != ?', $permanentTag)
            ->_endif()
            ->select('NormalizedTag')
            ->groupByNormalizedTag()
            ->orderBy('popular', 'desc')
            ->limit($max)
            ->find();

        $result = [];
        foreach ($popularTags as $tag) {
            array_push($result, $tag['NormalizedTag']);
        }

        return $result;
    }

    public function addTagsForUser($phrase, $userId, $tag = null)
    {
        // split phrase into individual tags
        $tags = Tag::splitPhrase($phrase);

        // add tags
        foreach ($tags as $tag) {
            $questionTag = new QuestionTag();
            $questionTag->setQuestionId($this->getId());
            $questionTag->setUserId($userId);
            $questionTag->setTag($tag);
            $questionTag->save();
        }
    }

    public function save($con = null)
{
   $con = Propel::getConnection();
  try
  {
     $con->beginTransaction();
 
    $ret = parent::save($con);
    $this->updateSearchIndex();
 
    $con->commit();
 
    return $ret;
  }
  catch (Exception $e)
  {
    $con->rollback();
    throw $e;
  }
}

    public function updateSearchIndex()
{
  // delete existing SearchIndex entries about the current question
  SearchIndexQuery::create()
    ->filterByQuestionId($this->getId())
    ->delete();   
 
  // create a new entry for each of the words of the question
  foreach ($this->getWords() as $word => $weight)
  {
    $index = new SearchIndex();
    $index->setQuestionId($this->getId());
    $index->setWord($word);
    $index->setWeight($weight);
    $index->save();
  }
}
 
public function getWords()
{
  // body
  $raw_text =  str_repeat(' '.strip_tags($this->getHtmlBody()), 1);
 
  // title
  $raw_text .= str_repeat(' '.$this->getTitle(), 2);
 
  // title and body stemming
  $stemmed_words = myTools::stemPhrase($raw_text);
 
  // unique words with weight
  $words = array_count_values($stemmed_words);
 
  // add tags
  $max = 0;
  foreach ($this->getPopularTags(20) as $tag => $count)
  {
    if (!$max)
    {
      $max = $count;
    }
 
    $stemmed_tag = PorterStemmer::stem($tag);
 
    if (!isset($words[$stemmed_tag]))
    {
      $words[$stemmed_tag] = 0;
    } else {
        $words[$stemmed_tag] += ceil(($count / $max) * 3);
    }
  }
 
  return $words;
}
}
