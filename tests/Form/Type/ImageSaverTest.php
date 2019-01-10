<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/01/2019
 * Time: 11:45
 */

namespace App\Tests\Form\Type;


use App\Form\ShiniPlayerType;
use App\Entity\ShiniPlayer;
use Symfony\Component\Form\Test\TypeTestCase;

class ImageSaverTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $playerData = array(
            'name' => 'Nom',
            'lastname' => 'Prénom',
            'nickName' => 'Pseudo',
            'birthday' => new \DateTime(),
            'address' => 'Une adresse',
            'postal_code' => '78200',
            'city' => 'Mantes-la-jolie',
            'phone' => '0101010101',
            'email' => 'test@email.com',
            'cardCode' => 'Test',
            'password' => 'Un mot de passe'
        );

        $playerToCompare = new ShiniPlayer();

        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(ShiniPlayer::class, $playerToCompare);

        // Create an object to compare
        $player = $this->player($playerData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    private function player(array $playerData)
    {

        $player = new ShiniPlayer();
        $player->setName($playerData['name']);
        $player->setLastname($playerData['lastname']);
        $player->setNickName($playerData['nickName']);
        $player->setBirthday($playerData['birthday']);
        $player->setAddress($playerData['address']);
        $player->setPostalCode($playerData['postal_code']);
        $player->setCity($playerData['city']);
        $player->setPhone($playerData['phone']);
        $player->setEmail($playerData['email']);
        $player->setCardCode($playerData['cardCode']);
        $player->setPassword($playerData['password']);

        return $player;

    }

}