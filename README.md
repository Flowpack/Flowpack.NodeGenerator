# NodeGenerator - Random nodes generator for TYPO3 Neos

## Configuration of the Node generator classes

In your settings.yaml you can register node generator class. Each node type used in your setup, must
have an attached generator class.

```yaml
Flowpack:
  NodeGenerator:
    generator:
      'TYPO3.Neos.NodeTypes:Page':
        class: 'Flowpack\NodeGenerator\Generator\Document\PageGeneratorImplementation'
      'Ttree.Blog:Post':
        class: 'Flowpack\NodeGenerator\Generator\Document\PostGeneratorImplementation'
      'TYPO3.Neos.NodeTypes:Text':
        class: 'Flowpack\NodeGenerator\Generator\Content\TextGeneratorImplementation'
      'TYPO3.Neos.NodeTypes:Image':
        class: 'Flowpack\NodeGenerator\Generator\Content\ImageGeneratorImplementation'
      'TYPO3.Neos.NodeTypes:TextWithImage':
        class: 'Flowpack\NodeGenerator\Generator\Content\TextWithImageGeneratorImplementation'
```

### Minimal Generator Class

The NodesGenerators who call your node generator class, will catch NodeExistsException so you
don't need to take care about that. The generator will skip silently nodes that currently exist
in the TYPO3CR.

```php
class PageGeneratorImplementation extends AstractNodeGeneratorImplementation {

	/**
	 * @param NodeInterface $parentNode
	 * @param NodeType $nodeType
	 * @return NodeInterface|void
	 */
	public function create(NodeInterface $parentNode, NodeType $nodeType) {
		$title = Company::name();
		$name = Utility::renderValidNodeName($title);

		$childrenNode = $parentNode->createNode($name, $nodeType);
		$childrenNode->setProperty('title', $title);

		return $childrenNode;
	}
}
```

## Configuration of presets

If multiple Content and Document node type are configured the generator will select a
random node type for each new node. Take care to declare a generator class for each node
type.

The Extension is shipped with some exemple of presets, a basic preset look like:

```yaml
Flowpack:
  NodeGenerator:
    preset:
      # Basic website, with a multiple level page tree
      small-website:
        depth: 3
        nodeByLevel: 10
        contentNodeByDocument: 5
        documentNodeType: [ 'TYPO3.Neos.NodeTypes:Page' ]
        contentNodeType: [ 'TYPO3.Neos.NodeTypes:Text', 'TYPO3.Neos.NodeTypes:Images' ]
        # Randomness of the number of nodes generated from 0 to 100
        randomness: 25
```

## Run your preset

```
flow generator:nodes --site-node blog --preset small-blog
```