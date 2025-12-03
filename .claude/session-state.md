# Mini CRM PHP - Session State
**Last Updated:** 2025-12-03 17:09 CET
**Session Duration:** ~2 hours
**Agent:** @backend-dev → @debugger → @backend-dev

---

## 📋 PROJECT OVERVIEW

**Mini CRM - Customer Management System**
- Native PHP 8.2+ (no frameworks)
- Custom MVC architecture
- MariaDB 10.11 database
- Docker containerized
- PSR-12 coding standards

**Tech Stack:**
- PHP 8.2-apache (Docker)
- MariaDB 10.11 (Docker)
- PDO with Prepared Statements
- Apache 2.4 + mod_rewrite
- HTML5 + CSS3 + Vanilla JavaScript (pending)

---

## ✅ COMPLETED WORK

### Phase 1: Infrastructure (@devops)
- ✅ `docker-compose.yml` - Multi-container orchestration (web + db)
- ✅ `Dockerfile` - PHP 8.2-apache with PDO extensions
- ✅ `docker/sql/init.sql` - Database schema + sample data
- ✅ `.env.example` - Environment configuration template
- ✅ `.gitignore` - Git exclusions

**Critical Fix:**
- **Issue:** MySQL 8.0 container failed with "CPU does not support x86-64-v2"
- **Solution:** Switched from `mysql:8.0` to `mariadb:10.11`
- **Result:** Database container healthy and running

### Phase 2: Backend Model (@backend-dev)
- ✅ `config/db.php` - Database singleton with PDO connection
- ✅ `src/CustomerModel.php` - Comprehensive CRUD operations
  - Methods: `getAll()`, `getById()`, `search()`, `create()`, `update()`, `delete()`, `count()`
  - Validation: email format, unique email constraint
  - Security: PDO prepared statements, try-catch error handling

### Phase 3: Backend Logic (@backend-dev)
- ✅ `src/CustomerController.php` - HTTP request handling
  - CRUD methods: `index()`, `create()`, `store()`, `edit()`, `update()`, `delete()`
  - CSV export: `export()` with proper Content-Type headers
  - Input validation: XSS protection, email validation, required fields
  - Pagination support: configurable items per page
- ✅ `public/index.php` - Front controller with query-string routing
- ✅ `public/.htaccess` - Apache configuration + security headers

**Verification:**
```bash
curl http://localhost:8080/index.php
# Successfully loads 5 customers from database
# View placeholder displays data correctly
```

---

## 📁 PROJECT STRUCTURE

```
mini-crm-php/
├── .claude/
│   └── session-state.md          # This file
├── config/
│   └── db.php                     # Database connection (singleton)
├── docker/
│   └── sql/
│       └── init.sql               # Database initialization
├── public/
│   ├── .htaccess                  # Apache rewrite rules + security
│   └── index.php                  # Entry point + routing
├── src/
│   ├── CustomerController.php     # HTTP request handling
│   └── CustomerModel.php          # Database operations
├── views/                         # 🚧 NOT YET CREATED
│   └── customers/
│       ├── index.php              # List view (pending)
│       ├── create.php             # Create form (pending)
│       └── edit.php               # Edit form (pending)
├── .env                           # Environment variables (DO NOT COMMIT)
├── .env.example                   # Environment template
├── .gitignore                     # Git exclusions
├── docker-compose.yml             # Container orchestration
├── Dockerfile                     # PHP 8.2 image definition
└── README.md                      # Project documentation (empty)
```

---

## 🔧 MODIFIED FILES

### Created in this session:
1. `docker-compose.yml` - **MODIFIED** (mysql → mariadb)
2. `Dockerfile`
3. `docker/sql/init.sql`
4. `.env.example`
5. `.env` (copied from .env.example)
6. `.gitignore`
7. `config/db.php`
8. `src/CustomerModel.php`
9. `src/CustomerController.php`
10. `public/index.php`
11. `public/.htaccess`
12. `.claude/session-state.md`

### File Permissions Fixed:
- Ran `chmod -R 755 /home/patrik/mini-crm-php`
- Fixed `.htaccess` and `index.php` permissions (644)

---

## 🗄️ DATABASE STATE

**Connection:**
- Host: db (Docker service name)
- Port: 3306
- Database: crm_db
- Charset: utf8mb4

**Table: customers**
```sql
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Sample Data:**
- 5 customers loaded (Ján Novák, Mária Horváthová, Peter Kováč, Eva Tóthová, Martin Szabó)

---

## 🐳 DOCKER STATE

**Containers:**
```
NAME           STATUS                  PORTS
mini-crm-db    Up 2+ hours (healthy)   0.0.0.0:3306->3306/tcp
mini-crm-web   Up 30+ seconds          0.0.0.0:8080->80/tcp
```

**Images:**
- `mariadb:10.11` - Database server
- `mini-crm-php-web` - Custom PHP 8.2-apache image

**Commands:**
```bash
# Start containers
docker-compose up -d

# Check status
docker-compose ps

# View logs
docker-compose logs web
docker-compose logs db

