<?php
namespace Flowpack\NodeGenerator\Generator\Content;

/*                                                                        *
 * This script belongs to the Neos package "Flowpack.NodeGenerator".      *
 *                                                                        *
 *                                                                        */

use KDambekalns\Faker\Lorem;
use Neos\ContentRepository\Exception\NodeExistsException;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\Flow\ResourceManagement\Exception;

/**
 * Text with Images Node Generator
 */
class TextWithImageGeneratorImplementation extends TextGeneratorImplementation
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
        $node = parent::create($parentNode, $nodeType);
        $node->setProperty('image', $this->getRandomImageVariant());

        if (rand(0, 10) === 0) {
            $node->setProperty('hasCaption', true);
            $node->setProperty('caption', Lorem::sentence(rand(5, 12)));
            $node->setProperty('title', Lorem::sentence(rand(5, 12)));
            $node->setProperty('alternativeText', Lorem::sentence(rand(5, 12)));
        }

        return $node;
    }
}
