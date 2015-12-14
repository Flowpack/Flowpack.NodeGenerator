<?php
namespace Flowpack\NodeGenerator\Generator\Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Flowpack.NodeGenerator".*
 *                                                                        *
 *                                                                        */

use Flowpack\NodeGenerator\Generator\AstractNodeGeneratorImplementation;
use KDambekalns\Faker\Company;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Utility;

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
