# 🌐 Network Billing System

A comprehensive Laravel-based billing and management system designed specifically for network service providers in Chogoria, Kenya. This system streamlines client management, service provisioning, billing, and network monitoring for ISPs and network companies.

## 🚀 Features

### Core Modules
- **🔐 Super Admin Authentication & Authorization** - Role-based access control with Spatie Permissions
- **📊 Dashboard & Analytics** - Real-time KPIs, revenue tracking, and performance metrics
- **👥 Client Management** - Complete customer lifecycle management
- **🖥️ Device Management** - Network infrastructure monitoring (routers, switches, access points)
- **📦 Service Plans & Packages** - Flexible internet service offerings
- **🧾 Billing & Invoicing** - Automated billing cycles and invoice generation
- **💳 Payment Processing** - Multiple payment methods and tracking
- **📡 Network Monitoring** - Usage tracking and performance monitoring
- **📈 Reports & Analytics** - Comprehensive reporting suite
- **🎫 Support Ticketing System** - Customer support management

### Key Capabilities
- **Automated Billing Cycles** - Monthly, quarterly, and annual billing
- **Usage Monitoring** - Real-time data usage tracking
- **Payment Integration** - Support for multiple payment gateways
- **Device Discovery** - Automatic network device detection
- **Customer Portal** - Self-service client interface
- **SMS & Email Notifications** - Automated communication
- **Multi-tenant Support** - Scalable architecture

## 🛠️ Technical Stack

- **Framework:** Laravel 10.x
- **Database:** MySQL 8.0+
- **Frontend:** Blade Templates with Bootstrap 5 & Tailwind CSS
- **Authentication:** Laravel Sanctum/Passport
- **Permissions:** Spatie Laravel Permission
- **Queue System:** Laravel Queues with Redis
- **Notifications:** Laravel Notifications (Email, SMS)
- **Payment Gateway:** Stripe, M-Pesa, Bank Transfer
- **Monitoring:** Laravel Telescope (Development)

## 📋 Requirements

- **PHP:** 8.1 or higher
- **MySQL:** 8.0 or higher
- **Composer:** Latest version
- **Node.js:** 16.x or higher (for asset compilation)
- **Web Server:** Apache/Nginx
- **Extensions:** BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/your-repo/network-billing-system.git
cd network-billing-system
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=billing_system
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Create Database
```bash
mysql -u root -p -e "CREATE DATABASE billing_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 6. Run Migrations & Seeders
```bash
php artisan migrate --seed
```

### 7. Generate Application Key & Storage Link
```bash
php artisan key:generate
php artisan storage:link
```

### 8. Start Development Server
```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.

## 🔑 Default Credentials

After running the seeders, you can login with:

- **Email:** admin@chogoria-network.com
- **Password:** password123

⚠️ **Important:** Change these credentials immediately in production!

## 📊 Database Schema

### Core Tables
- `users` - System users and admins
- `clients` - Customer information
- `service_plans` - Internet service packages
- `client_subscriptions` - Client service subscriptions
- `devices` - Network equipment tracking
- `invoices` - Billing records
- `payments` - Payment transactions
- `tickets` - Support requests
- `network_usages` - Data usage tracking

### Relationships
- Clients have multiple subscriptions
- Subscriptions belong to service plans
- Invoices are generated from subscriptions
- Payments are linked to invoices
- Devices can be assigned to clients

## 🎯 Usage

### Admin Dashboard
Access comprehensive analytics including:
- Monthly revenue reports
- Active client statistics
- Network usage metrics
- Outstanding invoices
- Support ticket status

### Client Management
- Add/edit client information
- Manage service subscriptions
- Track payment history
- Monitor data usage
- Handle support requests

### Billing Operations
- Generate invoices automatically
- Process payments
- Send payment reminders
- Track outstanding balances
- Generate financial reports

### Network Monitoring
- Monitor device status
- Track bandwidth usage
- Generate usage reports
- Set usage alerts
- Manage network topology

## 🔧 Configuration

### Payment Gateways
Configure payment methods in `config/services.php`:

```php
'stripe' => [
    'model' => App\Models\Client::class,
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
],

'mpesa' => [
    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'passkey' => env('MPESA_PASSKEY'),
],
```

### Email Configuration
Update `.env` for email notifications:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### Queue Configuration
For background jobs (recommended for production):
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 📱 API Documentation

The system provides RESTful APIs for:
- Client management
- Invoice operations
- Payment processing
- Device monitoring
- Usage tracking

API endpoints are available at `/api/*` with proper authentication.

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

Run specific test categories:
```bash
php artisan test --filter=ClientTest
php artisan test --filter=InvoiceTest
php artisan test --filter=PaymentTest
```

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Configure proper database credentials
4. Set up SSL certificates
5. Configure web server (Apache/Nginx)
6. Set up cron jobs for scheduled tasks
7. Configure queue workers
8. Set up monitoring and logging

### Cron Jobs
Add to your server's crontab:
```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Workers
Set up queue workers for background processing:
```bash
php artisan queue:work --daemon
```

## 🔒 Security

- All forms use CSRF protection
- Database queries use Eloquent ORM (SQL injection protection)
- User input is validated and sanitized
- Role-based access control implemented
- API endpoints require authentication
- Password hashing with bcrypt

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- **Email:** support@chogoria-network.com
- **Documentation:** [Wiki](https://github.com/your-repo/network-billing-system/wiki)
- **Issues:** [GitHub Issues](https://github.com/your-repo/network-billing-system/issues)

## 🔄 Changelog

### Version 1.0.0 (2025-07-03)
- Initial release
- Core billing functionality
- Client management system
- Payment processing
- Network device monitoring
- Support ticket system

---

**Built with ❤️ for Network Service Providers in Kenya**
