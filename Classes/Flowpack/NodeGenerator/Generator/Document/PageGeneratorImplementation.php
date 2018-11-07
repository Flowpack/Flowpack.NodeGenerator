<?php
namespace Flowpack\NodeGenerator\Generator\Document;

/*                                                                        *
 * This script belongs to the Neos package "Flowpack.NodeGenerator".      *
 *                                                                        *
 *                                                                        */

use Flowpack\NodeGenerator\Generator\AstractNodeGeneratorImplementation;
use KDambekalns\Faker\Company;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\Utility;

/**
 * Page Node Generator
 */
class PageGeneratorImplementation extends AstractNodeGeneratorImplementation
{
    /**
     * @param NodeInterface $parentNode
     * @param NodeType $nodeType
     * @return NodeInterface|void
     * @throws \Neos\ContentRepository\Exception\NodeExistsException
     */
    public function create(NodeInterface $parentNode, NodeType $nodeType)
    {
        $title = Company::name();
        $name = Utility::renderValidNodeName($title);

        $childrenNode = $parentNode->createNode($name, $nodeType);
        $childrenNode->setProperty('title', $title);

        return $childrenNode;
    }
}
