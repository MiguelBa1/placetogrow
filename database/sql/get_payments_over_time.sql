CREATE PROCEDURE get_payments_over_time()
BEGIN
    SELECT
        DATE(payment_date) AS day,
        currency,
        SUM(amount) AS total_amount
    FROM payments
    WHERE status = 'APPROVED'
      AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    GROUP BY DATE(payment_date), currency
    ORDER BY DATE(payment_date);
END;
