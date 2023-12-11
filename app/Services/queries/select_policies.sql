SELECT 
    `policy`.*, 
    FLOOR(JULIANDAY(`renewal_date`)) - FLOOR(JULIANDAY()) AS `duration`,
    `customer_type`.`name` AS `customer_type`, 
    `product`.`name` AS `product_name`, 
    `product_type`.`name` AS `product_type`,
    `insurer`.`name` AS `insurer_name`,
    CASE 
        WHEN `effective_date` <= :today AND `renewal_date` > :today 
            THEN 1 
            ELSE 0 
        END `is_active` 
FROM `policy` 
INNER JOIN `customer_type` ON `policy`.`customer_type_id` = `customer_type`.`id` 
INNER JOIN `product` ON `policy`.`product_id` = `product`.`id`
INNER JOIN `product_type` ON `product`.`product_type_id` = `product_type`.`id`
INNER JOIN `insurer` ON `product`.`insurer_id` = `insurer`.`id`
