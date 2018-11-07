<?php
namespace Flowpack\NodeGenerator\Generator\Document;

/*                                                                        *
 * This script belongs to the Neos package "Flowpack.NodeGenerator".      *
 *                                                                        *
 *                                                                        */

use KDambekalns\Faker\Date;
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
     * @throws \Neos\ContentRepository\Exception\NodeExistsException
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
