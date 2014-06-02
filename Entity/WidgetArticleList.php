<?php
namespace Victoire\ArticleListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Victoire\Bundle\CoreBundle\Entity\Widget;

/**
 * WidgetArticleList
 *
 * @ORM\Table("cms_widget_articlelist")
 * @ORM\Entity
 */
class WidgetArticleList extends Widget
{
    /**
     * @var string
     *
     * @ORM\Column(name="global_link_title", type="string", length=255)
     */
    private $globalLinkTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="global_link_url", type="string", length=255)
     */
    private $globalLinkUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="global_link_label", type="string", length=255)
     */
    private $globalLinkLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxResults", type="integer", nullable=true)
     */
    private $maxResults;

    /**
     * Set title
     *
     * @param string $title
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
     * Set maxResults
     *
     * @param string $maxResults
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;

        return $this;
    }

    /**
     * Get maxResults
     *
     * @return string
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * Set globalLinkTitle
     *
     * @param string $globalLinkTitle
     */
    public function setGlobalLinkTitle($globalLinkTitle)
    {
        $this->globalLinkTitle = $globalLinkTitle;

        return $this;
    }

    /**
     * Get globalLinkTitle
     *
     * @return string
     */
    public function getGlobalLinkTitle()
    {
        return $this->globalLinkTitle;
    }

    /**
     * Set globalLinkUrl
     *
     * @param string $globalLinkUrl
     */
    public function setGlobalLinkUrl($globalLinkUrl)
    {
        $this->globalLinkUrl = $globalLinkUrl;

        return $this;
    }

    /**
     * Get globalLinkUrl
     *
     * @return string
     */
    public function getGlobalLinkUrl()
    {
        return $this->globalLinkUrl;
    }

    /**
     * Set globalLinkLabel
     *
     * @param string $globalLinkLabel
     */
    public function setGlobalLinkLabel($globalLinkLabel)
    {
        $this->globalLinkLabel = $globalLinkLabel;

        return $this;
    }

    /**
     * Get globalLinkLabel
     *
     * @return string
     */
    public function getGlobalLinkLabel()
    {
        return $this->globalLinkLabel;
    }

}
