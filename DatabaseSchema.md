### Table: User
#### Columns
欄位                    |類型         |說明            |屬性
:---------------------:|:-----------:|:-------------:|:---
ixUser                 |int(10)      |使用者編號       |AUTO_INCREMENT、UNSIGNED
sUsername              |varchar(255) |使用者名稱       |UNIQUE:uniqueUserName
sPassword              |varchar(60)  |使用者密碼       |經過 password_hash 加密
iCreatedTimestamp      |int(10)      |建立時間         |
iUpdatedTimestamp      |int(10)      |更新時間         |NULLABLE
