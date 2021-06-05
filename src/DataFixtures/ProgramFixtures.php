<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        'Smallville',
        'Charmed',
        'H',
        'Narcos',
        'Platane',
    ];

    const SUMMARIES = [
        'La Jeunesse de Clark Kent avant Superman dans une petite ville du Kansas',
        'Trois soeurs-sorcières sont à la fois soeurs et sorcières',
        'L\'histoire d\'un hopital ou il ne fait pas bon aller',
        "La vie et l'oeuvre de Pablo Escobar",
        "La vie et l'oeuvre de Eric Judor",
    ];

    public function load(ObjectManager $manager)
    {   
        foreach (self::PROGRAMS as $key => $programTitle) {
            $program = new Program();
            $program->setTitle($programTitle);
            $program->setSummary(self::SUMMARIES[$key]);
            $program->setCategory($this->getReference('category_' . $key));
            $this->addReference('program_' . $key, $program);
            for ($i = 0; $i < count(ActorFixtures::ACTORS); $i++){
                $program->addActor($this->getReference('actor_' . $i));
            }
            $manager->persist($program);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
            ];
    }
}
