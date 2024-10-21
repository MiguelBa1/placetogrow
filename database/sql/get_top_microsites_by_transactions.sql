CREATE PROCEDURE get_top_microsites_by_transactions(IN start_date DATE, IN end_date DATE)
BEGIN
    SELECT
        m.name AS microsite_name,
        COUNT(p.id) AS transaction_count
    FROM payments p
             INNER JOIN microsites m ON p.microsite_id = m.id
    WHERE p.payment_date BETWEEN start_date AND end_date
    GROUP BY m.name
    ORDER BY COUNT(p.id) DESC
    LIMIT 5;
END;
