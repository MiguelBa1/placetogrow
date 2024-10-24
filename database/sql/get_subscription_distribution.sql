CREATE PROCEDURE get_subscription_distribution(IN start_date DATE, IN end_date DATE)
BEGIN
    SELECT
        s.status,
        COUNT(*) AS subscription_count
    FROM subscriptions s
             INNER JOIN plans p ON s.plan_id = p.id
             INNER JOIN microsites m ON p.microsite_id = m.id
    WHERE m.type = 'subscription'
      AND s.created_at BETWEEN start_date AND end_date
    GROUP BY s.status;
END;
