CREATE PROCEDURE get_top_microsites_by_transactions()
BEGIN
    SELECT
        m.name AS microsite_name,
        COUNT(p.id) AS transaction_count
    FROM payments p
             INNER JOIN microsites m ON p.microsite_id = m.id
    GROUP BY m.name
    ORDER BY COUNT(p.id) DESC
    LIMIT 5;
END;
