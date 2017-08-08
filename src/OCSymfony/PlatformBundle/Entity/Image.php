<?php

namespace OCSymfony\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="OCSymfony\PlatformBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    private $file;

    private $tempFileName;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        // Vérification si fichier déjà présent
        if (null !== $this->url) {
            // Sauvergarde de l'extention du fichier
            $this->tempFileName = $this->url;

            // Réinitialisation des valeurs
            $this->url = null;
            $this->url = null;
        }
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {

        // Si il n'y a pas de fichier (facultatif) on sort de la fonction
        if (null === $this->file) {
            return;
        }

        // Si fichier present : suppression
        if (null !== $this->tempFileName) {
            $oldFile = $this->getUploadDir(). '/' .$this->id. '.'. $this->tempFileName;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // Déplacement du fichier dans le répertoire que l'on souhaite
        $this->file->move($this->getUploadRootDir(), $this->id .'.'. $this->url);
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload() {
        // Sauvegarde temporaire du nom du fichier
        $this->tempFileName = $this->getUploadRootDir() .'/'. $this->id .'.' .$this->url;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {

        // Récupération du nom du fichier sauvegardé
        if (file_exists($this->tempFileName)) {
            // Suppression du fichier
            unlink($this->tempFileName);
        }
    }


    public function getUploadDir() {
        // Retourne le chemin relatif vers l'image pour un navigateur
        return 'upload/img';
    }

    protected function getUploadRootDir() {
        // REtourne le chemin relatif vers l'image
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        // Si il n'y a pas de de fichier, on sort de la fonction
        if (null === $this->file) {
            return;
        }

        $this->url = $this->file->guessExtension();

        $this->alt = $this->file->getClientOriginalName();
    }

    public function getWebPath() {
        return $this->getUploadDir() .'/'. $this->getId() .'.' .$this->getUrl();
    }
}
