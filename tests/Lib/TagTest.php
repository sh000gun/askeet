<?php

namespace App\Tests\Lib;

use App\Lib\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function test_normalize()
    {
        $tests = array(
            'FOO'       => 'foo',
            '   foo'    => 'foo',
            'foo  '     => 'foo',
            ' foo '     => 'foo',
            'foo-bar'   => 'foobar',
        );
 
        foreach ($tests as $tag => $normalized_tag)
        {
            $this->assertEquals($normalized_tag, Tag::normalize($tag));
        }
    }

    public function test_splitPhrase()
    {
        $tests = array(
            'foo'              => array('foo'),
            'foo bar'          => array('foo', 'bar'),
            '  foo    bar  '   => array('foo', 'bar'),
            '"foo bar" askeet' => array('foo bar', 'askeet'),
            "'foo bar' askeet" => array('foo bar', 'askeet'),
        );
 
        foreach ($tests as $tag => $tags)
        {
            $this->assertEquals($tags, Tag::splitPhrase($tag));
         }
    }
}
