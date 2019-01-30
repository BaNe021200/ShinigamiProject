<?php

namespace App\Tests;

use App\Entity\ShiniAdmin;
use App\Entity\ShiniPlayer;
use App\Entity\ShiniStaff;
use App\Service\ImageSaver\ImageSaverTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class ImageSaverTraitTest
 *
 * Is ImageSaverTrait ok ?
 *
 * @package App\Tests
 */
class ImageSaverTraitTest extends TestCase
{
    private $classWithoutConstants;
    private $player;
    private $staff;
    private $admin;

    protected function setUp()
    {
        parent::setUp();

        $this->classWithoutConstants = new class {use ImageSaverTrait;};
        $this->classWithoutConstants->setImageName('image.jpg');

        $this->player = new ShiniPlayer();
        $this->player->setImageName('image.jpg');

        $this->staff = new ShiniStaff();
        $this->staff->setImageName('image.jpg');

        $this->admin = new ShiniAdmin();
        $this->admin->setImageName('image.jpg');
    }

    /**
     * Do we get the default storage folder ?
     */
    public function testAnonymousHasDefaultFolder()
    {

        $this->assertSame('myImages', $this->classWithoutConstants->getFolder());
    }

    /**
     * Can we get the image name ?
     *
     * @depends testAnonymousHasDefaultFolder
     */
    public function testAnonymousHasImageNameProperty()
    {
        $this->assertSame('image.jpg', $this->classWithoutConstants->getImageName());
    }

    /**
     * Can we get the image path ?
     *
     * @depends testAnonymousHasImageNameProperty
     */
    public function testAnonymousGetImagePath()
    {
        $this->assertSame('myImages/image.jpg', $this->classWithoutConstants->getImage());
    }

    /**
     * Do we get the default storage folder ?
     *
     * @depends testAnonymousHasDefaultFolder
     */
    public function testPlayerHasDefaultFolder()
    {
        $this->assertSame('player', $this->player->getFolder());
    }

    /**
     * Can we get the image name ?
     *
     * @depends testPlayerHasDefaultFolder
     */
    public function testPlayerHasImageNameProperty()
    {
        $this->assertSame('image.jpg', $this->player->getImageName());
    }

    /**
     * Can we get the image path ?
     *
     * @depends testPlayerHasImageNameProperty
     */
    public function testPlayerGetImagePath()
    {
        $this->assertSame('player/image.jpg', $this->player->getImage());
    }

    /**
     * Do we get the default storage folder ?
     *
     * @depends testAnonymousHasDefaultFolder
     */
    public function testStaffHasDefaultFolder()
    {
        $this->assertSame('staff', $this->staff->getFolder());
    }

    /**
     * Can we get the image name ?
     *
     * @depends testStaffHasDefaultFolder
     */
    public function testStaffHasImageNameProperty()
    {
        $this->assertSame('image.jpg', $this->staff->getImageName());
    }

    /**
     * Can we get the image path ?
     *
     * @depends testStaffHasImageNameProperty
     */
    public function testStaffGetImagePath()
    {
        $this->assertSame('staff/image.jpg', $this->staff->getImage());
    }

    /**
     * Do we get the default storage folder ?
     *
     * @depends testAnonymousHasDefaultFolder
     */
    public function testAdminHasDefaultFolder()
    {
        $this->assertSame('admin', $this->admin->getFolder());
    }

    /**
     * Can we get the image name ?
     *
     * @depends testAdminHasDefaultFolder
     */
    public function testAdminHasImageNameProperty()
    {
        $this->assertSame('image.jpg', $this->admin->getImageName());
    }

    /**
     * Can we get the image path ?
     *
     * @depends testAdminHasImageNameProperty
     */
    public function testAdminGetImagePath()
    {
        $this->assertSame('admin/image.jpg', $this->admin->getImage());
    }
}
