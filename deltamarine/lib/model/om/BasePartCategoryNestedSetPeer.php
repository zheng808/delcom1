<?php

require 'lib/model/om/BasePartCategoryPeer.php';

/**
 * Base static class for performing query operations on the tree contained by the 'part_category' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BasePartCategoryNestedSetPeer extends BasePartCategoryPeer implements NodePeer {

	/**
	 * Left column for the set
	 */
	const LEFT_COL = 'part_category.LFT';

	/**
	 * Right column for the set
	 */
	const RIGHT_COL = 'part_category.RGT';

	/**
	 * Scope column for the set
	 */
	const SCOPE_COL = 'part_category.SCOPE';

	/**
	 * Creates the supplied node as the root node.
	 *
	 * @param      PartCategory $node	Propel object for model
	 * @throws     PropelException
	 */
	public static function createRoot(NodeObject $node)
	{
		if ($node->getLeftValue()) {
			throw new PropelException('Cannot turn an existing node into a root node.');
		}

		$node->setLeftValue(1);
		$node->setRightValue(2);
	}

	/**
	 * Returns the root node for a given scope id
	 *
	 * @param      int $scopeId		Scope id to determine which root node to return
	 * @param      PropelPDO $con	Connection to use.
	 * @return     PartCategory			Propel object for root node
	 */
	public static function retrieveRoot($scopeId = null, PropelPDO $con = null)
	{
		$c = new Criteria(PartCategoryPeer::DATABASE_NAME);

		$c->add(self::LEFT_COL, 1, Criteria::EQUAL);

		if (self::SCOPE_COL) {
			$c->add(self::SCOPE_COL, $scopeId, Criteria::EQUAL);
		}

		return PartCategoryPeer::doSelectOne($c, $con);
	}

	/**
	 * Inserts $child as first child of given $parent node
	 *
	 * @param      PartCategory $child	Propel object for child node
	 * @param      PartCategory $parent	Propel object for parent node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     void
	 */
	public static function insertAsFirstChildOf(NodeObject $child, NodeObject $parent, PropelPDO $con = null)
	{
		// Update $child node properties
		$child->setLeftValue($parent->getLeftValue() + 1);
		$child->setRightValue($parent->getLeftValue() + 2);
		$child->setParentNode($parent);

		$sidv = null;
		if (self::SCOPE_COL) {
			$child->setScopeIdValue($sidv = $parent->getScopeIdValue());
		}

		// Update database nodes
		self::shiftRLValues($child->getLeftValue(), 2, $con, $sidv);

		// Update all loaded nodes
		self::updateLoadedNode($parent, 2, $con);
	}

	/**
	 * Inserts $child as last child of destination node $parent
	 *
	 * @param      PartCategory $child		Propel object for child node
	 * @param      PartCategory $parent	Propel object for parent node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     void
	 */
	public static function insertAsLastChildOf(NodeObject $child, NodeObject $parent, PropelPDO $con = null)
	{
		// Update $child node properties
		$child->setLeftValue($parent->getRightValue());
		$child->setRightValue($parent->getRightValue() + 1);
		$child->setParentNode($parent);

		$sidv = null;
		if (self::SCOPE_COL) {
			$child->setScopeIdValue($sidv = $parent->getScopeIdValue());
		}

		// Update database nodes
		self::shiftRLValues($child->getLeftValue(), 2, $con, $sidv);

		// Update all loaded nodes
		self::updateLoadedNode($parent, 2, $con);
	}

	/**
	 * Inserts $sibling as previous sibling to destination node $node
	 *
	 * @param      PartCategory $node		Propel object for destination node
	 * @param      PartCategory $sibling	Propel object for source node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     void
	 */
	public static function insertAsPrevSiblingOf(NodeObject $node, NodeObject $sibling, PropelPDO $con = null)
	{
		if ($sibling->isRoot()) {
			throw new PropelException('Root nodes cannot have siblings');
		}

		$node->setLeftValue($sibling->getLeftValue());
		$node->setRightValue($sibling->getLeftValue() + 1);
		$node->setParentNode($sibling->retrieveParent());

		$sidv = null;
		if (self::SCOPE_COL) {
			$node->setScopeIdValue($sidv = $sibling->getScopeIdValue());
		}

		// Update database nodes
		self::shiftRLValues($node->getLeftValue(), 2, $con, $sidv);

		// Update all loaded nodes
		self::updateLoadedNode($sibling->retrieveParent(), 2, $con);
	}

	/**
	 * Inserts $sibling as next sibling to destination node $node
	 *
	 * @param      PartCategory $node		Propel object for destination node
	 * @param      PartCategory $sibling	Propel object for source node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     void
	 */
	public static function insertAsNextSiblingOf(NodeObject $node, NodeObject $sibling, PropelPDO $con = null)
	{
		if ($sibling->isRoot()) {
			throw new PropelException('Root nodes cannot have siblings');
		}

		$node->setLeftValue($sibling->getRightValue() + 1);
		$node->setRightValue($sibling->getRightValue() + 2);
		$node->setParentNode($sibling->retrieveParent());

		$sidv = null;
		if (self::SCOPE_COL) {
			$node->setScopeIdValue($sidv = $sibling->getScopeIdValue());
		}

		// Update database nodes
		self::shiftRLValues($node->getLeftValue(), 2, $con, $sidv);

		// Update all loaded nodes
		self::updateLoadedNode($sibling->retrieveParent(), 2, $con);
	}

	/**
	 * Inserts $parent as parent of given node.
	 *
	 * @param      PartCategory $parent	Propel object for given parent node
	 * @param      PartCategory $node	Propel object for given destination node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     void
	 */
	public static function insertAsParentOf(NodeObject $parent, NodeObject $node, PropelPDO $con = null)
	{
		$sidv = null;
		if (self::SCOPE_COL) {
			$sidv = $node->getScopeIdValue();
		}

		self::shiftRLValues($node->getLeftValue(), 1, $con, $sidv);
		self::shiftRLValues($node->getRightValue() + 2, 1, $con, $sidv);

		if (self::SCOPE_COL) {
			$parent->setScopeIdValue($sidv);
		}

		$parent->setLeftValue($node->getLeftValue());
		$parent->setRightValue($node->getRightValue() + 2);

		$previous_parent = $node->retrieveParent();
		$parent->setParentNode($previous_parent);
		$node->setParentNode($parent);

		$node->save($con);

		// Update all loaded nodes
		self::updateLoadedNode($previous_parent, 2, $con);
	}

	/**
	 * Inserts $node as root node
	 *
	 * @param      PartCategory $node	Propel object as root node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     void
	 */
	public static function insertRoot(NodeObject $node, PropelPDO $con = null)
	{
		$sidv = null;
		if (self::SCOPE_COL) {
			$sidv = $node->getScopeIdValue();
		}

		PartCategoryPeer::insertAsParentOf(PartCategoryPeer::retrieveRoot($sidv, $con), $node, $con);
	}

	/**
	 * Inserts $parent as parent to destination node $child
	 *
	 * @deprecated 1.3 - 2007/11/06
	 * @see        insertAsParentOf()
	 * @param      PartCategory $child	Propel object to become child node
	 * @param      PartCategory $parent	Propel object as parent node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     void
	 */
	public static function insertParent(NodeObject $child, NodeObject $parent, PropelPDO $con = null)
	{
		self::insertAsParentOf($parent, $child, $con);
	}

	/**
	 * Delete root node
	 *
	 * @param      PropelPDO $con	Connection to use.
	 * @return     boolean		Deletion status
	 */
	public static function deleteRoot($scopeId = null, PropelPDO $con = null)
	{
		if (!self::SCOPE_COL) {
			$scopeId = null;
		}
		$root = PartCategoryPeer::retrieveRoot($scopeId, $con);
		if (PartCategoryPeer::getNumberOfChildren($root) == 1) {
			return PartCategoryPeer::deleteNode($root, $con);
		} else {
			return false;
		}
	}

	/**
	 * Delete $dest node
	 *
	 * @param      PartCategory $dest	Propel object node to delete
	 * @param      PropelPDO $con	Connection to use.
	 * @return     boolean		Deletion status
	 */
	public static function deleteNode(NodeObject $dest, PropelPDO $con = null)
	{
		if ($dest->getLeftValue() == 1) {
			// deleting root implies conditions (see deleteRoot() method)
			return PartCategoryPeer::deleteRoot($con);
		}

		$sidv = null;
		if (self::SCOPE_COL) {
			$sidv = $dest->getScopeIdValue();
		}

		self::shiftRLRange($dest->getLeftValue(), $dest->getRightValue(), -1, $con, $sidv);
		self::shiftRLValues($dest->getRightValue() + 1, -2, $con, $sidv);
		return $dest->delete($con);
	}

	/**
	 * Moves $child to be first child of $parent
	 *
	 * @param      PartCategory $parent	Propel object for parent node
	 * @param      PartCategory $child		Propel object for child node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     void
	 */
	public static function moveToFirstChildOf(NodeObject $parent, NodeObject $child, PropelPDO $con = null)
	{
		if ($parent->getScopeIdValue() != $child->getScopeIdValue()) {
			throw new PropelException('Moving nodes across trees is not supported');
		}
		$destLeft = $parent->getLeftValue() + 1;
		self::updateDBNode($child, $destLeft, $con);

		// Update all loaded nodes
		self::updateLoadedNode($parent, 2, $con);
	}

	/**
	 * Moves $child to be last child of $parent
	 *
	 * @param      PartCategory $parent	Propel object for parent node
	 * @param      PartCategory $child		Propel object for child node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     void
	 */
	public static function moveToLastChildOf(NodeObject $parent, NodeObject $child, PropelPDO $con = null)
	{
		if ($parent->getScopeIdValue() != $child->getScopeIdValue()) {
			throw new PropelException('Moving nodes across trees is not supported');
		}
		$destLeft = $parent->getRightValue();
		self::updateDBNode($child, $destLeft, $con);

		// Update all loaded nodes
		self::updateLoadedNode($parent, 2, $con);
	}

	/**
	 * Moves $node to be prev sibling to $dest
	 *
	 * @param      PartCategory $dest	Propel object for destination node
	 * @param      PartCategory $node	Propel object for source node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     void
	 */
	public static function moveToPrevSiblingOf(NodeObject $dest, NodeObject $node, PropelPDO $con = null)
	{
		if ($dest->getScopeIdValue() != $node->getScopeIdValue()) {
			throw new PropelException('Moving nodes across trees is not supported');
		}
		$destLeft = $dest->getLeftValue();
		self::updateDBNode($node, $destLeft, $con);

		// Update all loaded nodes
		self::updateLoadedNode($dest->retrieveParent(), 2, $con);
	}

	/**
	 * Moves $node to be next sibling to $dest
	 *
	 * @param      PartCategory $dest	Propel object for destination node
	 * @param      PartCategory $node	Propel object for source node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     void
	 */
	public static function moveToNextSiblingOf(NodeObject $dest, NodeObject $node, PropelPDO $con = null)
	{
		if ($dest->getScopeIdValue() != $node->getScopeIdValue()) {
			throw new PropelException('Moving nodes across trees is not supported');
		}
		$destLeft = $dest->getRightValue();
		$destLeft = $destLeft + 1;
		self::updateDBNode($node, $destLeft, $con);

		// Update all loaded nodes
		self::updateLoadedNode($dest->retrieveParent(), 2, $con);
	}

	/**
	 * Gets first child for the given node if it exists
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     mixed 		Propel object if exists else false
	 */
	public static function retrieveFirstChild(NodeObject $node, PropelPDO $con = null)
	{
		$c = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$c->add(self::LEFT_COL, $node->getLeftValue() + 1, Criteria::EQUAL);
		if (self::SCOPE_COL) {
			$c->add(self::SCOPE_COL, $node->getScopeIdValue(), Criteria::EQUAL);
		}

		return PartCategoryPeer::doSelectOne($c, $con);
	}

	/**
	 * Gets last child for the given node if it exists
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     mixed 		Propel object if exists else false
	 */
	public static function retrieveLastChild(NodeObject $node, PropelPDO $con = null)
	{
		$c = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$c->add(self::RIGHT_COL, $node->getRightValue() - 1, Criteria::EQUAL);
		if (self::SCOPE_COL) {
			$c->add(self::SCOPE_COL, $node->getScopeIdValue(), Criteria::EQUAL);
		}

		return PartCategoryPeer::doSelectOne($c, $con);
	}

	/**
	 * Gets prev sibling for the given node if it exists
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     mixed 		Propel object if exists else null
	 */
	public static function retrievePrevSibling(NodeObject $node, PropelPDO $con = null)
	{
		$c = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$c->add(self::RIGHT_COL, $node->getLeftValue() - 1, Criteria::EQUAL);
		if (self::SCOPE_COL) {
			$c->add(self::SCOPE_COL, $node->getScopeIdValue(), Criteria::EQUAL);
		}
		$prevSibling = PartCategoryPeer::doSelectOne($c, $con);
		$node->setPrevSibling($prevSibling);
		return $prevSibling;
	}

	/**
	 * Gets next sibling for the given node if it exists
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     mixed 		Propel object if exists else false
	 */
	public static function retrieveNextSibling(NodeObject $node, PropelPDO $con = null)
	{
		$c = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$c->add(self::LEFT_COL, $node->getRightValue() + 1, Criteria::EQUAL);
		if (self::SCOPE_COL) {
			$c->add(self::SCOPE_COL, $node->getScopeIdValue(), Criteria::EQUAL);
		}
		$nextSibling = PartCategoryPeer::doSelectOne($c, $con);
		$node->setNextSibling($nextSibling);
		return $nextSibling;
	}

	/**
	 * Retrieves the entire tree from root
	 *
	 * @param      PropelPDO $con	Connection to use.
	 */
	public static function retrieveTree($scopeId = null, PropelPDO $con = null)
	{
		$c = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$c->addAscendingOrderByColumn(self::LEFT_COL);
		if (self::SCOPE_COL) {
			$c->add(self::SCOPE_COL, $scopeId, Criteria::EQUAL);
		}
		$stmt = PartCategoryPeer::doSelectStmt($c, $con);
		if (false !== ($row = $stmt->fetch(PDO::FETCH_NUM))) {
			$omClass = PartCategoryPeer::getOMClass($row, 0);
			$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);

			$key = PartCategoryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null === ($root = PartCategoryPeer::getInstanceFromPool($key))) {
				$root = new $cls();
				$root->hydrate($row);
			}

			$root->setLevel(0);
			PartCategoryPeer::hydrateDescendants($root, $stmt);
			PartCategoryPeer::addInstanceToPool($root);
			
			$stmt->closeCursor();
			return $root;
		}
		return false;
	}

	/**
	 * Retrieves the entire tree from parent $node
	 *
	 * @param      PartCategory $node	Propel object for parent node
	 * @param      PropelPDO $con	Connection to use.
	 */
	public static function retrieveBranch(NodeObject $node, PropelPDO $con = null)
	{
		return PartCategoryPeer::retrieveDescendants($node, $con);
	}

	/**
	 * Gets direct children for the node
	 *
	 * @param      PartCategory $node	Propel object for parent node
	 * @param      PropelPDO $con	Connection to use.
	 */
	public static function retrieveChildren(NodeObject $node, PropelPDO $con = null)
	{
		$c = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$c->addAscendingOrderByColumn(self::LEFT_COL);
		if (self::SCOPE_COL) {
			$c->add(self::SCOPE_COL, $node->getScopeIdValue(), Criteria::EQUAL);
		}
		$c->add(self::LEFT_COL, $node->getLeftValue(), Criteria::GREATER_THAN);
		$c->addAnd(self::RIGHT_COL, $node->getRightValue(), Criteria::LESS_THAN);
		$stmt = PartCategoryPeer::doSelectStmt($c, $con);

		return PartCategoryPeer::hydrateChildren($node, $stmt);
	}

	/**
	 * Gets all descendants for the node
	 *
	 * @param      PartCategory $node	Propel object for parent node
	 * @param      PropelPDO $con	Connection to use.
	 */
	public static function retrieveDescendants(NodeObject $node, PropelPDO $con = null)
	{
		$c = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$c->addAscendingOrderByColumn(self::LEFT_COL);
		if (self::SCOPE_COL) {
			$c->add(self::SCOPE_COL, $node->getScopeIdValue(), Criteria::EQUAL);
		}
		$c->add(self::LEFT_COL, $node->getLeftValue(), Criteria::GREATER_THAN);
		$c->addAnd(self::RIGHT_COL, $node->getRightValue(), Criteria::LESS_THAN);
		$stmt = PartCategoryPeer::doSelectStmt($c, $con);

		return PartCategoryPeer::hydrateDescendants($node, $stmt);
	}

	/**
	 * Gets all siblings for the node
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @param      PropelPDO $con	Connection to use.
	 */
	public static function retrieveSiblings(NodeObject $node, PropelPDO $con = null)
	{
		$parent = PartCategoryPeer::retrieveParent($node, $con);
		$siblings = PartCategoryPeer::retrieveChildren($parent, $con);

		return $siblings;
	}

	/**
	 * Gets immediate ancestor for the given node if it exists
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     mixed 		Propel object if exists else null
	 */
	public static function retrieveParent(NodeObject $node, PropelPDO $con = null)
	{
		$c = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$c1 = $c->getNewCriterion(self::LEFT_COL, $node->getLeftValue(), Criteria::LESS_THAN);
		$c2 = $c->getNewCriterion(self::RIGHT_COL, $node->getRightValue(), Criteria::GREATER_THAN);

		$c1->addAnd($c2);

		$c->add($c1);
		if (self::SCOPE_COL) {
			$c->add(self::SCOPE_COL, $node->getScopeIdValue(), Criteria::EQUAL);
		}
		$c->addAscendingOrderByColumn(self::RIGHT_COL);

		$parent = PartCategoryPeer::doSelectOne($c, $con);

		$node->setParentNode($parent);

		return $parent;
	}

	/**
	 * Gets level for the given node
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     int			Level for the given node
	 */
	public static function getLevel(NodeObject $node, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(PartCategoryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$sql = "SELECT COUNT(*) AS level FROM " . self::TABLE_NAME . " WHERE " . self::LEFT_COL . " < :left AND " . self::RIGHT_COL . " > :right";

		if (self::SCOPE_COL) {
			$sql .= ' AND ' . self::SCOPE_COL . ' = :scope';
		}

		$stmt = $con->prepare($sql);
		$stmt->bindValue(':left', $node->getLeftValue(), PDO::PARAM_INT);
		$stmt->bindValue(':right', $node->getRightValue(), PDO::PARAM_INT);
		if (self::SCOPE_COL) {
			$stmt->bindValue(':scope', $node->getScopeIdValue());
		}
		$stmt->execute();
		$row = $stmt->fetch();
		return $row['level'];
	}

	/**
	 * Gets number of direct children for given node
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     int			Level for the given node
	 */
	public static function getNumberOfChildren(NodeObject $node, PropelPDO $con = null)
	{
		$children = PartCategoryPeer::retrieveChildren($node);
		return count($children);
	}

	/**
	 * Gets number of descendants for given node
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @param      PropelPDO $con	Connection to use.
	 * @return     int			Level for the given node
	 */
	public static function getNumberOfDescendants(NodeObject $node, PropelPDO $con = null)
	{
		$right = $node->getRightValue();
		$left = $node->getLeftValue();
		$num = ($right - $left - 1) / 2;
		return $num;
	}

	/**
	 * Returns path to a specific node as an array, useful to create breadcrumbs
	 *
	 * @param      PartCategory $node	Propel object of node to create path to
	 * @param      PropelPDO $con	Connection to use.
	 * @return     array			Array in order of heirarchy
	 */
	public static function getPath(NodeObject $node, PropelPDO $con = null)
	{
		$criteria = new Criteria();
		if (self::SCOPE_COL) {
			$criteria->add(self::SCOPE_COL, $node->getScopeIdValue(), Criteria::EQUAL);
		}
		$criteria->add(self::LEFT_COL, $node->getLeftValue(), Criteria::LESS_EQUAL);
		$criteria->add(self::RIGHT_COL, $node->getRightValue(), Criteria::GREATER_EQUAL);
		$criteria->addAscendingOrderByColumn(self::LEFT_COL);

		return self::doSelect($criteria, $con);
	}

	/**
	 * Tests if node is valid
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @return     bool
	 */
	public static function isValid(NodeObject $node = null)
	{
		if (is_object($node) && $node->getRightValue() > $node->getLeftValue()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Tests if node is a root
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @return     bool
	 */
	public static function isRoot(NodeObject $node)
	{
		return ($node->getLeftValue()==1);
	}

	/**
	 * Tests if node is a leaf
	 *
	 * @param      PartCategory $node	Propel object for src node
	 * @return     bool
	 */
	public static function isLeaf(NodeObject $node)
	{
		return (($node->getRightValue()-$node->getLeftValue())==1);
	}

	/**
	 * Tests if $child is a child of $parent
	 *
	 * @param      PartCategory $child	Propel object for node
	 * @param      PartCategory $parent	Propel object for node
	 * @return     bool
	 */
	public static function isChildOf(NodeObject $child, NodeObject $parent)
	{
		return (($child->getLeftValue()>$parent->getLeftValue()) && ($child->getRightValue()<$parent->getRightValue()));
	}

	/**
	 * Tests if $node1 is a child of or equal to $node2
	 *
	 * @deprecated 1.3 - 2007/11/09
	 * @param      PartCategory $node1		Propel object for node
	 * @param      PartCategory $node2		Propel object for node
	 * @return     bool
	 */
	public static function isChildOfOrSiblingTo(NodeObject $node1, NodeObject $node2)
	{
		return (($node1->getLeftValue()>=$node2->getLeftValue()) and ($node1->getRightValue()<=$node2->getRightValue()));
	}

	/**
	 * Tests if $node1 is equal to $node2
	 *
	 * @param      PartCategory $node1		Propel object for node
	 * @param      PartCategory $node2		Propel object for node
	 * @return     bool
	 */
	public static function isEqualTo(NodeObject $node1, NodeObject $node2)
	{
		$also = true;
		if (self::SCOPE_COL) {
			$also = ($node1->getScopeIdValue() === $node2->getScopeIdValue());
		}
		return $node1->getLeftValue() == $node2->getLeftValue() && $node1->getRightValue() == $node2->getRightValue() && $also;
	}

	/**
	 * Tests if $node has an ancestor
	 *
	 * @param      PartCategory $node		Propel object for node
	 * @param      PropelPDO $con		Connection to use.
	 * @return     bool
	 */
	public static function hasParent(NodeObject $node, PropelPDO $con = null)
	{
		return PartCategoryPeer::isValid(PartCategoryPeer::retrieveParent($node, $con));
	}

	/**
	 * Tests if $node has prev sibling
	 *
	 * @param      PartCategory $node		Propel object for node
	 * @param      PropelPDO $con		Connection to use.
	 * @return     bool
	 */
	public static function hasPrevSibling(NodeObject $node, PropelPDO $con = null)
	{
		return PartCategoryPeer::isValid(PartCategoryPeer::retrievePrevSibling($node, $con));
	}

	/**
	 * Tests if $node has next sibling
	 *
	 * @param      PartCategory $node		Propel object for node
	 * @param      PropelPDO $con		Connection to use.
	 * @return     bool
	 */
	public static function hasNextSibling(NodeObject $node, PropelPDO $con = null)
	{
		return PartCategoryPeer::isValid(PartCategoryPeer::retrieveNextSibling($node, $con));
	}

	/**
	 * Tests if $node has children
	 *
	 * @param      PartCategory $node		Propel object for node
	 * @return     bool
	 */
	public static function hasChildren(NodeObject $node)
	{
		return (($node->getRightValue()-$node->getLeftValue())>1);
	}

	/**
	 * Deletes $node and all of its descendants
	 *
	 * @param      PartCategory $node		Propel object for source node
	 * @param      PropelPDO $con		Connection to use.
	 */
	public static function deleteDescendants(NodeObject $node, PropelPDO $con = null)
	{
		$left = $node->getLeftValue();
		$right = $node->getRightValue();

		$c = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$c1 = $c->getNewCriterion(self::LEFT_COL, $left, Criteria::GREATER_THAN);
		$c2 = $c->getNewCriterion(self::RIGHT_COL, $right, Criteria::LESS_THAN);

		$c1->addAnd($c2);

		$c->add($c1);
		if (self::SCOPE_COL) {
			$c->add(self::SCOPE_COL, $node->getScopeIdValue(), Criteria::EQUAL);
		}
		$c->addAscendingOrderByColumn(self::RIGHT_COL);

		$result = PartCategoryPeer::doDelete($c, $con);

		self::shiftRLValues($right + 1, $left - $right -1, $con, $node->getScopeIdValue());

		return $result;
	}

	/**
	 * Returns a node given its primary key or the node itself
	 *
	 * @param      int/PartCategory $node	Primary key/instance of required node
	 * @param      PropelPDO $con		Connection to use.
	 * @return     object		Propel object for model
	 */
	public static function getNode($node, PropelPDO $con = null)
	{
		if (is_object($node)) {
			return $node;
		} else {
			$object = PartCategoryPeer::retrieveByPK($node, $con);
			$rtn = is_object($object) ? $object : false;
			return $rtn;
		}
	}

	/**
	 * Hydrate recursively the descendants of the given node
	 * @param      PartCategory $node	Propel object for src node
	 * @param      PDOStatement $stmt	Executed PDOStatement
	 */
	protected static function hydrateDescendants(NodeObject $node, PDOStatement $stmt)
	{
		$descendants = array();
		$children = array();
		$prevSibling = null;

		// set the class once to avoid overhead in the loop
		$cls = PartCategoryPeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = PartCategoryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null === ($child = PartCategoryPeer::getInstanceFromPool($key))) {
				$child = new $cls();
				$child->hydrate($row);
			}

			$child->setLevel($node->getLevel() + 1);
			$child->setParentNode($node);
			if (!empty($prevSibling)) {
				$child->setPrevSibling($prevSibling);
				$prevSibling->setNextSibling($child);
			}

			$descendants[] = $child;

			if ($child->hasChildren()) {
				$descendants = array_merge($descendants, PartCategoryPeer::hydrateDescendants($child, $stmt));
			} else {
				$child->setChildren(array());
			}

			$children[] = $child;
			$prevSibling = $child;

			PartCategoryPeer::addInstanceToPool($child);
			if ($child->getRightValue() + 1 == $node->getRightValue()) {
				$child->setNextSibling(null);
				break;
			}
		}
		$node->setChildren($children);
		return $descendants;
	}

	/**
	 * Hydrate the children of the given node
	 * @param      PartCategory $node Propel object for src node
	 * @param      PDOStatement $stmt Executed PDOStatement
	 */
	protected static function hydrateChildren(NodeObject $node, PDOStatement $stmt)
	{
		$children = array();
		$prevRight = 0;

		// set the class once to avoid overhead in the loop
		$cls = PartCategoryPeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = PartCategoryPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null === ($child = PartCategoryPeer::getInstanceFromPool($key))) {
				$child = new $cls();
				$child->hydrate($row);
			}

			$child->setLevel($node->getLevel() + 1);

			if ($child->getRightValue() > $prevRight) {
				$children[] = $child;
				$prevRight = $child->getRightValue();
			}

			if ($child->getRightValue() + 1 == $node->getRightValue()) {
				break;
			}
		}
		$node->setChildren($children);
		return $children;
	}

	/**
	 * Adds '$delta' to all parent R values.
	 * '$delta' can also be negative.
	 *
	 * @deprecated 1.3 - 2008/03/11
	 * @param      PartCategory $node	Propel object for parent node
	 * @param      int $delta	Value to be shifted by, can be negative
	 * @param      PropelPDO $con		Connection to use.
	 */
	protected static function shiftRParent(NodeObject $node, $delta, PropelPDO $con = null)
	{
		if ($node->hasParent($con)) {
			$parent = $node->retrieveParent();
			self::shiftRParent($parent, $delta, $con);
		}
		$node->setRightValue($node->getRightValue() + $delta);
	}

	/**
	 * Reload all already loaded nodes to sync them with updated db
	 *
	 * @param      PartCategory $node	Propel object for parent node
	 * @param      int $delta	Value to be shifted by, can be negative
	 * @param      PropelPDO $con		Connection to use.
	 */
	protected static function updateLoadedNode(NodeObject $node, $delta, PropelPDO $con = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			$keys = array();
			foreach (self::$instances as $obj) {
				$keys[] = $obj->getPrimaryKey();
			}

			if (!empty($keys)) {
				// We don't need to alter the object instance pool; we're just modifying these ones
				// already in the pool.
				$criteria = new Criteria(self::DATABASE_NAME);
				$criteria->add(PartCategoryPeer::ID, $keys, Criteria::IN);

				$stmt = PartCategoryPeer::doSelectStmt($criteria, $con);
				while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
					$key = PartCategoryPeer::getPrimaryKeyHashFromRow($row, 0);
					if (null !== ($object = PartCategoryPeer::getInstanceFromPool($key))) {
						$object->setLeftValue($row[2]);
						$object->setRightValue($row[3]);
					}
				}
				$stmt->closeCursor();
			}
		}
	}

	/**
	 * Move $node and its children to location $destLeft and updates rest of tree
	 *
	 * @param      PartCategory $node Propel object for node to update
	 * @param      int	$destLeft Destination left value
	 * @param      PropelPDO $con		Connection to use.
	 */
	protected static function updateDBNode(NodeObject $node, $destLeft, PropelPDO $con = null)
	{
		$left = $node->getLeftValue();
		$right = $node->getRightValue();

		$treeSize = $right - $left +1;

		self::shiftRLValues($destLeft, $treeSize, $con, $node->getScopeIdValue());

		if ($left >= $destLeft) { // src was shifted too?
			$left += $treeSize;
			$right += $treeSize;
		}

		// now there's enough room next to target to move the subtree
		self::shiftRLRange($left, $right, $destLeft - $left, $con, $node->getScopeIdValue());

		// correct values after source
		self::shiftRLValues($right + 1, -$treeSize, $con, $node->getScopeIdValue());
	}

	/**
	 * Adds '$delta' to all L and R values that are >= '$first'. '$delta' can also be negative.
	 *
	 * @param      int $first		First node to be shifted
	 * @param      int $delta		Value to be shifted by, can be negative
	 * @param      PropelPDO $con		Connection to use.
	 */
	protected static function shiftRLValues($first, $delta, PropelPDO $con = null, $scopeId = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(PartCategoryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$leftUpdateCol = substr(self::LEFT_COL, strrpos(self::LEFT_COL, '.') + 1);
		$rightUpdateCol = substr(self::RIGHT_COL, strrpos(self::RIGHT_COL, '.') + 1);

		// Shift left column values
		$whereCriteria = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$criterion = $whereCriteria->getNewCriterion(
			self::LEFT_COL,
			$first,
			Criteria::GREATER_EQUAL);

		if (self::SCOPE_COL) {
			$criterion->addAnd(
				$whereCriteria->getNewCriterion(
					self::SCOPE_COL,
					$scopeId,
					Criteria::EQUAL));
		}
		$whereCriteria->add($criterion);

		$valuesCriteria = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$valuesCriteria->add(
			self::LEFT_COL,
			array('raw' => $leftUpdateCol . ' + ?', 'value' => $delta),
			Criteria::CUSTOM_EQUAL);

		BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);

		// Shift right column values
		$whereCriteria = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$criterion = $whereCriteria->getNewCriterion(
			self::RIGHT_COL,
			$first,
			Criteria::GREATER_EQUAL);

		if (self::SCOPE_COL) {
			$criterion->addAnd(
				$whereCriteria->getNewCriterion(
					self::SCOPE_COL,
					$scopeId,
					Criteria::EQUAL));
		}
		$whereCriteria->add($criterion);

		$valuesCriteria = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$valuesCriteria->add(
		  self::RIGHT_COL,
			array('raw' => $rightUpdateCol . ' + ?', 'value' => $delta),
			Criteria::CUSTOM_EQUAL);

		BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
	}

	/**
	 * Adds '$delta' to all L and R values that are >= '$first' and <= '$last'.
	 * '$delta' can also be negative.
	 *
	 * @param      int $first	First node to be shifted (L value)
	 * @param      int $last	Last node to be shifted (L value)
	 * @param      int $delta	Value to be shifted by, can be negative
	 * @param      PropelPDO $con		Connection to use.
	 * @return     array 		Shifted L and R values
	 */
	protected static function shiftRLRange($first, $last, $delta, PropelPDO $con = null, $scopeId = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(PartCategoryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$leftUpdateCol = substr(self::LEFT_COL, strrpos(self::LEFT_COL, '.') + 1);
		$rightUpdateCol = substr(self::RIGHT_COL, strrpos(self::RIGHT_COL, '.') + 1);

		// Shift left column values
		$whereCriteria = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$criterion = $whereCriteria->getNewCriterion(self::LEFT_COL, $first, Criteria::GREATER_EQUAL);
		$criterion->addAnd($whereCriteria->getNewCriterion(self::LEFT_COL, $last, Criteria::LESS_EQUAL));
		if (self::SCOPE_COL) {
			$criterion->addAnd($whereCriteria->getNewCriterion(self::SCOPE_COL, $scopeId, Criteria::EQUAL));
		}
		$whereCriteria->add($criterion);

		$valuesCriteria = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$valuesCriteria->add(
			self::LEFT_COL,
			array('raw' => $leftUpdateCol . ' + ?', 'value' => $delta),
			Criteria::CUSTOM_EQUAL);

		BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);

		// Shift right column values
		$whereCriteria = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$criterion = $whereCriteria->getNewCriterion(self::RIGHT_COL, $first, Criteria::GREATER_EQUAL);
		$criterion->addAnd($whereCriteria->getNewCriterion(self::RIGHT_COL, $last, Criteria::LESS_EQUAL));
		if (self::SCOPE_COL) {
			$criterion->addAnd($whereCriteria->getNewCriterion(self::SCOPE_COL, $scopeId, Criteria::EQUAL));
		}
		$whereCriteria->add($criterion);

		$valuesCriteria = new Criteria(PartCategoryPeer::DATABASE_NAME);
		$valuesCriteria->add(
			self::RIGHT_COL,
			array('raw' => $rightUpdateCol . ' + ?', 'value' => $delta),
			Criteria::CUSTOM_EQUAL);

		BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);

		return array('left' => $first + $delta, 'right' => $last + $delta);
	}

} // BasePartCategoryNestedSetPeer
