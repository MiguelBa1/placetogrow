CREATE PROCEDURE get_invoice_distribution(IN start_date DATE, IN end_date DATE)
BEGIN
    SELECT
        status,
        COUNT(*) AS invoice_count
    FROM invoices
             INNER JOIN microsites m ON invoices.microsite_id = m.id
    WHERE m.type = 'invoice'
      AND invoices.created_at BETWEEN start_date AND end_date
    GROUP BY status;
END;
