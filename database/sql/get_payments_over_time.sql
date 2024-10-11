CREATE PROCEDURE get_payments_over_time()
BEGIN
    SELECT
        DATE_FORMAT(payment_date, '%Y-%m') AS month,
        currency,
        SUM(amount) AS total_amount
    FROM payments
    WHERE status = 'APPROVED'
    GROUP BY DATE_FORMAT(payment_date, '%Y-%m'), currency
    ORDER BY DATE_FORMAT(payment_date, '%Y-%m');
END;
