# SecondBook Project Documentation

Updated: 2026-10-18

This document describes the repository as inspected on the date above. Runtime claims are limited to the checks listed in [Testing and Build](#14-testing-and-build); source inspection is not a substitute for authenticated browser testing.

## 1. Project Overview

SecondBook is a Laravel-based marketplace for books. Customers can browse books and stores, manage a cart and wishlist, place and track orders, and review eligible purchases. Sellers manage their catalogue, orders and store through a protected panel. Administrators and permission-bearing staff manage marketplace content, users, sellers, orders, payments and site configuration.

The repository contains three main user-facing areas: the customer Frontend, the Admin panel and the Seller panel. Laravel handles routing, authentication, authorization and business operations. Blade provides page shells and initial data; Vue 3 enhances many page bodies.

## 2. Technology Stack

- **Backend:** PHP `^8.1`, Laravel `^10.10`, Eloquent ORM, Blade.
- **Database:** MySQL is the configured default connection in `config/database.php`; connection settings are provided through environment configuration.
- **Frontend:** Vue `^3.5`, JavaScript ES modules, Vite `^8.3.4`, `@vitejs/plugin-vue` and Laravel Vite integration.
- **HTTP:** Axios `^1.6.4`; the shared client uses `/api`, credentials and XSRF support.
- **UI and assets:** Bootstrap 5.3.7 and Bootstrap Icons 1.13.1 are loaded from jsDelivr by layouts. Seller and Admin layouts also load SweetAlert2 from jsDelivr. Seller Analytics loads Chart.js on demand. Google Fonts supplies Plus Jakarta Sans in panel layouts.
- **Tests and tooling:** PHPUnit `^10.1`, Laravel Pint and Faker are declared in Composer development requirements.

The project does not declare a frontend CSS framework dependency in `package.json`; panel styles are primarily standalone CSS under `public/`.

## 3. Project Structure

- `app/Http/Controllers/` — controllers grouped by `Admin`, `Api`, `Auth`, `Frontend` and `Seller`.
- `app/Http/Middleware/` — authentication, Admin, Seller, permission, session and seller-application middleware.
- `app/Models/` — Eloquent entities and relationships.
- `app/Services/` — shared application services such as notification delivery, activity logging and mail configuration.
- `app/Providers/` — application, authentication, event and route providers.
- `bootstrap/`, `config/` — Laravel bootstrap and configuration.
- `database/migrations/`, `database/seeders/`, `database/factories/` — schema history and sample/test data.
- `resources/views/Admin/`, `Auth/`, `Frontend/`, `Seller/` — Blade page roots and server-rendered content.
- `resources/views/Layout/` — shared Admin, Frontend and Seller layouts and partials.
- `resources/views/Components/` — Blade components; some Admin components are intentionally absent in the current checkout and must not be restored without an explicit request.
- `resources/js/` — Vite entry points, Vue pages, shared Axios services, and Seller page-data utilities.
- `resources/css/app.css` — configured Vite CSS input; it is currently an empty file. Most current styles are in `public/`.
- `public/admin/`, `public/frontend-assets/`, `public/seller/` — panel-specific CSS, JavaScript, images and other public assets.
- `public/build/` — Vite production output; generated and not a source directory.
- `routes/` — `web.php`, `api.php`, `console.php` and `channels.php`. `routes/admin.php` exists but is empty; `RouteServiceProvider` loads `web.php` and `api.php`.
- `tests/Feature/`, `tests/Unit/` — PHPUnit tests.
- `docs/` — project documentation and screenshots.

## 4. Backend Architecture

Controllers are grouped by request domain: Admin CRUD and operations, JSON API endpoints, authentication, customer-facing page workflows, and Seller operations. Eloquent models are centralized in `app/Models/`; commonly used relationships include users to books/orders/stores/reviews, books to authors/categories/publishers/orders/reviews, and orders to users/books/payments/refunds/shipping.

Current application services include `MailConfigurationService`, `NotificationService` and `ActivityLogService`. Middleware is registered in `app/Http/Kernel.php`; aliases include `auth`, `admin`, `permission`, `seller` and `restrict.seller.application`. Providers include route configuration and an Admin layout view composer in `AppServiceProvider`.

There is no `app/Http/Requests/` directory or custom `FormRequest` class in the inspected tree. Validation is generally performed in controllers with `$request->validate()` or dedicated controller validation helpers. Do not document FormRequest classes as existing unless the structure changes.

## 5. Frontend Architecture

`vite.config.js` declares `resources/css/app.css` and `resources/js/app.js` as Vite inputs and enables the Vue plugin. `resources/js/app.js` loads `bootstrap.js` and calls `mountVuePages()` from `mountVuePages.js`.

`mountVuePages.js` discovers Vue files with a lazy `import.meta.glob('./pages/*.vue')`. A component filename maps to a kebab-case `data-vue-page` value. The loader converts remaining `data-*` attributes to Vue props, avoids repeat/in-flight mounts and gives Seller roots loading/error feedback. Blade roots can pass JSON-encoded, base64 `data-page-data`; `sellerPageData.js` decodes that payload for Seller components.

Vue components are in `resources/js/pages/`; shared HTTP/API services are in `resources/js/services/`. `services/api.js` is the shared Axios instance. Most customer JSON calls use its `/api` base. Seller pages commonly receive controller-prepared initial data; the Reviews and message-reply services explicitly target same-origin Seller web routes where needed.

Blade layouts remain responsible for the document shell, navigation, CSRF metadata and global CSS/vendor assets. Vue is mounted into page-specific roots rather than using a separate client-side router.

## 6. Admin Panel

Admin routes are defined in `routes/web.php` under the `admin` prefix and the `admin` plus `permission` middleware. `routes/admin.php` is empty and is not loaded by the current `RouteServiceProvider`.

The Admin area includes dashboard and analytics, catalogue management (books, categories, authors, publishers and book conditions), orders, payments, refunds, shipping, coupons, users and sellers, seller applications, reviews, messages, notifications, banners, blogs, FAQs, reports, activity logs, settings, email settings, roles/permissions and backups. Controllers are under `app/Http/Controllers/Admin/`; views are organized under matching `resources/views/Admin/` directories and use `Layout.Admin.master`.

`AdminMiddleware` requires an authenticated active account and accepts configured RBAC staff roles, `super-admin`, or supported legacy Admin role values. If the `two_factor_authentication_enabled` setting is active, the Admin 2FA session flag is required. The `permission` middleware derives a permission from the route name/action and checks the user's assigned permissions; super-admin is treated as having all permissions by `User::hasPermission()`.

**Confirmed unresolved issue:** `OrdersController::create()` supplies `$users` and `$books` to `Admin.orders.create`, and `OrdersController::store()` expects order fields. The current `resources/views/Admin/orders/create.blade.php` is a coupon creation form: it says “Create Coupon” and posts to `admin.coupons.store`. Admin order creation is therefore inconsistent and should be fixed in a separate Admin task.

## 7. Seller Panel

Seller routes are grouped under `/seller` with `auth` and `seller` middleware. Views extend `Layout.Seller.master`, include the shared Seller navigation/CSS and declare a `data-vue-page` root. Page-specific CSS is under `public/seller/css/pages/`.

| Page | Blade view | Vue component |
|---|---|---|
| Dashboard | `Seller/dashboard.blade.php` | `SellerDashboardPage.vue` |
| Books list | `Seller/books/index.blade.php` | `SellerBooksIndexPage.vue` |
| Book create/edit | `Seller/books/create.blade.php`, `Seller/books/edit.blade.php` | `SellerBookFormPage.vue` (shared) |
| Book details | `Seller/books/show.blade.php` | `SellerBookDetailsPage.vue` |
| Orders list/details | `Seller/orders/index.blade.php`, `Seller/orders/show.blade.php` | `SellerOrdersIndexPage.vue`, `SellerOrderDetailsPage.vue` |
| Sales | `Seller/sales/index.blade.php` | `SellerSalesPage.vue` |
| Reviews list/details | `Seller/reviews/index.blade.php`, `Seller/reviews/show.blade.php` | `SellerReviewsIndexPage.vue`, `SellerReviewDetailsPage.vue` |
| Analytics | `Seller/analytics/index.blade.php` | `SellerAnalyticsPage.vue` |
| Messages list/details | `Seller/messages/index.blade.php`, `Seller/messages/show.blade.php` | `SellerMessagesIndexPage.vue`, `SellerMessageDetailsPage.vue` |
| Notifications | `Seller/notifications/index.blade.php` | `SellerNotificationsPage.vue` |
| Store | `Seller/store.blade.php` | `SellerStorePage.vue` |
| Settings | `Seller/settings/index.blade.php` | `SellerSettingsPage.vue` |

Seller controllers remain the source of truth for initial page data, validation and mutations. The Books, Orders, Sales, Analytics, Dashboard, Messages, Reviews and Store queries are seller-scoped in their respective controllers; notifications are obtained through the authenticated user's relationship. Reviews list/detail JSON endpoints are also inside the Seller middleware group and check the seller's book ownership. Existing Laravel routes, session auth and CSRF-protected web workflows remain in use. No separate Seller SPA router is present.

## 8. Customer Frontend

Customer pages cover the home page, books and book details, categories, authors, stores, search/filter/pagination, cart, checkout, payment, wishlist, customer orders/details/tracking, eligible reviews, notifications, profile/account preferences and seller applications. Informational pages include About, Contact, FAQ, Help Center, Terms, Privacy, Cookies, Returns and Shipping Information.

Most page bodies have Vue roots mapped to components such as `HomePage.vue`, `BooksPage.vue`, `BookDetailsPage.vue`, `CategoriesPage.vue`, `CartPage.vue`, `CheckoutPage.vue`, `OrdersPage.vue`, `OrderDetailsPage.vue`, `WishlistPage.vue`, `ProfilePage.vue` and policy/content page components. Laravel controllers and APIs continue to enforce account and transaction rules. Auth and profile Blade pages are under `resources/views/Auth/`; storefront pages are under `resources/views/Frontend/`.

## 9. Authentication and Authorization

Authentication is session-based through Laravel's `web` middleware. The `User` model has a legacy `role` field (`user`, `seller`, `admin` checks) and also many-to-many `roles`/`permissions` relationships. Admin access combines active-account checks, accepted roles, optional two-factor verification and route permissions. Seller middleware requires authentication and `User::isSeller()`; Seller application approval in `SellerApplicationsController` assigns the seller role and creates the store.

Ownership checks belong on the server. Seller controllers scope books by `seller_id`, orders through their books, messages by `seller_id`, reviews through seller-owned books, and user notifications through the authenticated user's relationship. Customer order, wishlist, profile and review actions are likewise handled by authenticated controller/API flows. Vue visibility or client-side validation is not an authorization boundary.

## 10. Database Architecture

The schema is defined in timestamped Laravel migrations. Primary domain tables include `users`, `books`, `categories`, `authors`, `publishers`, `orders`, `payments`, `shippings`, `refunds`, `coupons`, `stores`, `seller_applications`, `reviews`, `messages`, `message_replies`, `notifications`, `wishlists`, `user_settings`, `settings`, `roles`, `permissions`, `role_user`, `permission_role`, `faqs`, `banners`, `blogs` and `activity_logs`. Laravel support tables include password reset tokens, failed jobs and personal access tokens; OTP and archive/read/discount/shipping fields are introduced by additional migrations.

Key relationships are represented by Eloquent models: `Book` belongs to seller/user, category, author and publisher; `Order` belongs to customer, book and shipping and has payment/refund records; `Review` belongs to customer and book; `Message` belongs to customer and seller and has replies; `Store` belongs to a seller and exposes that seller's books. The roles/permissions tables provide Admin RBAC in addition to the legacy user role field.

Migrations are the authoritative schema history. Seeders under `database/seeders/` cover users/roles, catalogue data, stores, seller books/orders, messages, notifications, reviews, settings and other sample records. Review individual seeder behavior before using `db:seed` against a non-disposable database.

## 11. Routes and Request Flow

`RouteServiceProvider` loads `routes/web.php` with `web` middleware and `routes/api.php` with the `api` middleware group and `/api` prefix. `routes/web.php` contains public content/store routes, auth pages, authenticated customer workflows, the Admin group and the Seller group. `routes/api.php` contains public catalogue/content reads and authenticated account, cart, checkout, wishlist, order, payment, notification and seller-application endpoints; selected session-dependent API endpoints are explicitly wrapped in `web` middleware.

For a Vue-backed page, Laravel resolves the route, applies middleware, runs the controller and renders a Blade layout/view. The view emits a mount root and any initial data/URLs; Vite loads `app.js`, whose mount registry lazy-loads the matching Vue page. Vue submits to existing Laravel routes or uses the shared Axios client for JSON. Server validation, authorization and response handling remain authoritative.

## 12. Design System and Assets

Admin CSS and images live under `public/admin/`; Seller shared styles are in `public/seller/css/core/` and page styles in `public/seller/css/pages/`; customer assets live under `public/frontend-assets/` in CSS, JavaScript, images and icon-font folders. Layouts load Bootstrap 5.3.7, Bootstrap Icons and Plus Jakarta Sans from external CDNs; panel layouts also load SweetAlert2. Seller Analytics fetches Chart.js dynamically.

Seller styles use shared CSS custom properties in `public/seller/css/core/style.css`; page CSS supplies cards, tables, forms and responsive breakpoints. Bootstrap grid classes and page-specific media queries provide responsive behavior. Preserve existing selectors, layouts and tokens when migrating or adding a page; compare desktop, tablet and mobile in a browser before changing visual rules.

`resources/css/app.css` is zero bytes while still configured as a Vite input, so the build can emit an empty CSS asset. Current page styling is supplied by the public CSS files linked from Blade layouts/views.

## 13. Validation, Security and Error Handling

The `web` middleware group starts the session and applies `VerifyCsrfToken`; Axios is configured for credentials/XSRF, and HTML forms use CSRF tokens and method spoofing where appropriate. API routes are throttled by the `api` limiter; sensitive auth/contact operations add route-specific throttle limits.

Validation currently lives primarily in controller methods/helpers. Book cover and store logo uploads use server-side image/MIME/size rules and the public storage disk. Keep server validation even when Vue performs previews or client-side checks. Seller ownership and Admin permission checks must remain in controllers/middleware. Vue pages should surface loading, empty and request-error states; Laravel validation/HTTP errors should be returned or redirected through the existing workflow rather than silently ignored.

## 14. Testing and Build

- `npm run dev` starts Vite for local development; `npm run build` creates production assets in `public/build/`.
- Automated tests are in `tests/Feature/` and `tests/Unit/`; run with `php artisan test`.
- `php -l <file>`, `php artisan route:list`, `php artisan view:cache` and `git diff --check` are used for targeted syntax/integration/whitespace checks.
- Last confirmed Seller migration verification on 2026-10-18: Vite production build passed; `php artisan test` passed **35 tests and 264 assertions**; PHP syntax checks, `php artisan route:list --path=seller`, `php artisan view:cache` and `git diff --check` passed. The path filter matches any route containing `seller`, including Admin seller-management routes.
- No authenticated Seller browser session was available during that verification. Seller rendering, Console/Network behavior, responsive visual parity and CRUD/upload flows were not runtime-verified; source inspection and feature tests do not replace those checks.

## 15. Installation and Local Development

1. Install PHP 8.1 or newer, Composer, Node.js/npm and a running MySQL server compatible with the project dependencies.
2. Install PHP dependencies with `composer install` and JavaScript dependencies with `npm install` (or `npm ci` when using the committed lockfile).
3. Create local environment configuration from `.env.example` without committing it. Set `APP_URL`, database connection settings (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) and required local mail settings. Never copy real credentials into documentation or Git.
4. Create the configured database, then run `php artisan key:generate`, `php artisan migrate` and, only where appropriate, `php artisan db:seed`.
5. Run `php artisan storage:link` if public uploaded images need the conventional `public/storage` link.
6. Start the app with `php artisan serve` and Vite with `npm run dev`; use `npm run build` to verify production assets.

The exact external services (mail provider, OAuth credentials and any payment gateway configuration) depend on local `.env` values and are not documented here. Do not use production credentials or production data for local tests.

## 16. Git Workflow

The inspected branch is `main`, tracking `origin/main`. Recent commits use a conventional scoped subject such as `feat(seller): complete Vue 3 panel migration`; follow the same `type(scope): summary` pattern and include a concise body for substantial work. Stage only files within the task scope, inspect both staged and unstaged diffs before committing, and run relevant checks first.

Preserve existing user changes. Do not use reset/restore/checkout/stash or force-push as a cleanup shortcut. Ask before rewriting history that has already been pushed. The current checkout contains five pre-existing deleted Admin Blade components under `resources/views/Components/Admin/`; leave them deleted and do not stage or restore them during unrelated work.

## 17. Known Issues and Limitations

- **Admin order creation:** confirmed mismatch between `OrdersController::create()`/`store()` and the coupon form currently rendered by `Admin/orders/create.blade.php`; order creation through this page is not ready.
- **Authenticated browser verification:** a Seller login session was unavailable in the recorded migration audit, so runtime visual, network and workflow claims remain unverified.
- **Empty Vite CSS input:** `resources/css/app.css` is empty but remains a configured entry point.
- **Legacy inventory snapshot:** `project_structure.txt` is absent from the current tree. Git history shows it was deleted in `c07b292`; the prior audit reports it included `.git/` entries and was not a reliable source inventory.
- **View namespace casing:** Git history for `c07b292` confirms case-normalization changes across 115 files spanning controllers, Blade views, provider and route references. Current paths use the physical directory casing (`Admin`, `Auth`, `Layout`, `Seller`); preserve that casing, especially on case-sensitive filesystems.
- **Vue demo cleanup:** `c07b292` removed the demo Vue preview component and `resources/views/vue` demo pages/routes/test reference. A current search of `routes`, `resources` and `tests` finds no `CatalogPreview`, `catalog-preview`, `/vue-test` or `resources/views/vue` references. The production catalogue remains `/frontend/books` and `BooksPage.vue`.

## 18. Maintenance Guidelines

- **New page:** add or update the Laravel route/controller and Blade view; keep the route middleware and named routes explicit. For a Vue body, use a unique `data-vue-page` matching the kebab-case Vue filename and pass only required initial data/URLs.
- **Vue page:** add the component under `resources/js/pages/`; the glob registry discovers it automatically. Keep page-local state/lifecycle cleanup in the component and provide loading, empty and error states for asynchronous requests.
- **API/service:** use the shared `resources/js/services/api.js` Axios instance for `/api` routes. For same-origin web routes outside `/api`, pass the correct base URL as existing Seller Review/Message services do. Preserve credentials, CSRF and server authorization.
- **Controller/backend:** keep request validation and ownership checks server-side. There are no custom FormRequest classes at present; use existing controller validation conventions unless adding a FormRequest as part of a deliberate architecture change.
- **Database:** add a new timestamped migration for schema changes; update models/factories/seeders/tests where applicable. Do not edit old migrations that may already have run in shared environments.
- **CSS/assets:** use the existing area-specific public asset tree and layout. Reuse established class names/tokens and responsive breakpoints; avoid global selectors that leak into other panels.
- **Tests/docs:** add feature coverage for authorization, data ownership and workflows; update this document only when verified architecture or behavior changes. Never include secrets or personal data.

## 19. Future Improvements

1. **High priority — fix Admin order creation** with a real order form that matches `OrdersController::store()` validation, then add feature tests for creation and invalid input.
2. **High priority — authenticated Seller browser pass** covering every Seller page, Console/Network errors, search/filter/pagination, uploads, CRUD, replies, notifications and responsive layouts.
3. **Medium priority — strengthen Seller tests** beyond Dashboard/Order Details coverage to exercise Seller ownership, Reviews endpoints, Store/Settings updates and mutation error responses.
4. **Low priority — remove or populate the empty CSS Vite entry** only after confirming the build and any external tooling no longer depend on it.
5. **Low priority — maintain a generated source inventory if needed**, excluding `.git`, secrets, dependency trees, caches and generated build output.
