<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const SEASONS = [
            '1',
            '2', 
            '3', 
            '4', 
            '5'
    ];
    const YEARS = ['1999', '2002', '2005', '2012', '2017'];


    public function load(ObjectManager $manager)
    {  
        foreach(self::SEASONS as $key => $seasonNumber) {
            $season = new Season();
            $season->setNumber($seasonNumber);
            $season->setYear(self::YEARS[$key]);
            $season->setPrograms($this->getReference('program_' . $key));
            $this->addReference('season_' . $key, $season);
            $manager->persist($season);
        }
           $manager->flush();
    }


    public function getDependencies()
    {
        return [ProgramFixtures::class];
            
    }
}
