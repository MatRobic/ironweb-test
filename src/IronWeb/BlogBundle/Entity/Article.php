<?php

namespace IronWeb\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="IronWeb\BlogBundle\Repository\ArticleRepository")
 */
class Article
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
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     * @Assert\NotBlank()   
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"}, separator="-", updatable=false)
     * @ORM\Column(length=255)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()   
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="smallint", nullable=true)
     */
    private $rating;

    /**
      * @ORM\OneToMany(targetEntity="IronWeb\BlogBundle\Entity\Comment", mappedBy="article", cascade={"remove"})
      */
    private $comments;

    public function __construct()
    {        
        $this->rating           = 0; 
        $this->comments         = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Update the article rating
     */
    public function updateRating()
    {
        $averageRating = $this->getRating();
        $commentsRatings = array();
        $comments = $this->getComments();
        
        $index = 0;
        foreach ($comments as $comment) {
            $commentsRatings[$index] = $comment->getRating();
            $index++;
        }

        $sum = array_sum($commentsRatings);
        $count;
        if($count = count($commentsRatings) == 0) {
            $count = 1;
        }
        else {
            $count = count($commentsRatings);
        }

        $averageRating = (int)($sum / $count);

        $this->rating = $averageRating;
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
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
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
     * Set rating
     *
     * @param integer $rating
     *
     * @return Article
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Add comment
     *
     * @param \IronWeb\BlogBundle\Entity\Comment $comment
     *
     * @return Article
     */
    public function addComment(\IronWeb\BlogBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \IronWeb\BlogBundle\Entity\Comment $comment
     */
    public function removeComment(\IronWeb\BlogBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
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

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
