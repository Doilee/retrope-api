<?php

namespace Tests\Feature;

use Antoineaugusti\LaravelSentimentAnalysis\SentimentAnalysis;
use Tests\TestCase;

class SentimentTest extends TestCase
{
    protected function setUp()
    {
        $this->markTestIncomplete();
    }

    public function testPositive()
    {
        $messages = [
            'This weather looks gorgeous today.',
            'She\'s the real winner of the team, she did all that work using just her mouse!',
            'I wish I could be as sneaky as her, it\'s her greatest asset.' // Try a weird sentence
        ];

        foreach ($messages as $message) {
            $this->assertEquals('positive', $this->sentiment($message));
        }
    }

    public function testNegative()
    {
        $messages = [
            'I\m not super fan of this colleague.',
            'He\'s a total creep',
            'She\'s so possessive, I don\'t like her!'
        ];

        foreach ($messages as $message) {
            $this->assertEquals('negative', $this->sentiment($message));
        }
    }

    public function testNeutral()
    {
        $messages = [
            'I like his code, but he is creepy.'
        ];

        foreach ($messages as $message) {
            $this->assertEquals('neutral', $this->sentiment($message));
        }
    }

    private function sentiment($message)
    {
        return (new SentimentAnalysis)->decision($message);
    }
}
