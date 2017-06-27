SELECT * from admin_user WHERE username = 'AdminUserName';
UPDATE admin_user SET password = CONCAT(MD5('xxNewPassword'), ':xx') WHERE username = 'AdminUserName';
