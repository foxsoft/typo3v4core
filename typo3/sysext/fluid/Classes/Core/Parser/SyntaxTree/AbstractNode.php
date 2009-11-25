<?php

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Abstract node in the syntax tree which has been built.
 *
 * @version $Id: AbstractNode.php 1734 2009-11-25 21:53:57Z stucki $
 * @package Fluid
 * @subpackage Core\Parser\SyntaxTree
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
abstract class Tx_Fluid_Core_Parser_SyntaxTree_AbstractNode {

	/**
	 * List of Child Nodes.
	 * @var array<Tx_Fluid_Core_Parser_SyntaxTree_AbstractNode>
	 */
	protected $childNodes = array();

	/**
	 * The rendering context containing everything to correctly render the subtree
	 * @var Tx_Fluid_Core_Rendering_RenderingContext
	 */
	protected $renderingContext;

	/**
	 * @param Tx_Fluid_Core_Rendering_RenderingContext $renderingContext Rendering Context to be used for this evaluation
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function setRenderingContext(Tx_Fluid_Core_Rendering_RenderingContext $renderingContext) {
		$this->renderingContext = $renderingContext;
	}

	/**
	 * Evaluate all child nodes and return the evaluated results.
	 *
	 * @return object Normally, an object is returned - in case it is concatenated with a string, a string is returned.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function evaluateChildNodes() {
		$output = NULL;
		foreach ($this->childNodes as $subNode) {
			$subNode->setRenderingContext($this->renderingContext);

			if ($output === NULL) {
				$output = $subNode->evaluate();
			} else {
				if (is_object($output) && !method_exists($output, '__toString')) {
					throw new Tx_Fluid_Core_Parser_Exception('Cannot cast object of type "' . get_class($output) . '" to string.', 1248356140);
				}
				$output = (string)$output;
				$subNodeOutput = $subNode->evaluate();
				if (is_object($subNodeOutput) && !method_exists($subNodeOutput, '__toString')) {
					throw new Tx_Fluid_Core_Parser_Exception('Cannot cast object of type "' . get_class($subNodeOutput) . '" to string.', 1248356140);
				}
				$output .= (string)$subNodeOutput;
			}
		}
		return $output;
	}

	/**
	 * Returns all child nodes for a given node.
	 * This is especially needed to implement the boolean expression language.
	 *
	 * @return array Tx_Fluid_Core_Parser_SyntaxTree_AbstractNode A list of nodes
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function getChildNodes() {
		return $this->childNodes;
	}

	/**
	 * Appends a subnode to this node. Is used inside the parser to append children
	 *
	 * @param Tx_Fluid_Core_Parser_SyntaxTree_AbstractNode $childNode The subnode to add
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function addChildNode(Tx_Fluid_Core_Parser_SyntaxTree_AbstractNode $childNode) {
		$this->childNodes[] = $childNode;
	}

	/**
	 * Evaluates the node - can return not only strings, but arbitary objects.
	 *
	 * @return object Evaluated node
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	abstract public function evaluate();
}

?>