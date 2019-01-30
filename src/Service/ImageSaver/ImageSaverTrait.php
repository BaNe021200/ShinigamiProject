<?php
/**
 * Created by PhpStorm.
 * User: connector
 * Date: 08/01/2019
 * Time: 10:34
 */

namespace App\Service\ImageSaver;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait ImageSaverTrait
 *
 * Add an imageName property in entity using the trait and the method to retrieve the image in its storage folder.
 *
 * Entity should defined two constants to customized the trait to ist need.
 *
 * 1) ASSET_FOLDER : a storage folder based in the symfony 'asset' tree, generally under 'public'
 * if not defined 'myImages' is returned.
 *
 * 2) DEFAULT_IMAGE : a default image preferably stored in a folder outside the 'asset folder'
 * if not defined empty string is returned
 * @package App\Service\ImageSaver
 */
trait ImageSaverTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $imageName;

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
     * Get image from its storage location.
     *
     * @return string $image Image from the entity folder.
     */
    public function getImage(): string
    {
        return $this->getFolder().'/'.$this->getImageName();
    }

    /**
     * Return storage folder name for entity.
     *
     * @return string Storage Folder name defined in const ASSET_FOLDER of the entity, or 'myImages' if not defined.
     */
    public function getFolder(): string
    {
        try
        {
            $class = get_called_class();
            return $class::ASSET_FOLDER;
        }
        catch(\Error $e)
        {
            // TODO: throw notice 'no constant ASSET_FOLDER defined in entity'
            return 'myImages';
        }
    }

    /**
     * Return path to default image for entity.
     *
     * @return string The path to default image defined in const DEFAULT_IMAGE entity, or otherwise empty string ''.
     */
    public function getDefaultImage(): string
    {
        try
        {
            $class = get_called_class();
            return $class::DEFAULT_IMAGE;
        }
        catch(\Error $e)
        {
            // TODO: throw notice 'no constant DEFAULT_IMAGE defined in entity'
            // Be compliant with php 7.1
            return '';
        }
    }
}