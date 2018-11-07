<?php
namespace Flowpack\NodeGenerator\Generator\Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Flowpack.NodeGenerator".*
 *                                                                        *
 *                                                                        */

use KDambekalns\Faker\Date;
use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;

/**
 * Post Node Generator
 */
class PostGeneratorImplementation extends PageGeneratorImplementation
{
    /**
     * @param NodeInterface $parentNode
     * @param NodeType $nodeType
     * @return NodeInterface|void
     */
    public function create(NodeInterface $parentNode, NodeType $nodeType)
    {
        $postNode = parent::create($parentNode, $nodeType);
        $date = Date::random('-1 week');
        $postNode->setProperty('datePublished', $date);
        $postNode->setProperty('image', $this->getRandommImageVariant());

        return $postNode;
    }
}
