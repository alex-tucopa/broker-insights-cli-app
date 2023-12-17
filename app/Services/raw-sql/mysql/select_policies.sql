SELECT 
    `policy`.`broker_policy_ref`, 
    `policy`.`effective_date`, 
    `policy`.`renewal_date`, 
    FLOOR(`policy`.`amount_insured`) AS `amount_insured`, 
    FLOOR(`policy`.`premium`) AS `premium`, 
    `customer_type`.`name` AS `customer_type`, 
    `product`.`name` AS `product_name`, 
    `product_type`.`name` AS `product_type`,
    `insurer`.`name` AS `insurer_name`,
    CASE 
        WHEN `effective_date` <= :today_1 AND `renewal_date` > :today_2 
            THEN 1 
            ELSE 0 
        END `is_active`, 
    CASE 
        WHEN `effective_date` <= :today_3 AND `renewal_date` > :today_4 
            THEN DATEDIFF(`renewal_date`, CURRENT_DATE()) - 1
            ELSE 0 
        END `duration` 
FROM `policy` 
INNER JOIN `customer_type` ON `policy`.`customer_type_id` = `customer_type`.`id` 
INNER JOIN `product` ON `policy`.`product_id` = `product`.`id`
INNER JOIN `product_type` ON `product`.`product_type_id` = `product_type`.`id`
INNER JOIN `insurer` ON `product`.`insurer_id` = `insurer`.`id`
