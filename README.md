# SecondBook

**SecondBook** is a full-stack Laravel-based online book marketplace designed for buying, selling, managing, and discovering books through a modern multi-role platform.

The platform supports three main user roles — **Admin, Seller, and User/Buyer** — with separate interfaces and responsibilities.

SecondBook combines a customer-facing marketplace, seller management panel, and comprehensive administration panel into one Laravel application.

---

## 📑 Table of Contents

* [Project Overview](#project-overview)
* [Main Features](#main-features)
* [User System](#user-system)
* [Authentication & Security](#authentication--security)
* [Seller Marketplace](#seller-marketplace)
* [Seller Stores](#seller-stores)
* [Books](#books)
* [Book Discounts](#book-discounts)
* [Categories](#categories)
* [Authors](#authors)
* [Publishers](#publishers)
* [Wishlist](#wishlist)
* [Orders](#orders)
* [Shipping](#shipping)
* [Payments](#payments)
* [Refunds](#refunds)
* [Reviews](#reviews)
* [Messaging System](#messaging-system)
* [Message Replies](#message-replies)
* [Notifications](#notifications)
* [Promotional Banners](#promotional-banners)
* [Blog](#blog)
* [FAQ](#faq)
* [Coupons](#coupons)
* [Settings System](#settings-system)
* [Roles & Permissions](#roles--permissions)
* [Activity Logs](#activity-logs)
* [Archive System](#archive-system)
* [Admin Panel](#admin-panel)
* [Seller Panel](#seller-panel)
* [User Interface](#user-interface)
* [Admin Dark Mode](#admin-dark-mode)
* [Responsive Design](#responsive-design)
* [Database Architecture](#database-architecture)
* [Important Database Relationships](#important-database-relationships)
* [Project Architecture](#project-architecture)
* [Technologies](#technologies)
* [Installation](#installation)
* [Useful Laravel Commands](#useful-laravel-commands)
* [Development Workflow](#development-workflow)
* [Security Considerations](#security-considerations)
* [Database Constraints](#database-constraints)
* [Project Status](#project-status)
* [Future Improvements](#future-improvements)
* [Developer](#developer)
* [License](#license)

---

## 📚 Project Overview

SecondBook provides a complete marketplace experience where users can:

* Browse books and categories
* Search and discover books
* View detailed book information
* Add books to their wishlist
* Purchase books
* Manage orders
* Make payments
* Submit book reviews
* Contact sellers
* Receive notifications
* Manage their account and preferences

Approved sellers can:

* Create and manage their own store
* Add and manage books
* Manage stock and pricing
* Configure discounts
* Manage orders
* Track sales
* Manage customer reviews
* Communicate with customers
* View analytics
* Manage notifications
* Configure store settings

Administrators can manage the complete platform, including users, sellers, books, orders, payments, refunds, coupons, shipping, content, permissions, settings, and activity logs.

---

# ✨ Main Features

## 🛍️ Customer Marketplace

The customer-facing marketplace provides:

* Book catalog
* Category browsing
* Book details
* Author information
* Publisher information
* Seller/store information
* Book conditions
* Stock information
* Pricing
* Discount pricing
* Wishlist
* Shopping cart
* Checkout
* Shipping information
* Payment selection
* Order tracking
* Reviews
* Seller messaging
* Notifications
* FAQ
* Blog content
* Promotional banners

---

# 👤 User System

Users are stored in the `users` table.

### Account Roles

The database supports:

* `admin`
* `user`
* `seller`

### Account Status

Users can have:

* `active`
* `inactive`
* `banned`

### User Information

The user profile supports:

* First name
* Last name
* Username
* Email
* Password
* Profile photo
* Phone
* Date of birth
* Gender
* Country
* City
* State
* Postal code
* Address
* Biography

### User Preferences

Users can configure:

* Email notifications
* Order updates
* Promotional emails
* Profile visibility

The project also has a dedicated `user_settings` table for user-specific preferences.

---

# 🔐 Authentication & Security

SecondBook includes an authentication system with:

* Registration
* Email verification
* OTP verification
* Login
* Logout
* Password reset
* Password reset OTP
* Account status restrictions
* Login information tracking

The `otps` table supports different OTP purposes through the `purpose` field.

Supported OTP purposes can be extended by the application logic.

The project also uses:

* Role-based access
* Permission-based authorization
* Middleware protection
* Activity logging
* Login security controls
* User status restrictions

---

# 🏪 Seller Marketplace

SecondBook supports a dedicated seller marketplace architecture.

A normal user can apply to become a seller.

## Seller Application

Seller applications are stored in `seller_applications`.

Each application contains:

* User
* Store name
* Description
* Phone
* Address
* Status
* Rejection reason
* Review timestamp

Application statuses:

* `pending`
* `approved`
* `rejected`

After approval, the application can be used by the application logic to establish the seller account/store relationship.

---

# 🏬 Seller Stores

Approved sellers can have their own store.

Stores are stored in the `stores` table.

Each store supports:

* Store name
* Unique slug
* Description
* Logo
* Phone
* Address
* Status

Store statuses:

* `active`
* `inactive`

### Store Settings

Stores can also configure:

* Accept orders
* Auto approve orders
* Processing time
* Minimum order amount
* Order note

Each seller can have one store because `seller_id` is unique in the `stores` table.

---

# 📖 Books

Books are stored in the `books` table.

Each book supports:

### Basic Information

* Title
* ISBN
* Description
* Cover image

### Bibliographic Information

* Category
* Author
* Publisher
* Publication year
* Number of pages
* Language

### Marketplace Information

* Seller
* Price
* Stock
* Condition
* Status

### Book Conditions

Supported conditions:

* `new`
* `like_new`
* `good`
* `fair`

### Book Status

Supported statuses:

* `pending`
* `approved`
* `rejected`

---

# 💰 Book Discounts

Books support an independent discount system.

### Discount Types

* `none`
* `percentage`
* `fixed`

Discount information includes:

* Discount type
* Discount value
* Discount start date
* Discount end date

This allows the marketplace to support temporary promotional pricing.

---

# 📚 Categories

Categories support:

* Name
* Unique slug
* Description
* Image
* Status

Categories can be activated or deactivated.

---

# ✍️ Authors

Authors support:

* Name
* Biography
* Photo
* Status

Author information is connected to books through `author_id`.

---

# 🏢 Publishers

Publishers support:

* Name
* Status
* Logo
* Country
* Website
* Description

Publisher information is connected to books through `publisher_id`.

---

# ❤️ Wishlist

Users can save books to their wishlist.

The `wishlists` table connects:

```text
User → Book
```

A user cannot add the same book to their wishlist more than once because of the unique:

```text
user_id + book_id
```

constraint.

---

# 🛒 Orders

Orders are stored in the `orders` table.

Each order contains:

* Unique order number
* User
* Book
* Book price
* Quantity
* Total price
* Shipping fee
* Shipping method
* Payment method
* Payment status
* Order status
* Processing deadline
* Order note
* Customer information
* Delivery information
* Delivery estimate
* Archive timestamp

### Order Statuses

* `pending`
* `processing`
* `shipped`
* `delivered`
* `cancelled`

### Payment Statuses

* `pending`
* `paid`
* `failed`
* `refunded`

### Payment Methods

* Cash on delivery
* Credit card
* Debit card
* PayPal

Orders store customer delivery information directly, including:

* Full name
* Phone
* Country
* City
* Postal code
* Address

---

# 🚚 Shipping

Shipping methods are stored in the `shippings` table.

Each shipping method supports:

* Name
* Description
* Price
* Delivery time
* Status

Orders can optionally reference a shipping method through:

```text
orders.shipping_id → shippings.id
```

The order also stores its actual shipping fee and delivery estimate.

---

# 💳 Payments

Payments are stored separately from orders in the `payments` table.

Each payment contains:

* Unique transaction ID
* Order
* Amount
* Payment method
* Payment status
* Paid timestamp
* Note

Payment records are connected to orders through:

```text
payments.order_id → orders.id
```

---

# 💸 Refunds

The platform supports refund management through the `refunds` table.

A refund contains:

* Order
* Payment
* User
* Processor
* Unique refund number
* Amount
* Reason
* Note
* Status
* Requested timestamp
* Processed timestamp

### Refund Statuses

* `pending`
* `approved`
* `rejected`
* `processed`
* `cancelled`

The database intentionally restricts deleting an order when a refund references it.

---

# ⭐ Reviews

Users can review books through the `reviews` table.

A review contains:

* User
* Book
* Rating
* Comment
* Status
* Archive timestamp

### Review Statuses

* `pending`
* `approved`
* `rejected`

Each user can have only one review for the same book because of:

```text
user_id + book_id
```

unique constraint.

> Reviews are directly related to users and books in the database. There is no `order_id` foreign key in the reviews table.

---

# 💬 Messaging System

SecondBook includes a customer-to-seller messaging system.

Messages contain:

* User
* Seller
* Name
* Email
* Subject
* Message
* Status
* Archive timestamp

Message statuses include:

* `unread`
* `read`

The system supports seller-specific message filtering through indexed:

```text
seller_id + status
```

and user-specific filtering through:

```text
user_id + status
```

---

# ↩️ Message Replies

Messages can have multiple replies.

The `message_replies` table contains:

* Message
* User
* Sender type
* Reply
* Read timestamp

Replies are connected to their parent message through:

```text
message_replies.message_id → messages.id
```

The `sender_type` field identifies the sender type used by the application.

Read/unread reply handling is supported through `read_at`.

---

# 🔔 Notifications

SecondBook includes an application-level notification system.

Notifications contain:

* User
* Type
* Title
* Message
* Read timestamp
* Archive timestamp

Notifications support:

* Read/unread state
* User-specific notifications
* Notification indexing
* Archived notifications

---

# 📣 Promotional Banners

Administrators can manage marketplace banners.

Banners support:

* Title
* Subtitle
* Image
* Button text
* Button URL
* Position
* Status
* Start date
* End date

Banner statuses:

* `active`
* `inactive`

---

# 📝 Blog

The platform includes a blog/content management system.

Blog posts support:

* Title
* Unique slug
* Excerpt
* Content
* Image
* Author
* Status
* Published timestamp

Blog statuses:

* `draft`
* `published`

---

# ❓ FAQ

Frequently asked questions are stored in the `faqs` table.

Each FAQ supports:

* Category
* Question
* Answer
* Active/inactive state
* Sort order

---

# 🎟️ Coupons

The marketplace supports discount coupons.

Coupons include:

* Unique coupon code
* Discount type
* Discount value
* Minimum order amount
* Maximum discount amount
* Usage limit
* Used count
* Start date
* Expiration date
* Status

### Coupon Types

* `percentage`
* `fixed`

---

# ⚙️ Settings System

The platform has a centralized `settings` table.

Each setting contains:

* Unique key
* Group name
* Value
* Type

Settings can therefore be grouped and represented using different data types.

---

# 🛡️ Roles & Permissions

SecondBook contains a dedicated role and permission system.

### Roles

Roles contain:

* Name
* Display name
* Description
* System role flag

### Permissions

Permissions contain:

* Name
* Display name
* Group name
* Description

### Pivot Tables

The authorization structure uses:

```text
role_user
permission_role
```

This allows users to have roles and roles to have permissions.

---

# 📊 Activity Logs

Administrative and application actions can be recorded through `activity_logs`.

Each activity log contains:

* User
* Action
* Module
* Description
* IP address
* User agent
* Timestamp

Indexes are provided for:

* Action
* Module
* Creation time

This provides an audit trail for important platform operations.

---

# 🗂️ Archive System

SecondBook uses an application-level archive mechanism for historical records.

The following tables support `archived_at`:

* `messages`
* `orders`
* `notifications`
* `reviews`

An archive timestamp allows records to be hidden from normal active lists without necessarily deleting their database records.

---

# 🖥️ Admin Panel

The administration panel provides centralized management of the platform.

Major administrative areas include:

* Dashboard
* Books
* Categories
* Authors
* Publishers
* Book conditions
* Book requests
* Orders
* Payments
* Coupons
* Shipping
* Refunds
* Users
* Sellers
* Roles
* Permissions
* Reviews
* Messages
* Banners
* Blog
* FAQ
* Reports
* Analytics
* Settings
* Email settings
* Notifications
* Activity logs
* Backup

The admin panel is designed as a separate management interface from the customer marketplace and seller panel.

---

# 🏪 Seller Panel

Approved sellers have access to a dedicated Seller Panel.

Main sections include:

### Dashboard

Seller overview and marketplace activity.

### Store

Manage:

* Store information
* Store settings
* Order preferences
* Processing time
* Minimum order amount

### Books

Manage seller-owned books:

* Create
* View
* Edit
* Delete
* Stock
* Pricing
* Discounts
* Conditions
* Status

### Orders

Manage seller-related orders and order processing.

### Sales

Review seller sales information.

### Reviews

View and manage customer reviews associated with seller books.

### Analytics

View seller marketplace analytics.

### Messages

Communicate with customers who contact the seller's store.

### Notifications

View and manage seller notifications.

### Settings

Manage seller-specific preferences and store configuration.

---

# 🎨 User Interface

SecondBook uses separate UI systems for its main application areas.

## Customer Frontend

The customer interface focuses on:

* Marketplace browsing
* Book discovery
* Product details
* Shopping
* Checkout
* Account management

## Seller Panel

The Seller Panel uses a clean, modern and responsive interface focused on marketplace management.

## Admin Panel

The Admin Panel uses a dedicated dashboard interface with:

* Sidebar navigation
* Header
* Dashboard cards
* Tables
* Filters
* Forms
* Modal dialogs
* Status badges
* Responsive layouts
* Dark theme support

---

# 🌙 Admin Dark Mode

The Admin Panel supports a dedicated dark interface.

Dark-mode styling covers major administrative components such as:

* Sidebar
* Header
* Tables
* Forms
* Pagination
* Badges
* Select elements
* Cards
* Modals
* Dashboard components

The Seller and customer-facing interfaces use their own visual systems rather than sharing the Admin Panel's dark-mode implementation.

---

# 📱 Responsive Design

The project is designed for different screen sizes.

Responsive layouts are implemented across:

* Customer pages
* Seller Panel
* Admin Panel
* Tables
* Forms
* Dashboards
* Navigation
* Book pages
* Order pages

---

# 🗄️ Database Architecture

The application uses **MySQL** with Laravel migrations.

Main database entities include:

```text
users
├── stores
├── seller_applications
├── orders
├── reviews
├── wishlists
├── notifications
├── messages
├── message_replies
├── user_settings
├── activity_logs
└── roles

books
├── categories
├── authors
├── publishers
├── users (seller)
├── reviews
└── wishlists

orders
├── users
├── books
├── payments
├── refunds
└── shippings
```

Additional platform entities include:

```text
roles
permissions
role_user
permission_role

categories
authors
publishers
coupons
shippings
settings
faqs
banners
blogs
otps
```

---

# 🔗 Important Database Relationships

### User → Books

```text
users.id
    ↓
books.seller_id
```

A book can optionally belong to a seller.

If the seller is deleted, the seller reference on the book is set to `NULL`.

### User → Store

```text
users.id
    ↓
stores.seller_id
```

`stores.seller_id` is unique, meaning a seller can have one store.

### User → Orders

```text
users.id
    ↓
orders.user_id
```

Deleting a user cascades to their orders.

### Book → Orders

```text
books.id
    ↓
orders.book_id
```

Deleting a book cascades to related orders according to the migration.

### Order → Payment

```text
orders.id
    ↓
payments.order_id
```

### Order → Refund

```text
orders.id
    ↓
refunds.order_id
```

Refunds intentionally restrict deletion of referenced orders.

### User → Review → Book

```text
users.id
    ↓
reviews.user_id

books.id
    ↓
reviews.book_id
```

One user can have one review per book.

### Message → Replies

```text
messages.id
    ↓
message_replies.message_id
```

A message can have multiple replies.

---

# 🧱 Project Architecture

The application follows Laravel's MVC architecture.

```text
SecondBook/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── Frontend/
│   │   │   └── Seller/
│   │   │
│   │   └── Middleware/
│   │
│   ├── Models/
│   └── Services/
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
│
├── public/
│   ├── admin/
│   ├── frontend/
│   └── seller/
│
├── resources/
│   └── views/
│       ├── admin/
│       ├── Frontend/
│       ├── Layout/
│       └── Seller/
│
├── routes/
│   ├── web.php
│   └── ...
│
├── storage/
│
└── README.md
```

---

# 🛠️ Technologies

## Backend

* PHP
* Laravel
* Laravel Eloquent ORM
* Laravel Blade
* MySQL

## Frontend

* HTML5
* CSS3
* JavaScript
* Bootstrap
* Bootstrap Icons
* AJAX
* SweetAlert2

## Development Environment

* Windows
* Laragon
* MySQL
* Git
* GitHub

---

# 🚀 Installation

## 1. Clone the Repository

```bash
git clone https://github.com/ElmirVelizadeDev/SecondBook.git
```

## 2. Enter the Project

```bash
cd SecondBook
```

## 3. Install PHP Dependencies

```bash
composer install
```

## 4. Install Frontend Dependencies

```bash
npm install
```

## 5. Create Environment File

```bash
cp .env.example .env
```

On Windows, you can also create `.env` manually from `.env.example`.

## 6. Generate Application Key

```bash
php artisan key:generate
```

## 7. Configure Database

Update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=secondbook
DB_USERNAME=root
DB_PASSWORD=
```

Adjust the credentials according to your local MySQL configuration.

## 8. Run Migrations

```bash
php artisan migrate
```

## 9. Seed the Database

If seeders are available for the current environment:

```bash
php artisan db:seed
```

Or:

```bash
php artisan migrate --seed
```

## 10. Start the Development Server

```bash
php artisan serve
```

The application can then be accessed through the configured local URL.

---

# 🧹 Useful Laravel Commands

### Clear Application Cache

```bash
php artisan optimize:clear
```

### List Routes

```bash
php artisan route:list
```

### Run Migrations

```bash
php artisan migrate
```

### Roll Back Last Migration Batch

```bash
php artisan migrate:rollback
```

### Create a Migration

```bash
php artisan make:migration create_example_table
```

### Create a Model

```bash
php artisan make:model Example
```

### Create a Controller

```bash
php artisan make:controller ExampleController
```

---

# 🔄 Development Workflow

Typical development workflow:

```text
Feature
   ↓
Migration
   ↓
Model
   ↓
Controller / Service
   ↓
Route
   ↓
Blade View
   ↓
CSS / JavaScript
   ↓
Testing
   ↓
Git Commit
   ↓
GitHub
```

---

# 🔒 Security Considerations

The project includes several security-oriented mechanisms:

* Authentication
* Email verification
* OTP verification
* Password reset
* Role-based authorization
* Permission-based authorization
* Middleware protection
* User account status control
* Seller ownership checks
* Foreign key constraints
* Unique database constraints
* Activity logging
* Input validation
* CSRF protection through Laravel
* Password hashing through Laravel

---

# 📊 Database Constraints

The database makes extensive use of:

* Foreign keys
* Unique indexes
* Composite unique constraints
* Regular indexes
* Nullable foreign keys
* Enum values
* Default values
* Cascade deletes
* Restrict deletes
* Null-on-delete behavior

These constraints help maintain database integrity at the persistence layer.

---

# 🧪 Project Status

SecondBook is an actively developed Laravel marketplace project.

Current platform areas include:

* Customer marketplace
* Authentication
* Books
* Categories
* Authors
* Publishers
* Wishlist
* Orders
* Payments
* Shipping
* Coupons
* Refunds
* Reviews
* Seller applications
* Seller stores
* Seller Panel
* Messaging
* Notifications
* Admin Panel
* Roles & permissions
* Settings
* FAQ
* Blog
* Promotional banners
* Activity logs
* Analytics
* Archive support

The project continues to receive improvements to functionality, security, UI consistency, and marketplace workflows.

---

# 🔮 Future Improvements

Potential future improvements include:

* Advanced search
* Advanced filtering
* More detailed seller analytics
* Advanced reporting
* Recommendation system
* Improved marketplace discovery
* Additional payment providers
* Additional shipping integrations
* Automated email notifications
* Improved seller performance metrics
* API expansion
* Mobile application
* Automated testing coverage
* Performance optimization
* Production deployment improvements

---

# 👨‍💻 Developer

**Elmir Velizade**

Full Stack Developer in progress, focused on:

* PHP
* Laravel
* JavaScript
* Vue.js
* REST APIs
* MySQL
* Backend Development
* Full Stack Web Development

---

# 📄 License

This project is currently developed as a personal software project.

License and distribution terms may be defined separately as the project moves toward public or commercial release.

---

# ⭐ SecondBook

SecondBook aims to provide a modern and scalable marketplace architecture for buying and selling books while maintaining clear separation between:

```text
Customer
   │
   ├── Marketplace
   ├── Cart
   ├── Checkout
   ├── Orders
   ├── Reviews
   ├── Wishlist
   └── Messaging
       
Seller
   │
   ├── Store
   ├── Books
   ├── Orders
   ├── Sales
   ├── Reviews
   ├── Analytics
   ├── Messages
   ├── Notifications
   └── Settings

Admin
   │
   ├── Users
   ├── Sellers
   ├── Books
   ├── Orders
   ├── Payments
   ├── Refunds
   ├── Coupons
   ├── Shipping
   ├── Roles
   ├── Permissions
   ├── Content
   ├── Reports
   ├── Analytics
   ├── Settings
   └── Activity Logs
```

**SecondBook — A Laravel-powered marketplace for books.**
