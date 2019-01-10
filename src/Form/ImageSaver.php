<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/01/2019
 * Time: 10:50
 */

namespace App\Form;

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
 * TODO : installer dropify dans la champ image
 */
class ImageSaver implements EventSubscriberInterface
{
    /**
     * The events subscribed
     * PRE_SET_DATA : add the filetype before creation of the form
     * POST_SUBMIT : choose the folder to save image (offer or player)
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'addImageField',
            FormEvents::POST_SUBMIT   => 'saveImage',
        );
    }

    /**
     * Add a FileType field before submit button
     * @param FormEvent $event
     */
    public function addImageField(FormEvent $event)
    {
        $user = $event->getData();
        $form = $event->getForm();

        // If the user have an image pass it to dropify
        if ($imageUrl = $user->getPlayerImageName())
        {
            $directory = $this->getDirectory($user);
            $imageUrl = "${directory}/${imageUrl}";
        }

        $options = [
            'image_url' => "$imageUrl"
        ];

        // move submit to end of form.
        $submit = $form->get('submit');
        $form->remove('submit');

        $form->add('playerImageFile', FileType::class,  [
            'label' => 'Charger une image pour votre profile',
            'mapped' => false,
            'attr' => [
                'class' => 'dropify',
                // TODO : gérer l'image qui a été ajoutée au moment de l'inscription
                'data-default-file' => $options['image_url']
        ]])
        ->add($submit);
    }

    /**
     * Save an image to the right folder (offer or player)
     * @param FormEvent $event
     */
    public function saveImage(FormEvent $event)
    {
        $user = $event->getData();
        $form = $event->getForm();

        if (!$user) {
            return;
        }

        // We need to check that form is valid.
        $errors = $form->getErrors()->count();

        // Find error from the added field.
        // $errors += $form->get('playerImageFile')->getErrors()->count();

        if ($errors)  return;

        // Good news, the form is valid.
        // Now, check if the user has uploaded an image.
        if ($noImageUploaded = $form->get('playerImageFile')->isEmpty())
        {
            if ($user->getPlayerImageName()) return;

            // User doesn't have an image
            $user->setPlayerImageName('player/default.png');
            return;
        }

        // Well ! User has added a new image for his profile !
        /** @var UploadedFile $image */
        $image = $form->get('playerImageFile')->getData();

        // Is it really an image file ?
        $extension = $image->guessExtension();

        if (!in_array($extension, array('gif','jpeg','png')))
        {
            $form->get('playerImageFile')->addError(new FormError('Invalid Image type'));
            return;
        }

        // Never trust user input...
        $image = $this->sanitifyImage($image);

        // Create a name for the image
        $filename = md5(uniqid()).'.'.$extension;
        $directory = $this->getDirectory($user);

        // Move the image to the right folder
        try
        {
            $image->move($directory, $filename);
        }
        catch (FileException $e) {
            // TODO: quel message d'erreur renvoyer ?
            // ... handle exception if something happens during file upload
        }

        // Set the entity
        $user->setPlayerImageName($filename);
    }

    /**
     * Get the storage directory of the entity using the form
     * @return string
     */
    private function getDirectory($user): string
    {
        //TODO: comment typer notre $user (ShiniStaff, ou ShiniPlayer, ShiniOffer)
        try
        {
            $directory = get_class($user)::DIRECTORY;
        }
        catch (\Exception $e)
        {
            // TODO: prévoir la gestion des exceptions
            // Did you add a DIRECTORY constant in your %s entity ?
        }

        return $directory;
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
