# Project Overview

The Employee Management System, also known as a Human Resource Information System (HRIS), is a web-based application designed to help organizations manage employee records, department assignments, attendance tracking, leave requests, and HR reports in one centralized platform.

## Technology Stack

The system will be developed using the **Laravel MVC framework**:

- **Model** – Handles business logic and database interaction  
- **View** – Handles the presentation layer and user interface  
- **Controller** – Processes requests and coordinates models and views

## Deployment Notes (Render / Docker)

- In production, container startup now runs `php artisan migrate --force` by default.
- You can override this behavior with environment variables.
- `RUN_MIGRATIONS=true` forces migrations.
- `RUN_MIGRATIONS=false` skips migrations.
- If you use `SESSION_DRIVER=database`, make sure migrations are enabled so the `sessions` table exists.
- For MySQL providers that require SSL (for example Aiven), provide one of these:
- `MYSQL_ATTR_SSL_CA=/absolute/path/to/ca.pem` when the cert file already exists in the container.
- `MYSQL_CA_CERT` with full PEM content (supports both real newlines and escaped `\n`).
- `MYSQL_CA_CERT_BASE64` with base64-encoded PEM content.
- If `MYSQL_ATTR_SSL_CA` is already set to a generic system bundle, adding `MYSQL_CA_CERT` or `MYSQL_CA_CERT_BASE64` will override it at startup.
