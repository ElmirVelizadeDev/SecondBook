# 📚 SecondBook

> **A modern Laravel-based online marketplace for buying and selling books.**

SecondBook is a full-featured online book marketplace designed to connect **book buyers and approved sellers** in one platform.

The project combines a customer-facing bookstore, seller marketplace, and comprehensive administration panel into a single Laravel application.

Users can discover books, search and filter the catalog, manage wishlists and shopping carts, place orders, track their purchases, and leave reviews. Approved sellers can manage their own stores, books, orders, and sales, while administrators can control the entire platform through a dedicated admin panel.

---

## ✨ Features

### 🛍️ Customer Marketplace

* Browse available books
* Search books by title and other available information
* Filter books by condition
* Sort book listings
* Browse books by categories
* Browse books by authors
* View detailed book information
* View book condition and availability
* Add books to wishlist
* Remove books from wishlist
* Add books to shopping cart
* Update cart quantities
* Remove items from cart
* AJAX-based cart interactions
* Dynamic cart item count
* Checkout system
* Shipping information
* Estimated delivery information
* Order creation
* Order history
* Order details
* Order status information
* Buyer reviews
* Review management
* Contact system
* User profile
* Account settings
* Password reset with OTP verification
* User notifications
* Responsive frontend interface

---

# 🏪 Seller Marketplace

SecondBook uses a dedicated seller architecture instead of allowing every registered user to sell books.

Normal users can apply to become sellers. After administrative approval, the user's account can become a seller account and receive an associated store.

### Seller Application

* Become a Seller page
* Seller application form
* Application submission
* Application status
* Admin application management
* Admin approval
* Admin rejection
* Automatic seller role transition after approval
* Store creation for approved sellers

### Seller Panel

Approved sellers have access to a dedicated seller panel.

Seller features include:

* Seller dashboard
* Store management
* Store information
* Store settings
* Seller profile information
* Book management
* Create books
* Edit books
* Delete books
* Manage book stock
* Manage book prices
* Manage book conditions
* Seller-specific book listings
* Seller order management
* Order details
* Order processing
* Order notes
* Processing deadlines
* Sales overview
* Seller reviews

The seller panel is separated from the administrator panel to keep seller functionality and platform administration independent.

---

# 👤 User Roles

SecondBook currently uses three main user roles:

| Role     | Description                   |
| -------- | ----------------------------- |
| `admin`  | Full platform administration  |
| `user`   | Customer/buyer account        |
| `seller` | Approved seller/store account |

### User

A regular user can:

* Browse books
* Search and filter books
* Manage wishlist
* Manage cart
* Checkout
* Place orders
* View order history
* Review purchased books
* Manage account settings
* Apply to become a seller

### Seller

A seller is an approved store account that can:

* Manage their store
* Add books
* Edit books
* Manage inventory
* Manage seller orders
* Process orders
* View sales
* Receive buyer reviews

### Admin

Administrators have access to the platform management system and can manage users, sellers, books, orders, reviews, content, settings, reports and other platform functionality.

---

# ⭐ Buyer Review System

SecondBook includes a buyer review system that connects reviews with the platform's users, books and orders.

The review architecture allows the platform to associate:

```text
User
  │
  └── Review
        │
        ├── Book
        └── Order
```

This makes it possible to maintain a relationship between the buyer, purchased book and corresponding order.

Review functionality includes:

* Buyer reviews
* Review creation
* Review management
* Book-review relationships
* User-review relationships
* Order-review relationships
* Admin review management
* Review detail page

---

# 📚 Book Marketplace

Books are the core marketplace entity.

A book can contain information such as:

* Title
* ISBN
* Description
* Cover
* Publication year
* Number of pages
* Language
* Price
* Stock
* Condition
* Status
* Category
* Author
* Publisher
* Seller

### Book Conditions

SecondBook supports multiple book conditions:

* `new`
* `like_new`
* `good`
* `fair`

### Book Status

Books can have administrative statuses such as:

* `pending`
* `approved`
* `rejected`

### Seller Relationship

Books can be associated with their seller through:

```text
Book
 └── seller_id
       └── User
```

This allows the marketplace to identify which seller owns and manages a particular book.

---

# 🛒 Cart & Checkout

SecondBook includes a complete shopping flow:

```text
Book
 ↓
Add to Cart
 ↓
Cart
 ↓
Checkout
 ↓
Shipping Information
 ↓
Order
 ↓
Payment
 ↓
Order Processing
 ↓
Delivery
```

The frontend cart uses AJAX interactions to provide a smoother shopping experience without requiring a full page reload for every cart operation.

The header cart count can also be updated dynamically after successful cart actions.

---

# 🚚 Shipping

The project includes configurable shipping functionality.

Shipping-related functionality includes:

* Shipping settings
* Delivery information
* Order shipping fields
* Estimated delivery message
* Shipping configuration
* Checkout shipping information

The estimated delivery message can be configured through the application's shipping settings.

---

# 💳 Payments

SecondBook supports multiple payment method options:

* Cash on Delivery
* Credit Card
* Debit Card
* PayPal

