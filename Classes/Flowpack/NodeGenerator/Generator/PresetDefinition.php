<?php
namespace Flowpack\NodeGenerator\Generator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Flowpack.NodeGenerator".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * Preset Definition
 */
class PresetDefinition
{
    /**
     * @var NodeInterface
     */
    protected $siteNode;

    /**
     * @var integer
     */
    protected $nodeByLevel;

    /**
     * @var integer
     */
    protected $contentNodeByDocument;

    /**
     * @var integer
     */
    protected $depth;

    /**
     * @var array
     */
    protected $documentNodeType;

    /**
     * @var array
     */
    protected $contentNodeType;

    /**
     * @var integer
     */
    protected $randomness;

    /**
     * @param NodeInterface $siteNode
     * @param array $configuration
     */
    public function __construct(NodeInterface $siteNode, array $configuration)
    {
        $this->siteNode = $siteNode;
        $this->nodeByLevel = (int)$configuration['nodeByLevel'];
        $this->contentNodeByDocument = (int)$configuration['contentNodeByDocument'];
        $this->depth = (int)$configuration['depth'];
        $this->contentNodeType = (array)$configuration['contentNodeType'];
        $this->documentNodeType = (array)$configuration['documentNodeType'];
        $this->randomness = (int)$configuration['randomness'];
    }

    /**
     * @return integer
     */
    protected function getRandomness()
    {
        return (int)$this->randomness;
    }

    /**
     * @return string
     */
    public function getContentNodeType()
    {
        return (string)$this->contentNodeType[array_rand($this->contentNodeType)];
    }

    /**
     * @return integer
     */
    public function getDepth()
    {
        if ($this->getRandomness()) {
            $variant = $this->depth * $this->getRandomness() / 100;
            $minimum = ceil($this->depth - $variant);
            $maximum = ceil($this->depth + $variant);
            $depth = rand($minimum, $maximum);
        } else {
            $depth = $this->depth;
        }
        return $depth;
    }

    /**
     * @return string
     */
    public function getDocumentNodeType()
    {
        return (string)$this->documentNodeType[array_rand($this->documentNodeType)];
    }

    /**
     * @return integer
     */
    public function getNodeByLevel()
    {
        if ($this->getRandomness()) {
            $variant = $this->nodeByLevel * $this->getRandomness() / 100;
            $minimum = ceil($this->nodeByLevel - $variant);
            $maximum = ceil($this->nodeByLevel + $variant);
            $nodeByLevel = rand($minimum, $maximum);
        } else {
            $nodeByLevel = $this->nodeByLevel;
        }
        return $nodeByLevel;
    }

    /**
     * @return int
     */
    public function getContentNodeByDocument()
    {
        if ($this->getRandomness()) {
            $variant = $this->contentNodeByDocument * $this->getRandomness() / 100;
            $minimum = ceil($this->contentNodeByDocument - $variant);
            $maximum = ceil($this->contentNodeByDocument + $variant);
            $contentNodeByDocument = rand($minimum, $maximum);
        } else {
            $contentNodeByDocument = $this->contentNodeByDocument;
        }
        return $contentNodeByDocument;
    }

    /**
     * @return \TYPO3\TYPO3CR\Domain\Model\NodeInterface
     */
    public function getSiteNode()
    {
        return $this->siteNode;
    }
}
