-- Run this in SQL Server Management Studio to add poster_image column
-- if it does not already exist in Movies_19

USE MovieStreamDB_19;
GO

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'Movies_19' AND COLUMN_NAME = 'poster_image'
)
BEGIN
    ALTER TABLE Movies_19 ADD poster_image VARCHAR(255) NULL;
    PRINT 'poster_image column added successfully.';
END
ELSE
BEGIN
    PRINT 'poster_image column already exists - no changes made.';
END
GO
