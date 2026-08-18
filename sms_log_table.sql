-- ============================================================
--  Longguard — SMS Log Table  (add to database_setup.sql)
--  Run in phpMyAdmin: select longguard_db → Import this file
-- ============================================================

USE longguard_db;

CREATE TABLE IF NOT EXISTS sms_log (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    booking_id   INT DEFAULT NULL,
    recipient    VARCHAR(20) NOT NULL,
    template     VARCHAR(50) NOT NULL,
    message_body TEXT NOT NULL,
    status       ENUM('sent','failed') DEFAULT 'sent',
    sent_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (booking_id),
    INDEX (recipient)
);
