<?php
namespace Flowpack\NodeGenerator\Generator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Flowpack.NodeGenerator".*
 *                                                                        *
 *                                                                        */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Media\Domain\Model\Image;
use Neos\Media\Domain\Model\ImageVariant;
use Neos\Media\Domain\Repository\ImageRepository;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;

/**
 * Node Generator
 */
abstract class AstractNodeGeneratorImplementation implements NodeGeneratorImplementationInterface
{
    /**
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @Flow\Inject
     * @var ImageRepository
     */
    protected $imageRepository;

    /**
     * @return ImageVariant
     */
    protected function getRandommImageVariant()
    {
        $image = new Image($this->resourceManager->importResource(sprintf('resource://Flowpack.NodeGenerator/Private/Images/Sample%d.jpg', rand(1, 3))));
        $this->imageRepository->add($image);

        return $image->createImageVariant(array(
            array(
                'command' => 'thumbnail',
                'options' => array(
                    'size' => array(
                        'width' => 1500,
                        'height' => 1024
                    )
                ),
            ),
        ));
    }

    /**
     * @param NodeInterface $parentNode
     * @param NodeType $nodeType
     * @return NodeInterface The freshly created node
     */
    abstract public function create(NodeInterface $parentNode, NodeType $nodeType);
}
