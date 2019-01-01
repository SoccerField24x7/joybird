#Joybird Code Challenge
Thank you for the opportunity to complete your code challenge!  I love these things.

## Challenge 1 - SQL Tuning
The original code executed poorly taking nearly 20 seconds to return a resultset. After removing the section of code causing the drag on the system, and moving the data selection to a sub-query, the execution time was reduced to ~0.5 seconds.

```sql
SET sql_mode='';
SELECT
	p1.part_number AS bought_number,
	p1.part_desc AS bought_item,
	p2.part_number AS sold_number,
	p2.part_desc AS sold_item,
	p1.defaut_purchase_price,
	p1.container_unit_price,
	(SELECT AVG(oc.discounted_cost / oc.quantity) FROM order_cart AS oc JOIN bom_part_sku_relation AS bpkr ON oc.fk_product_id=bpkr.fk_product_id WHERE bpkr.fk_part_id=p2.part_id) AS JAQ,
	ROUND( SUM( pocf.quantity ) ) AS total_purchased,
	GROUP_CONCAT( DISTINCT ( s.supplier_name ) SEPARATOR '<br />' ) AS vendors 
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
	p1.part_id 
ORDER BY
	total_purchased DESC
```

## Challenge 2 - Implement DataTables
I decided to implement server-side paging, because it's a pain in the ass.  What's the fun in selecting < 200 records and passing the entire data set to the library? :)  Server-side paging requires that you do a lot of the heavy lifting yourself rather than letting the library handle it. More on that in the design decisions...

Design decisions:
* Utilized a stored procedure to ensure the fastest retrieval possible.
* Implemented sorting for all columns.  This meant I would need to create a dynamic way to pass the column and sort direction into the SP. This is shown in the database/seeds/ directory.  P.S. MySQL doesn't like SP parameters for column names.
* Did not implement search (and hid the default search box).

## Challenge 3 - Implement Chart (HighCharts)
Used the HighCharts library.

Design decisions:
* Used AJAX instead of passing data from the controller.  Although this one isn't, most charts are interactive and need to be able to refresh their data without a page reload.
* Utilized JavaScript promises. The speed of the data load didn't warrant it, but it is important to ensure you have a data set before you start drawing the chart.