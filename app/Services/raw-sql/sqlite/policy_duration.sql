(FLOOR(JULIANDAY(`renewal_date`)) - FLOOR(JULIANDAY(DATE("now")))) - 1 AS `duration`
