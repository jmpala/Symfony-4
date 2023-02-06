<?php

namespace App\DataFixtures;

use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\QuestionTagFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        TagFactory::createMany(100);

//        EXAMPLE
//        $question = QuestionFactory::createMany(20, function () {
//            return [
//                'questionTags' => QuestionTagFactory::new( function () {
//                    return [
//                        'tag' => TagFactory::random(),
//                        ];
//                })->many(5)
//            ];
//        });

        $question = QuestionFactory::createMany(20);

        QuestionTagFactory::createMany(100, function () use ($question) {
            return [
                'tag' => TagFactory::random(),
                'question' => QuestionFactory::random(),
            ];
        });

        QuestionFactory::new()
            ->unpublished()
            ->many(5)
            ->create();

        AnswerFactory::createMany(100, function() use ($question) {
            return [
                'question' => $question[array_rand($question)],
            ];
        });

        AnswerFactory::new(
            function() use ($question) {
                return [
                    'question' => $question[array_rand($question)],
                ];
            })
            ->needsApproval()
            ->many(20)
            ->create();

        $manager->flush();
    }
}
