SQL database forensics involves examining databases to investigate security breaches, data tampering, unauthorized access, or data loss. This requires identifying evidence of changes, access patterns, or irregularities in database activity.

Here are key **commands and techniques** with explanations and examples to assist in SQL database forensic investigations:

---

### 1. **Checking Database Users and Permissions**

**Purpose:**  
Identify user accounts, roles, and their access levels.

**Commands:**
- For MySQL:
  ```sql
  SELECT user, host FROM mysql.user;
  ```
- For SQL Server:
  ```sql
  SELECT name, type_desc, create_date 
  FROM sys.server_principals 
  WHERE type IN ('S', 'U');
  ```

**Explanation:**  
- Shows users who have access to the database.
- Forensics investigators check for unauthorized or suspicious accounts.

**Example Output (MySQL):**  
| user       | host       |
|------------|------------|
| root       | localhost  |
| admin_user | 192.168.1.5 |

---

### 2. **Audit Log Inspection**

**Purpose:**  
Trace queries executed, user activity, and schema changes.

**Commands:**
- **MySQL** (if General Log or Binary Log is enabled):
  ```sql
  SHOW VARIABLES LIKE 'general_log';
  SHOW VARIABLES LIKE 'log_bin';
  ```
- Access logs using:
  ```bash
  cat /var/log/mysql/mysql.log
  ```

- **SQL Server** (view logs via Extended Events or Error Log):
  ```sql
  EXEC xp_readerrorlog;
  ```

**Explanation:**  
These logs help investigators understand queries executed, database modifications, and when they occurred.

**Example General Log Entry:**
```
2024-06-17T12:32:15 root[localhost]: UPDATE customers SET email='fake@mail.com' WHERE id=3;
```

---

### 3. **Checking Table Structure Modifications**

**Purpose:**  
Detect unauthorized changes to database schema.

**Commands:**
- **MySQL**:
  ```sql
  SHOW TABLE STATUS FROM database_name;
  ```
- **SQL Server**:
  ```sql
  SELECT * 
  FROM INFORMATION_SCHEMA.TABLES 
  WHERE TABLE_CATALOG = 'your_database_name';
  ```

**Explanation:**  
Examines metadata about tables, such as creation time, update time, and status.

**Example Output (MySQL):**
| Name      | Engine | Create_time         | Update_time         |
|-----------|--------|---------------------|---------------------|
| orders    | InnoDB | 2024-06-15 10:00:00 | 2024-06-17 11:45:10 |

---

### 4. **Tracking Data Changes Using Logs**

**Purpose:**  
Analyze who made changes and what data was modified.

**Commands:**
- Enable **Binary Logs** in MySQL for tracking changes:
  ```sql
  SHOW BINARY LOGS;
  ```
  To view a binary log:
  ```bash
  mysqlbinlog /var/log/mysql/binlog.000001
  ```

**Explanation:**  
Binary logs capture all INSERT, UPDATE, and DELETE operations.

**Example Output:**
```
# at 1024
#240617 12:00:25 server id 1  end_log_pos 1100   Query   thread_id=5
SET TIMESTAMP=1718612425;
DELETE FROM orders WHERE id=45;
```

---

### 5. **Querying for Suspicious Data**

**Purpose:**  
Find unexpected or unauthorized data entries.

**Commands:**
- Identify newly inserted data:
  ```sql
  SELECT * FROM table_name 
  WHERE created_at > '2024-06-01';
  ```
- Detect unusual patterns:
  ```sql
  SELECT * FROM users 
  WHERE email LIKE '%@suspiciousdomain.com';
  ```

**Explanation:**  
Examines anomalies, such as suspicious email domains, or unusually large numbers of new records.

**Example Output:**
| id  | email                 | created_at          |
|-----|-----------------------|---------------------|
| 45  | hacker@fakemail.com   | 2024-06-16 11:00:45 |

---

### 6. **Verifying Last Update Times**

**Purpose:**  
Track when rows were last modified.

**Commands:**  
- If `updated_at` column exists:
  ```sql
  SELECT id, updated_at 
  FROM table_name 
  WHERE updated_at > '2024-06-15';
  ```

**Explanation:**  
By checking timestamps, investigators can isolate data changes during suspicious events.

**Example Output:**
| id  | updated_at           |
|-----|----------------------|
| 10  | 2024-06-16 09:15:00  |

---

### 7. **Retrieving Query Execution History**

**Purpose:**  
Identify past queries executed.

**Commands:**
- MySQL (via slow query log or performance schema):
  ```sql
  SELECT * FROM performance_schema.events_statements_history;
  ```

- SQL Server:
  ```sql
  SELECT TOP 100 * 
  FROM sys.dm_exec_query_stats;
  ```

**Explanation:**  
View details of past SQL statements executed, including query time and resources consumed.

---

### 8. **Detecting Data Exfiltration**

**Purpose:**  
Find large exports or downloads of data.

**Commands:**
- Identify large SELECT statements:
  ```sql
  SHOW PROCESSLIST;
  ```
  Look for long-running queries or large data transfers.

---

### 9. **Using Triggers for Tampering Detection**

**Purpose:**  
Triggers can log changes to another table for forensic audits.

**Example Command (MySQL):**
```sql
CREATE TRIGGER log_orders_update
AFTER UPDATE ON orders
FOR EACH ROW
INSERT INTO orders_audit (order_id, old_status, new_status, changed_at)
VALUES (OLD.id, OLD.status, NEW.status, NOW());
```

**Explanation:**  
This automatically logs changes to the `orders` table.

---

### 10. **Check System Tables and Logs**

**Purpose:**  
System tables provide metadata about database structure and events.

**Commands:**  
- MySQL System Tables:
  ```sql
  SELECT * FROM INFORMATION_SCHEMA.INNODB_TRX;
  ```
- SQL Server Logs:
  ```sql
  SELECT * FROM sys.fn_dblog(NULL, NULL);
  ```

---

### Summary

SQL forensics relies on a combination of log analysis, user activity tracking, schema inspection, and data anomaly detection. By using commands like `SHOW TABLE STATUS`, enabling binary logs, and checking user roles, investigators can identify malicious changes, unauthorized access, or data exfiltration effectively.