Payment records can contain information such as:

* Transaction ID
* Order ID
* Amount
* Payment method
* Payment status
* Paid date
* Notes

---

# 📦 Orders

The order system connects customers, books, sellers, payments and shipping information.

Order functionality includes:

* Order creation
* Order listing
* Order details
* Customer order history
* Seller order management
* Order status
* Payment information
* Shipping information
* Processing deadline
* Order notes
* Refund-related functionality

The seller order flow is designed around seller-owned books and seller-specific order management.

---

# ❤️ Wishlist

Users can maintain a personal wishlist.

Wishlist functionality includes:

* Add book to wishlist
* Remove book from wishlist
* Wishlist state handling
* AJAX wishlist interactions
* Wishlist database relationships

---

# 🔐 Authentication & Account Management

SecondBook includes an authentication system with account management functionality.

Features include:

* User registration
* User login
* Logout
* Remember me
* Email verification handling
* Password reset
* OTP-based password reset
* Six-digit OTP
* OTP resend cooldown
* Account settings
* User settings
* Account status handling

The application also supports configurable registration settings through the platform settings system.

---

# 🛡️ Authorization & Permissions

The application separates access between different types of users.

The platform uses:

* Authentication middleware
* Admin middleware
* Seller middleware
* Permission-based admin access
* Role-based access control

The seller middleware helps prevent normal users from accessing seller-specific functionality.

The admin area also uses permission-based authorization for protected administrative modules.

---

# 🖥️ Admin Panel

SecondBook includes a dedicated administration interface for managing the platform.

The admin panel is built separately from the customer and seller interfaces.

### Admin Modules

The current admin architecture includes modules for:

* Dashboard
* Books
* Categories
* Authors
* Publishers
* Book Conditions
* Book Requests
* Orders
* Payments
* Coupons
* Shipping
* Refunds
* Users
* Sellers
* Seller Applications
* Roles
* Reviews
* Messages
* Banners
* Blogs
* FAQ
* Reports
* Analytics
* Settings
* Email Settings
* Notifications
* Activity Logs
* Backup

---

# 📊 Admin Dashboard & Analytics

The administration dashboard provides platform-level information and management tools.

Dashboard information includes statistics and recent platform activity.

Analytics functionality includes date-based filtering and platform data analysis involving areas such as:

* Books
* Orders
* Users

The project also includes reporting interfaces for:

* Book reports
* Sales reports
* User reports

---

# 🎨 Admin UI

The admin panel uses a modern dashboard-oriented interface.

Technologies and UI components include:

* Bootstrap 5
* Bootstrap Icons
* Plus Jakarta Sans
* Chart.js
* SweetAlert2
* Custom CSS architecture
* Responsive layouts
* Dark mode

The admin theme supports light and dark modes while preserving the selected theme using browser storage.

---

# 🌙 Dark Mode

The admin panel supports a dedicated dark theme.

The theme state is stored using:

```text
admin_theme
```

The interface uses:

```html
data-theme="dark"
```

for dark theme activation.

Dark-mode styling is also applied to interactive components such as alerts and form controls.

---

# 📝 Content Management

SecondBook includes several content-management areas inside the admin panel.

### Banners

Administrators can manage promotional or informational banners.

### Blogs

Administrators can manage blog content and related information.

### FAQ

Frequently asked questions can be managed through the administration system.

### Messages

The messaging system includes:

* User messages
* Message management
* Message replies
* Administrative responses

---

# 🔔 Notifications

The project includes notification-related functionality for platform users.

Notification data can be managed through the application and seeded during development.

---

# 🗄️ Database & Seeders

SecondBook uses Laravel migrations and seeders to build and populate the application database.

The project includes seeders for areas such as:

* Roles and permissions
* Users
* Categories
* Publishers
* Authors
* Books
* Stores
* Seller books
* Seller orders
* Seller applications
* Reviews
* Wishlists
* Coupons
* FAQ
* Messages
* Message replies
* Notifications
* Settings
* User settings
* Shipping
* Banners
* Blogs

The main database seeding process is coordinated through:

```text
DatabaseSeeder
```

The seeders are ordered so that required relationships can be created correctly.

---

# 🏗️ Application Architecture

The application follows Laravel's MVC architecture.

```text
SecondBook
│
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── Admin
│   │   │   ├── Frontend
│   │   │   └── Seller
│   │   └── Middleware
│   │
│   ├── Models
│   │
│   └── ...
│
├── database
│   ├── migrations
│   └── seeders
│
├── public
│   ├── admin
│   │   ├── css
│   │   ├── images
│   │   └── js
│   │
│   └── ...
│
├── resources
│   └── views
│       ├── Admin
│       ├── Frontend
│       ├── Seller
│       └── errors
│
├── routes
│   └── web.php
│
└── ...
```

---

# 🔗 Main Relationships

The project contains several important Eloquent relationships.

### User

A user can be related to:

* Orders
* Books as seller
* Reviews
* Wishlist items
* Seller application
* Store
* User settings
* Notifications

### Book

A book can belong to:

* Category
* Author
* Publisher
* Seller

