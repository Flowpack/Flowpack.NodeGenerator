<?php
namespace Flowpack\NodeGenerator\Generator\Document;

/*                                                                        *
 * This script belongs to the Neos package "Flowpack.NodeGenerator".      *
 *                                                                        *
 *                                                                        */

use KDambekalns\Faker\Date;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\Exception\NodeExistsException;
use Neos\Flow\ResourceManagement\Exception;

/**
 * Post Node Generator
 */
class PostGeneratorImplementation extends PageGeneratorImplementation
{
    /**
     * @param NodeInterface $parentNode
     * @param NodeType $nodeType
     * @return NodeInterface
     * @throws NodeExistsException
     * @throws Exception
     */
    public function create(NodeInterface $parentNode, NodeType $nodeType)
    {
        $postNode = parent::create($parentNode, $nodeType);
        $date = Date::random('-1 week');
        $postNode->setProperty('datePublished', $date);
        $postNode->setProperty('image', $this->getRandomImageVariant());

        return $postNode;
    }
}
