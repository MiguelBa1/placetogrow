CREATE PROCEDURE get_invoice_distribution()
BEGIN
    SELECT
        status,
        COUNT(*) AS invoice_count
    FROM invoices
             INNER JOIN microsites m ON invoices.microsite_id = m.id
    WHERE m.type = 'invoice'
    GROUP BY status;
END;
