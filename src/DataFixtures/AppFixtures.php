<?php

namespace App\DataFixtures;

use App\Entity\ShiniAdmin;
use App\Entity\ShiniCenter;
use App\Entity\ShiniOffer;
use App\Entity\ShiniPlayer;
use App\Entity\ShiniStaff;
use App\Entity\ShiniPlayerAccount;
use App\Repository\ShiniPlayerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;


class AppFixtures extends Fixture
{
    private $code;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $this->generatePlayers($manager,$faker);
        $centers = $this->generateCenters($manager,$faker);

        // A Staff is linked to an offer
        $staffAdvisers = $this->generateStaff($manager,$faker, $centers);
        $this->generateOffer($manager,$faker,$staffAdvisers);

        $this->generateAdmin($manager,$faker);

        $this->generateWellKnownUser($manager,$faker, $centers);

    }

    public function generatePlayers(ObjectManager $manager,Generator $faker)
    {

        for($i=0; $i<200; $i++)
        {
            $player= new ShiniPlayer();
            $account= new ShiniPlayerAccount($player);
            $account->setConfirmedAt(new \DateTime());
            $player->setName($faker->firstName)
                ->setLastname($faker->lastName)
                ->setNickName($faker->userName)
                ->setEmail($faker->email)
                ->setPassword('Aa!00000')
                //->setPassword('o@bPShxT@u@9GuH%Ji3Y')
                //->setPassword($faker->regexify("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([!@#$%^&*\w]{8,20})$/"))
                ->setAddress($faker->buildingNumber.' '.$faker->streetName)
                ->setCity($faker->city)
                ->setPostalCode($faker->postcode(5))
                ->setPhone($faker->phoneNumber(10))
                ->setBirthday($faker->dateTimeThisCentury)
            ;
            //$account->setPlayer($player);
            $manager->persist($player);
            $manager->persist($account);
        }
        $manager->flush();
    }

    public function generateCenters(ObjectManager $manager,Generator $faker)
    {
        $centers = [];
        for($i=340; $i<351; $i++)
        {
            $center = new ShiniCenter();
            //$code = ['340','350','360'];
            //$center->setCode($faker->randomElement($code))
            //$center->setCode($faker->randomElement($code = ['340','350','360']));
            $center->setCode($i);
            $centers[] = $center;
            $manager->persist($center);
        }

        $manager->flush();
        return $centers;
    }

    public function generateOffer(ObjectManager $manager,Generator $faker, $staffAdvisers)
    {
        for($i=0; $i<10; $i++)
        {
            $offer = new ShiniOffer();
            $offer->setName($faker->word(1))
                ->setPrice($faker->randomFloat(2,50,1000))
                ->setDateEnd($faker->dateTimeThisCentury($min = 'now', $timezone = 'Europe/Paris'))
                ->setDescription($faker->text)
                ->setImage('https://picsum.photos/300/200/?image='.$i)
                ->setShown($faker->boolean(50))
                ->setOnfirstpage(0)
                ->setStaffAdviser($staffAdvisers[array_rand($staffAdvisers)])
            ;
            $manager->persist($offer);
        }
        $manager->flush();
    }

    public function generateStaff(ObjectManager $manager,Generator $faker, $centers)
    {

        $staffAdvisers = [];
        for($i=0; $i<1; $i++)
        {
            $staff = new ShiniStaff();
            $staff->setName($faker->firstName)
            ->setLastname($faker->lastName)
            ->setNickName($faker->userName)
            ->setEmail($faker->email)
            ->setPassword('o@bPShxT@u@9GuH%Ji3Y')
            ->setCenter($centers[array_rand($centers)])
            //->setPassword($faker->regexify("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([!@#$%^&*\w]{8,20})$/"))
            ->setAddress($faker->buildingNumber.' '.$faker->streetName)
            ->setCity($faker->city)
            ->setPostalCode($faker->postcode(5))
            ->setPhone($faker->phoneNumber(10))
            ->setBirthday($faker->dateTimeThisCentury);
            $staffAdvisers[] = $staff;
            $manager->persist($staff);
        }
        $manager->flush();
        return $staffAdvisers;
    }

    public function generateAdmin(ObjectManager $manager,Generator $faker)
    {
        for($i=0; $i<3; $i++)
        {
            $admin= new ShiniAdmin();
            $admin->setName($faker->firstName)
                ->setLastname($faker->lastName)
                ->setNickName('Admin_'.$i)
                ->setEmail($faker->email)
                ->setPassword('Aa!00000')
                ->setAddress($faker->buildingNumber.' '.$faker->streetName)
                ->setCity($faker->city)
                ->setPostalCode($faker->postcode(5))
                ->setPhone($faker->phoneNumber(10))
                ->setBirthday($faker->dateTimeThisCentury)
            ;
            $manager->persist($admin);
        }
        $manager->flush();
    }

    /**
     * Create three well known user
     *
     * @param ObjectManager $manager
     * @throws \Exception
     *
     */
    public function generateWellKnownUser(ObjectManager $manager,Generator $faker, $centers){

        $admin= new ShiniAdmin();
        $admin->setName($faker->firstName)
            ->setLastname($faker->lastName)
            ->setNickName('Admin')
            ->setEmail('admin@shinigami.fr')
            ->setPassword('Aa!00000')
            ->setAddress($faker->buildingNumber.' '.$faker->streetName)
            ->setCity($faker->city)
            ->setPostalCode($faker->postcode(5))
            ->setPhone($faker->phoneNumber(10))
            ->setBirthday($faker->dateTimeThisCentury)
        ;

        $staff = new ShiniStaff();
        $staff->setName($faker->firstName)
            ->setLastname($faker->lastName)
            ->setNickName('Staff')
            ->setEmail('staff@shinigami.fr')
            ->setPassword('Aa!00000')
            ->setCenter($centers[0])
            //->setPassword($faker->regexify("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([!@#$%^&*\w]{8,20})$/"))
            ->setAddress($faker->buildingNumber.' '.$faker->streetName)
            ->setCity($faker->city)
            ->setPostalCode($faker->postcode(5))
            ->setPhone($faker->phoneNumber(10))
            ->setBirthday($faker->dateTimeThisCentury);

        $player= new ShiniPlayer();
        $account= new ShiniPlayerAccount($player);
        $account->setConfirmedAt(new \DateTime());
        $player->setName($faker->firstName)
            ->setLastname($faker->lastName)
            ->setNickName('Player')
            ->setEmail('player@shinigami.fr')
            ->setPassword('Aa!00000')
            ->setAddress($faker->buildingNumber.' '.$faker->streetName)
            ->setCity($faker->city)
            ->setPostalCode($faker->postcode(5))
            ->setPhone($faker->phoneNumber(10))
            ->setBirthday($faker->dateTimeThisCentury)
        ;
        //$account->setPlayer($player);
        $manager->persist($player);
        $manager->persist($account);
        $manager->persist($staff);
        $manager->persist($admin);
        $manager->flush();
    }
}
