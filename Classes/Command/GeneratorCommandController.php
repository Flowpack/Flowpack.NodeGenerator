<?php
namespace Flowpack\NodeGenerator\Command;

/*                                                                        *
 * This script belongs to the Neos package "Flowpack.NodeGenerator".      *
 *                                                                        *
 *                                                                        */

use Flowpack\NodeGenerator\Generator\NodesGenerator;
use Flowpack\NodeGenerator\Generator\PresetDefinition;
use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;
use Neos\ContentRepository\Exception\NodeTypeNotFoundException;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Exception;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Neos\Domain\Model\Site;
use Neos\Neos\Domain\Repository\SiteRepository;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\ContentRepository\Domain\Service\Context;
use Neos\ContentRepository\Domain\Model\Node;

/**
 * Generator Controller
 */
class GeneratorCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var SiteRepository
     */
    protected $siteRepository;

    /**
     * @Flow\Inject
     * @var WorkspaceRepository
     */
    protected $workspaceRepository;

    /**
     * @Flow\Inject
     * @var ContextFactoryInterface
     */
    protected $contextFactory;

    /**
     * @Flow\InjectConfiguration(path="preset", package="Flowpack.NodeGenerator")
     * @var array
     */
    protected $presets;

    /**
     * Creates a big collection of node for performance benchmarking
     * @param string $siteNode
     * @param string $preset
     * @param string $path
     * @throws StopActionException
     * @throws NodeTypeNotFoundException
     * @throws Exception
     */
    public function nodesCommand($siteNode, $preset, $path = null)
    {
        if (!(isset($this->presets[$preset]))) {
            $this->outputLine('Error: Invalid preset');
            $this->quit(1);
        }
        $preset = $this->presets[$preset];
        /** @var Site $currentSite */
        $currentSite = $this->siteRepository->findOneByNodeName($siteNode);
        if ($currentSite === null) {
            $this->outputLine('Error: No site for exporting found');
            $this->quit(1);
        }
        /** @var ContentContext $contentContext */
        $contentContext = $this->createContext($currentSite, 'live');

        $workspace = 'live';
        if ($this->workspaceRepository->findByName($workspace)->count() === 0) {
            $this->outputLine('Workspace "%s" does not exist', [$workspace]);
            $this->quit(1);
        }

        /** @var Node $siteNode */
        $siteNode = $contentContext->getCurrentSiteNode();

        if ($path === null) {
            $rootNode = $siteNode;
        } else {
            if (str_starts_with('/',$path)) {
                // absolute path
                $rootNode = $contentContext->getNode($path);
            } else {
                // relative path
                $rootNode = $siteNode->getNode($path);
            }
        }

        if ($rootNode === null) {
            $this->outputLine('Error: Could not determine the root node');
            $this->quit(1);
        }
        $preset = new PresetDefinition($rootNode, $preset);
        $generator = new NodesGenerator($preset);

        $generator->generate();

        $this->outputLine('Success: Node generation complete');
    }

    /**
     * @param Site $currentSite
     * @param string $workspace
     * @return Context
     */
    protected function createContext(Site $currentSite, $workspace = 'live')
    {
        return $this->contextFactory->create([
            'workspaceName' => $workspace,
            'currentSite' => $currentSite,
            'invisibleContentShown' => true,
            'inaccessibleContentShown' => true
        ]);
    }
}
