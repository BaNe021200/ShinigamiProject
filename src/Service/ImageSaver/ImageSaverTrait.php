<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 08/01/2019
 * Time: 10:34
 */

namespace App\Service\ImageSaver;

use Doctrine\ORM\Mapping as ORM;

trait ImageSaverTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $imageName;

    /**
     * Path to a default image for the entity.
     * Presumably not stored in public folder.
     *
     * @var string A path to an image.
     */
    private $defaultImage = null;

    /**
     * Image folder for entity, located in symfony 'public' folder.
     * Accessible with symfony 'assets'
     *
     * Must be set in entity private '$folder' attribute, or default to myImages.
     *
     * @var string
     */
    private $folder = null;

    /**
     * Get image from the entity folder
     *
     * @return string $image Image from the entity folder.
     */
    public function getImage():? string
    {
        return $this->getFolder().'/'.$this->getImageName();
    }

    /**
     * Get name and extension.
     *
     * @return string $imageName Name + extension.
     */
    public function getImageName():? string
    {
        return $this->imageName;
    }

    /**
     * Change image name (name + extension)
     *
     * @param string $imageName Image name.
     * @return self
     */
    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;
        return $this;
    }

    /**
     * Return storage folder name
     *
     * @return string Storage Folder name, or if null 'savedImages' by default.
     */
    public function getFolder(): string
    {
        return null == $this->folder ? 'myImages' : $this->folder;
    }

    /**
     * Storage folder for image entity
     * Will be located under Symfony 'public' folder and reachable with 'asset' utility.
     *
     * @param string $folder Folder name.
     * @return mixed
     */
    public function setFolder(string $folder): self
    {
        $this->folder = $folder;
        return $this;
    }
}