# Rebuild and restart
docker-compose up -d --build
```

---

## 🧪 TESTING & VERIFICATION

### Manual Testing Performed:
1. ✅ Database container health check
2. ✅ MariaDB connection test
3. ✅ Table creation verification
4. ✅ Sample data insertion
5. ✅ HTTP request to index.php
6. ✅ Data retrieval from database
7. ✅ Controller routing logic
8. ✅ Pagination calculation

### Test Commands:
```bash
# Test database connection
docker exec mini-crm-db mysql -uroot -psecret -e "USE crm_db; SHOW TABLES;"

# Test application endpoint
curl http://localhost:8080/index.php

# Check table structure
docker exec mini-crm-db mysql -uroot -psecret -e "USE crm_db; DESCRIBE customers;"
```

---

## 🎯 NEXT STEPS

### Immediate Priority (Phase 4 - Frontend Views):
1. Create `views/layout/header.php` - HTML5 layout header
2. Create `views/layout/footer.php` - HTML5 layout footer
3. Create `views/customers/index.php` - Customer list view
   - Display customer table
   - Pagination controls
   - Search form
   - Export button
   - Success/error messages
4. Create `views/customers/create.php` - Create form
   - Name, email, phone fields
   - Client-side validation
   - CSRF protection (recommended)
5. Create `views/customers/edit.php` - Edit form
   - Pre-populated fields
   - Client-side validation

### Phase 5 (Search & Export):
6. Implement search functionality in frontend
7. Test CSV export feature
8. Add JavaScript for better UX (optional)

### Phase 6 (Polish):
9. Add CSS styling (clean, responsive design)
10. Implement CSRF token protection
11. Add confirmation dialogs for delete actions
12. Create README.md documentation
13. Final testing

---

## 🚨 KNOWN ISSUES & BLOCKERS

### Resolved:
- ✅ MySQL x86-64-v2 CPU compatibility → Fixed with MariaDB
- ✅ Apache 403 Forbidden on .htaccess → Fixed with permissions
- ✅ init.sql permission denied → Fixed with chmod

### Active:
- None

### Pending Investigation:
- None

---

## 🔐 SECURITY NOTES

**Implemented Security Measures:**
- ✅ PDO Prepared Statements (SQL injection protection)
- ✅ FILTER_VALIDATE_EMAIL (email validation)
- ✅ htmlspecialchars() (XSS protection)
- ✅ Apache security headers (X-Frame-Options, X-XSS-Protection)
- ✅ .env file in .gitignore (no credentials in repo)

**Recommended Additions:**
- ⏳ CSRF token protection for forms
- ⏳ Password hashing (if auth system added)
- ⏳ Rate limiting for API endpoints
- ⏳ Input length validation
- ⏳ File upload security (if feature added)

---

## 📝 IMPORTANT NOTES

### Environment Variables:
- Database credentials are in `.env` (NOT committed)
- Default values: DB_HOST=db, DB_NAME=crm_db, DB_USER=root, DB_PASSWORD=secret
- Change password in production!

### Routing:
- All requests go through `public/index.php`
- Query-string based routing: `?action=<action>&id=<id>`
- Default action: `index` (customer list)

### Code Quality:
- Follows PSR-12 coding standards
- All methods have PHPDoc comments
- Type hints for parameters and return types
- Try-catch error handling throughout

---

## 🔄 SESSION RECOVERY

To restore this session in a new Claude Code conversation:

1. **Read this file:**
   ```
   Read /home/patrik/mini-crm-php/.claude/session-state.md
   ```

2. **Verify Docker state:**
   ```bash
   cd /home/patrik/mini-crm-php
   docker-compose ps
   ```

3. **Continue with next steps** (Phase 4 - Frontend Views)

---

## 📊 PROGRESS SUMMARY

| Phase | Description | Status | Completion |
|-------|-------------|--------|------------|
| 1 | Infrastructure (Docker) | ✅ Completed | 100% |
| 2 | Backend Model | ✅ Completed | 100% |
| 3 | Backend Logic | ✅ Completed | 100% |
| 4 | Frontend Views | 🚧 Pending | 0% |
| 5 | Search & Export | 🚧 Pending | 0% |
| 6 | Polish & Docs | 🚧 Pending | 0% |

**Overall Progress:** ~50% (3/6 phases completed)

---

## 🛠️ TOOLS & COMMANDS

### Docker Commands:
```bash
# Start/stop
docker-compose up -d
docker-compose down
docker-compose restart web

# Logs
docker-compose logs -f web
docker-compose logs -f db

# Shell access
docker exec -it mini-crm-web bash
docker exec -it mini-crm-db mysql -uroot -psecret crm_db

# Rebuild
docker-compose up -d --build
```

### Testing:
```bash
# Test endpoint
curl http://localhost:8080/index.php

# Test with action
curl http://localhost:8080/index.php?action=export

# Test search
curl "http://localhost:8080/index.php?search=Novák"
```

---

## 🎓 LESSONS LEARNED

1. **MySQL Compatibility:** Modern MySQL 8.0 images require x86-64-v2 CPU instructions - use MariaDB for older systems
2. **Docker Permissions:** Files created on host need correct permissions (755/644) for Apache to read them
3. **Apache .htaccess:** Requires `AllowOverride All` in Apache config for mod_rewrite to work
4. **MariaDB Healthcheck:** Use `healthcheck.sh` instead of `mysqladmin ping` for MariaDB containers

---

**🚀 Ready to continue with Phase 4: Frontend Views**

*Session saved successfully. To restore, read this file in your next conversation.*
