<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment Entity
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment
{

    const PARENT_CATEGORY = 1;
    const PARENT_POST = 2;

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
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern = "/([a-zA-Z]+\s?\b){2}/",
     *     message = "Must contain two words, both with a capital letter"
     * )
     * @ORM\Column(name="author", type="string", length=100)
     */
    private $author;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="comments")
     * @ORM\JoinColumn(name="parent_category_id", referencedColumnName="id", nullable=true)
     */
    private $parentCategory;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(name="parent_post_id", referencedColumnName="id", nullable=true)
     */
    private $parentPost;

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
     * Set author
     *
     * @param string $author
     * @return Comment
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Comment
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
     * Set category
     *
     * @param Category $parentCategory
     *
     * @return Comment
     */
    public function setParentCategory(Category $parentCategory = null)
    {
        $this->parentCategory = $parentCategory;

        return $this;
    }

    /**
     * Get parent category
     *
     * @return integer
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    /**
     * Set post
     *
     * @param Post $parentPost
     *
     * @return Comment
     */
    public function setParentPost(Post $parentPost = null)
    {
        $this->parentPost = $parentPost;

        return $this;
    }

    /**
     * Get parent post
     *
     * @return integer
     */
    public function getParentPost()
    {
        return $this->parentPost;
    }

}