A book can also have:

* Reviews
* Orders
* Wishlist relationships

### Store

A store belongs to an approved seller.

```text
User
 │
 └── Store
```

### Seller Application

The seller application connects a customer with the seller approval process.

```text
User
 │
 └── SellerApplication
```

---

# ⚙️ Technologies

SecondBook is built with:

### Backend

* PHP
* Laravel
* Laravel Eloquent ORM
* Laravel Middleware
* Laravel Blade

### Frontend

* HTML5
* CSS3
* JavaScript
* Bootstrap 5
* Bootstrap Icons
* AJAX / Fetch API

### UI & Visualization

* Chart.js
* SweetAlert2
* Custom responsive CSS

### Database

* MySQL

### Development Environment

* Laragon
* Git
* GitHub
* Visual Studio Code

---

# 🚀 Installation

## 1. Clone the repository

```bash
git clone https://github.com/ElmirVelizadeDev/SecondBook.git
```

## 2. Enter the project

```bash
cd SecondBook
```

## 3. Install PHP dependencies

```bash
composer install
```

## 4. Create environment file

```bash
cp .env.example .env
```

On Windows PowerShell, you can also create the environment file manually from `.env.example`.

## 5. Generate application key

```bash
php artisan key:generate
```

## 6. Configure the database

Update the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=secondbook
DB_USERNAME=root
DB_PASSWORD=
```

Adjust the database credentials according to your local environment.

## 7. Run migrations

```bash
php artisan migrate
```

## 8. Seed the database

```bash
php artisan db:seed
```

Or, if you are working with a fresh development database:

```bash
php artisan migrate:fresh --seed
```

## 9. Create storage link

```bash
php artisan storage:link
```

## 10. Start the Laravel development server

```bash
php artisan serve
```

The application will then be available through the local Laravel server.

---

# 🧪 Development

During development, useful Laravel commands include:

```bash
php artisan route:list
```

```bash
php artisan migrate:status
```

```bash
php artisan db:seed
```

```bash
php artisan optimize:clear
```

```bash
php artisan storage:link
```

---

# 🔄 Database Reset

For a clean development environment:

```bash
php artisan migrate:fresh --seed
```

> **Warning:** This command deletes existing database tables and recreates them.

---

# 📱 Responsive Design

The application is designed to work across different screen sizes.

Responsive interfaces are provided for:

* Desktop
* Laptop
* Tablet
* Mobile

Both the customer-facing application and administration interface contain responsive styling.

---

# 🎯 Project Goals

SecondBook was developed with several goals in mind:

* Create a complete online book marketplace
* Support both buyers and approved sellers
* Provide independent seller stores
* Provide centralized platform administration
* Build a practical Laravel marketplace architecture
* Implement real-world order and shipping flows
* Connect buyers, sellers and books through meaningful relationships
* Provide a maintainable MVC-based codebase
* Create a responsive and modern user interface

---

# 🔮 Future Improvements

Potential future improvements include:

* More advanced seller analytics
* Advanced search
* Improved recommendation system
* More payment integrations
* Automated email notifications
* More detailed sales reports
* Advanced inventory management
* Product image optimization
* API layer for mobile applications
* Automated testing
* CI/CD with GitHub Actions
* Improved application monitoring
* Additional marketplace features

---

# 📸 Screenshots

Screenshots of the following interfaces can be added here:

* Home page
* Books page
* Book details
* Cart
* Checkout
* Orders
* Seller dashboard
* Seller books
* Seller orders
* Store settings
* Admin dashboard
* Admin analytics
* Admin reports
* Admin reviews

Example:

```text
screenshots/
├── home.png
├── books.png
├── book-details.png
├── checkout.png
├── seller-dashboard.png
├── seller-books.png
├── admin-dashboard.png
└── admin-analytics.png
```

---

# 🔒 Security

The project uses Laravel's built-in security mechanisms together with application-level authorization.

Important areas include:

* Authentication
* CSRF protection
* Middleware
* Role-based authorization
* Permission-based authorization
* Seller authorization
* Form validation
* Protected administrative routes

Sensitive environment configuration should remain inside `.env` and should never be committed to the repository.

---

# 🌱 Project Status

SecondBook is an actively developed Laravel marketplace project.

The core marketplace architecture includes:

```text
Buyer
  │
  ├── Browse Books
  ├── Wishlist
  ├── Cart
  ├── Checkout
  ├── Orders
  └── Reviews
          │
          ▼
       Books
          │
          ▼
       Sellers
          │
          ▼
        Stores
          │
          ▼
        Admin
```

---

# 👨‍💻 Developer

**Elmir Velizade**

Laravel / PHP / Web Development

SecondBook is developed as a full-stack Laravel marketplace project with a focus on practical application architecture, database relationships, authentication, authorization, marketplace workflows and responsive UI development.

---

## 📄 License

This project is currently intended as a personal/portfolio development project.

---

⭐ **If you find the project interesting, feel free to explore the repository and follow the development progress.**


Clone the repository:


git clone https://github.com/ElmirVelizadeDev/SecondBook.git
