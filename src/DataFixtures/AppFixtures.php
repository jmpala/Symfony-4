<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $question = QuestionFactory::createMany(20);

        QuestionFactory::new()
            ->unpublished()
            ->many(5)
            ->create()
        ;

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

        $question = QuestionFactory::createOne();

        $tag1 = new Tag();
        $tag1->setName('dino');
        $tag2 = new Tag();
        $tag2->setName('dino');

        $question->addTag($tag1);
        $question->addTag($tag2);

        $manager->persist($tag1);
        $manager->persist($tag2);

        $manager->flush();
    }
}
