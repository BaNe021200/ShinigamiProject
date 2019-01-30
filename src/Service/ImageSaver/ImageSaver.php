<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/01/2019
 * Time: 10:50
 */

namespace App\Service\ImageSaver;

use Symfony\Component\Asset\Packages;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageSaver
 * @package App\Form
 *
 */
class ImageSaver implements EventSubscriberInterface
{
    private $package;

    /**
     * ImageSaver constructor.
     * @param $package
     */
    public function __construct(Packages $package)
    {
        // Arriving from ImageSaverType
        $this->package = $package;
    }

    /**
     * The events subscribed
     * PRE_SET_DATA : add the filetype before creation of the form
     * POST_SUBMIT : choose the folder to save image (offer or player)
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'populatePlaceHolder',
            FormEvents::POST_SUBMIT   => 'saveImage',
        );
    }

    /**
     * Add image to the FileType Field
     * @param FormEvent $event
     */
    public function populatePlaceHolder(FormEvent $event)
    {
        $field = $event->getForm();
        $form = $field->getParent();
        $entity = $form->getData();

        // How to change form options dynamically
        // https://stackoverflow.com/questions/40267844/how-to-use-setattribute-on-symfony-form-field
        // Get all options
        $options = $field->getConfig()->getOptions();
        //dump($options);


        // Entity have an image, grab it.
/*        if ($imageUrl = $entity->getImageName())
        {
            $directory = $entity->getFolder();
            $imageName = $entity->getImageName();
            $imageUrl = $this->package->getUrl("${directory})/${imageName}");
            $options['attr']['data-default-file'] = "$imageUrl";

            // Load new options to the ImageSaver field
            $form->add('image', ImageSaverType::class, $options);
            // then dropify the field, if there is not another class attribute

        }*/
        /* Inutile pour le moment, mis par défaut dans le ImageSaverType
           if( 0 === count($options['attr']))
           {
               $options['attr']['class'] = 'dropify';
               $options['attr']['data-default-file'] = "$imageUrl";
           }
         */
        // move submit to end of form if it exist.
        // Part 1: remove the field
/*
        if ($hasSubmit = $form->has('submit'))
        {
            $submit = $form->get('submit');
            $form->remove('submit');
        }
*/




        // Part 2: re-inject it.
/*
       if ($hasSubmit)
        {
            $form->add($submit);;
        }
       */
    }

    /**
     * Save an image in entity folder
     * @param FormEvent $event
     */
    public function saveImage(FormEvent $event)
    {
        $entity = $event->getData();
        $form = $event->getForm();

        if (!$entity) {
            return;
        }

        // We need to check that form is valid.
        $errors = $form->getErrors(true)->count();

        // Find error from the added field.
        // $errors += $form->get('playerImageFile')->getErrors()->count();

        if ($errors)  return;

        // Good news, the form is valid.
        // Now, check if the entity has uploaded an image.
        if ($noImageUploaded = $form->get('ImageSaver')->isEmpty())
        {
            if ($entity->getImageName()) return;

           $entity->setImageName('default.png');
            return;
        }

        // Got it ! entity has an image !
        /** @var UploadedFile $image */
        $image = $form->get('ImageSaver')->getData();

        // Is it really an image file ?
        $extension = $image->guessExtension();

        if (!in_array($extension, array('gif','jpeg','png')))
        {
            // TODO : traduction des messages d'erreur
            $form->get('ImageSaver')->addError(new FormError('Invalid Image type'));
            return;
        }

        // Never trust actions input...
        $image = $this->sanitifyImage($image);

        // Create a name for this image
        $filename = md5(uniqid()).'.'.$extension;
        $folder = $entity->getFolder();
        dump($entity);
        // Move image to right folder
        try
        {
            $image->move($folder, $filename);
        }
        catch (FileException $e) {
            // TODO: quel message d'erreur renvoyer ?
            // ... handle exception if something happens during file upload
        }

        // Update form data
        $entity->setImageName($filename);
    }

    private function sanitifyImage(UploadedFile $image): UploadedFile
    {
        // TODO: implanter le réencodage de l'image
        return $image;

        /*
         // Ré-encoder l'image de l'utilisateur
        $pathToLoadedImage = $image->getPathname();
        dump($image);
        switch ($extension)
        {
            case "jpeg":
                $gdimage = imagecreatefromjpeg($pathToLoadedImage);
                imagejpeg($gdimage, $directory.'/'.$filename);
                imagedestroy($gdimage);
                break;
            case "png":
                $gdimage = imagecreatefrompng($pathToLoadedImage);
                imagepng($gdimage, $directory.'/'.$filename);
                imagedestroy($gdimage);
                break;
            case "gif":
                $gdimage = imagecreatefromgif($pathToLoadedImage);
                imagegif($gdimage, $directory.'/'.$filename);
                imagedestroy($gdimage);
                break;
        }
         */
    }
}
