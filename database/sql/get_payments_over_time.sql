CREATE PROCEDURE get_payments_over_time(IN start_date DATE, IN end_date DATE)
BEGIN
    WITH RECURSIVE dates AS (
        SELECT start_date AS day
        UNION ALL
        SELECT DATE_ADD(day, INTERVAL 1 DAY)
        FROM dates
        WHERE day < end_date
    ),
                   currencies AS (
                       SELECT 'COP' AS currency
                       UNION ALL
                       SELECT 'USD' AS currency
                   )
    SELECT
        d.day,
        c.currency,
        IFNULL(SUM(p.amount), 0) AS total_amount
    FROM dates d
             CROSS JOIN currencies c
             LEFT JOIN payments p
                       ON DATE(p.payment_date) = d.day
                           AND p.currency = c.currency
                           AND p.status = 'APPROVED'
    WHERE d.day BETWEEN start_date AND end_date
    GROUP BY d.day, c.currency
    ORDER BY d.day, c.currency;
END;
