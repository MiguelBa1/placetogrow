CREATE PROCEDURE get_approved_transactions_by_microsite_type()
BEGIN
    SELECT
        m.type AS microsite_type,
        COUNT(p.id) AS approved_transactions
    FROM payments p
             INNER JOIN microsites m ON p.microsite_id = m.id
    WHERE p.status = 'APPROVED'
    GROUP BY m.type;
END;
