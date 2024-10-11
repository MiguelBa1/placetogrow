CREATE PROCEDURE get_subscription_distribution()
BEGIN
    SELECT
        s.status,
        COUNT(*) AS subscription_count
    FROM subscriptions s
             INNER JOIN plans p ON s.plan_id = p.id
             INNER JOIN microsites m ON p.microsite_id = m.id
    WHERE m.type = 'subscription'
    GROUP BY s.status;
END;
