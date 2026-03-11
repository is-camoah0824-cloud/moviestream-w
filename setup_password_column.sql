-- Run this to add password_hash column to Users_19 for real authentication
-- (optional - site works in demo mode without it)

USE MovieStreamDB_19;
GO

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'Users_19' AND COLUMN_NAME = 'password_hash'
)
BEGIN
    ALTER TABLE Users_19 ADD password_hash VARCHAR(255) NULL;
    PRINT 'password_hash column added.';
END
GO
