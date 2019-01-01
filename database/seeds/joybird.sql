-- ----------------------------
-- Procedure structure for JoybirdSales
-- ----------------------------
DROP PROCEDURE IF EXISTS `JoybirdSales`;
delimiter ;;
CREATE PROCEDURE `joybird`.`JoybirdSales`(IN startPoint INT, IN pageSize INT, IN sortColumn VARCHAR(25), IN sortDir VARCHAR(4))
BEGIN
		SET sql_mode='';
		
		SET @selectStatement = Concat('
	SELECT
		p1.part_number AS bought_number,
		p1.part_desc AS bought_item,
		p2.part_number AS sold_number,
		p2.part_desc AS sold_item,
		p1.defaut_purchase_price,
		p1.container_unit_price,
		(SELECT AVG(oc.discounted_cost / oc.quantity) FROM order_cart AS oc JOIN bom_part_sku_relation AS bpkr ON oc.fk_product_id=bpkr.fk_product_id WHERE bpkr.fk_part_id=p2.part_id) AS JAQ,
		ROUND( SUM( pocf.quantity ) ) AS total_purchased,
		GROUP_CONCAT( DISTINCT ( s.supplier_name ) SEPARATOR \'<br />\' ) AS vendors 
	FROM
		part_tags t,
		bom_parts p1
		LEFT JOIN bom_part_tree pt ON p1.part_id = pt.fk_part_id
		LEFT JOIN bom_parts p2 ON p2.part_id = pt.fk_parent_id
		LEFT JOIN bom_po_cart_final pocf ON pocf.fk_part_id = p1.part_id
		LEFT JOIN bom_purchase_orders po ON po.po_id = pocf.fk_po_id
		LEFT JOIN bom_suppliers s ON s.supplier_id = po.fk_supplier_id 
	WHERE
		t.fk_part_id = p1.part_id 
		AND t.type_label = \'EFG\' 
	GROUP BY
		p1.part_id 
	ORDER BY ',sortColumn,' ',sortDir,'
	LIMIT ',startPoint,',',pageSize);
	
	PREPARE stmt FROM @selectStatement; -- parse and prepare insert statement from the above string 
	EXECUTE stmt; -- execute statement
	DEALLOCATE PREPARE stmt; -- release the statement memory.
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for JoybirdSalesCount
-- ----------------------------
DROP PROCEDURE IF EXISTS `JoybirdSalesCount`;
delimiter ;;
CREATE PROCEDURE `joybird`.`JoybirdSalesCount`()
BEGIN
	SET sql_mode='';
	SELECT
		COUNT(1) AS Total
	FROM
		part_tags t,
		bom_parts p1
		LEFT JOIN bom_part_tree pt ON p1.part_id = pt.fk_part_id
		LEFT JOIN bom_parts p2 ON p2.part_id = pt.fk_parent_id
		LEFT JOIN bom_po_cart_final pocf ON pocf.fk_part_id = p1.part_id
		LEFT JOIN bom_purchase_orders po ON po.po_id = pocf.fk_po_id
		LEFT JOIN bom_suppliers s ON s.supplier_id = po.fk_supplier_id 
	WHERE
		t.fk_part_id = p1.part_id 
		AND t.type_label = 'EFG' 
	GROUP BY
		p1.part_id;
END
;;
delimiter ;
