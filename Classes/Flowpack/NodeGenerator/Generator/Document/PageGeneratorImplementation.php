<?php
namespace Flowpack\NodeGenerator\Generator\Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Flowpack.NodeGenerator".*
 *                                                                        *
 *                                                                        */

use Flowpack\NodeGenerator\Generator\AstractNodeGeneratorImplementation;
use KDambekalns\Faker\Company;
use Neos\Flow\Annotations as Flow;
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
