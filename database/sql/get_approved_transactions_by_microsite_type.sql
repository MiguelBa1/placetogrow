CREATE PROCEDURE get_approved_transactions_by_microsite_type(IN start_date DATE, IN end_date DATE)
BEGIN
    SELECT
        m.type AS microsite_type,
        COUNT(p.id) AS approved_transactions
    FROM payments p
             INNER JOIN microsites m ON p.microsite_id = m.id
    WHERE p.status = 'APPROVED'
      AND p.payment_date BETWEEN start_date AND end_date
    GROUP BY m.type;
END;
