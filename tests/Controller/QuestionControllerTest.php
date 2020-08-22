<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuestionControllerTest extends WebTestCase
{
    public function testShowQuestion()
    {
        $client = static::createClient();

        $client->request('GET', 'question/show/what-can-i-offer-to-my-step-mother');

        $this->assertStringContainsString('My stepmother has everything a stepmother is usually offered', $client->getResponse()->getContent());
    }

    public function test_QuestionAdd()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/question');

        $link = $crawler->selectLink('sign in')->link();
        $client->click($link);
        $this->assertStringContainsString('Nickname', $client->getResponse()->getContent());
        $crawler = $client->submitForm('sign in', [
            'login[nickname]' => 'fabpot',
            'login[password]' => 'symfony'
        ]);
        $crawler = $client->followRedirect();
        $this->assertStringContainsString('fabpot profile', $client->getResponse()->getContent());
    }
}   
