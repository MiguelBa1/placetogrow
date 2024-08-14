DROP
    PROCEDURE IF EXISTS UpdateExpiredPayments;
CREATE PROCEDURE UpdateExpiredPayments()
BEGIN
    UPDATE payments SET status = 'EXPIRED'
    WHERE expires_in < NOW() AND status NOT IN ('EXPIRED', 'APPROVED', 'PENDING');
END;
