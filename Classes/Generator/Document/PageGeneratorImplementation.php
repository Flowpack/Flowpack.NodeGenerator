<?php
namespace Flowpack\NodeGenerator\Generator\Document;

/*                                                                        *
 * This script belongs to the Neos package "Flowpack.NodeGenerator".      *
 *                                                                        *
 *                                                                        */

use Faker\Factory;
use Flowpack\NodeGenerator\Generator\AbstractNodeGeneratorImplementation;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\Exception\NodeExistsException;
use Neos\ContentRepository\Utility;

/**
 * Page Node Generator
 */
class PageGeneratorImplementation extends AbstractNodeGeneratorImplementation
{
    /**
     * @param NodeInterface $parentNode
     * @param NodeType $nodeType
     * @return NodeInterface
     * @throws NodeExistsException
     */
    public function create(NodeInterface $parentNode, NodeType $nodeType)
    {
        $faker = Factory::create();
        $title = $faker->company;
        $name = Utility::renderValidNodeName($title);

        $childrenNode = $parentNode->createNode($name, $nodeType);
        $childrenNode->setProperty('title', $title);

        return $childrenNode;
    }
}
