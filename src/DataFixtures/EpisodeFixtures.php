<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const EPISODES = [
        'Episode 1',
        'Episode 2',
        'Episode 3',
        'Episode 4',
        'Episode 5',
    ];

    protected $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;        
    }

    public function load(ObjectManager $manager)
    {
        foreach(self::EPISODES as $key => $episodeTitle) {
            $episode = new Episode ();
            $episode->setNumber($key + 1);
            $episode->setTitle($episodeTitle);
            $episode->setSlug($this->slugify->generate($episodeTitle));
            $episode->setSeason($this->getReference('season_' . $key));
            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
