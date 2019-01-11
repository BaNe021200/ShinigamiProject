<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 08/01/2019
 * Time: 10:34
 */

namespace App\ImageSaver;

use Doctrine\ORM\Mapping as ORM;

trait ImageSaverTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $imageName;

    /**
     * Image folder for your entity
     * To be set in the entity's constructor
     */
    private $folder = '';

    /**
     * @return string $imageName
     */
    public function getImageName():? string
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     * @return self
     */
    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param string $folder
     * @return mixed
     */
    public function setFolder(string $folder): self
    {
        $this->folder = $folder;
        return $this;
    }
}