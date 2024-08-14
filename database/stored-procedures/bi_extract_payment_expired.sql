DROP
    PROCEDURE IF EXISTS UpdateExpiredPayments;
CREATE PROCEDURE UpdateExpiredPayments()
BEGIN
    UPDATE payments SET status = 'EXPIRED'
    WHERE payment_date < NOW() AND status NOT IN ('EXPIRED', 'APPROVED', 'OK', 'FAILED');
END;
