<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Post Entity
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 * @Vich\Uploadable
 *
 */
class Post
{

    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $attachment;

    /**
     * @var File
     *
     * /**
     * @Assert\File(
     *     maxSize = "2048k",
     *     mimeTypes = {"application/pdf", "application/image/png"},
     *     mimeTypesMessage = "Please upload a valid PDF"
     * )
     *
     * @Vich\UploadableField(mapping="post_files", fileNameProperty="attachment")
     */
    private $attachmentFile;

    /**
     * Many Posts have One Category.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="posts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * One Post has Many Comments.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="post", cascade={"remove"})
     */
    private $comments;

    /**
     * Category constructor.
     */
    public function __construct() {
        $this->comments = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Post
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param File|null $attachment
     */
    public function setAttachmentFile(File $attachment = null)
    {
        $this->attachmentFile = $attachment;

        if ($attachment) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getAttachmentFile()
    {
        return $this->attachmentFile;
    }

    /**
     * Set attachment
     *
     * @param $attachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Get attachment
     *
     * @return string
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Set category
     *
     * @param Category $category
     *
     * @return Post
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

}