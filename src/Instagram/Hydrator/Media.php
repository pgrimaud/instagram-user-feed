<?php
namespace Instagram\Hydrator;

class Media
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $typeName;

    /**
     * @var string
     */
    public $height;

    /**
     * @var string
     */
    public $width;

    /**
     * @var string
     */
    public $thumbnailSrc;

    /**
     * @var string
     */
    public $link;

    /**
     * @var \DateTime
     */
    public $date;

    /**
     * @var string
     */
    public $displaySrc;

    /**
     * @var string
     */
    public $caption;

    /**
     * @var integer
     */
    public $comments;

    /**
     * @var integer
     */
    public $likes;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @param string $typeName
     */
    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;
    }

    /**
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param string $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return string
     */
    public function getThumbnailSrc()
    {
        return $this->thumbnailSrc;
    }

    /**
     * @param string $thumbnailSrc
     */
    public function setThumbnailSrc($thumbnailSrc)
    {
        $this->thumbnailSrc = $thumbnailSrc;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getDisplaySrc()
    {
        return $this->displaySrc;
    }

    /**
     * @param string $displaySrc
     */
    public function setDisplaySrc($displaySrc)
    {
        $this->displaySrc = $displaySrc;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return int
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param int $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }
}
