ALTER TABLE EMPLOYEE ADD FEE_TELEPHONE NUMERIC(12, 2);
ALTER TABLE EMPLOYEE ADD FEE_POSITION NUMERIC(12, 2);
ALTER TABLE EMPLOYEE ADD CFG_TAX NUMERIC(12, 2);

ALTER TABLE OT_DOCUMENT ADD ACTUAL_LEAVE_DEDUCT_FLAG CHAR(1);
UPDATE OT_DOCUMENT SET ACTUAL_LEAVE_DEDUCT_FLAG = 'Y';
