# Seller Vue 3 migration status

Updated: 2026-10-18

## Final integration audit

**Status: code audit completed; browser runtime verification remains pending.** The migration implementation was reviewed across all Seller pages.

### Audit findings and fixes

- Confirmed the Seller Blade mount roots map to the existing Seller page components (including the shared Book form root for create/edit). The Seller route group in `routes/web.php` remains protected by `auth` and `seller`; the Seller layout still includes the existing `resources/js/app.js` Vite entry and shared navigation/CSS.
- Confirmed each Seller mount root maps to its intended component. The `seller-book-form-page` root is intentionally shared by the create and edit views, both handled by `SellerBookFormPage.vue`; no conflicting component registration was found.
- Found that the mount registry used `import.meta.glob(..., { eager: true })`, which put every page in the initial bundle. Changed the same registry to lazy dynamic imports and guarded in-flight mounts. No second mount mechanism or router was introduced.
- The audit found the Books and Orders delete modals submitted normal HTML forms to controllers that return JSON. That would expose the raw JSON response after deleting. Both now issue same-origin, CSRF-protected AJAX requests, surface server errors, show the existing SweetAlert toast when available, and refresh the list after success. Controller authorization, delete restrictions, and response contracts were not changed.
- Source inspection confirmed Seller ownership scoping for Books, Orders, Sales, Analytics, Dashboard, Messages, Reviews and Store operations; notification queries and mutations use the authenticated user's notification relationship. The Seller controllers and route group remain the server-side authority.
- No Seller CSS, Sales view/component, Admin files, storefront files, or API configuration were changed as part of this final audit.

### Verification results

- `npm run build`: passed. Seller page components are separate lazy chunks and Vite completed without a chunk-size warning.
- PHP lint: passed for Seller controller files, all Seller Blade views, `routes/web.php` and the Seller migration feature test. Five deleted Admin Blade files were excluded and left untouched.
- `git diff --check`: passed; Git prints CRLF/LF normalization warnings for existing files.
- `php artisan route:list --path=seller`: passed and listed 50 routes matching the `seller` path filter.
- `php artisan view:cache`: passed.
- `php artisan test`: passed (35 tests, 264 assertions).
- No authenticated Seller browser session was available for mount/runtime, Network/Console, responsive, visual parity, or cross-seller request testing. Code review confirms authorization checks but does not replace runtime authorization tests.

### Runtime follow-up still required

With an authenticated Seller session and reachable database, verify each Seller page's initial dynamic chunk request and Vue mount; exercise book/order delete success and server errors, search/filter/pagination, forms/uploads, messaging, notifications, chart loading, review ownership, and desktop/mobile appearance. Inspect browser Console and Network, and request another Seller's book/order/message/review identifiers to confirm server-side denial.

## Phase 1 — Review Details

**Status: code migrated; runtime verification pending.** Do not describe this page as runtime-tested until the checks below are completed with an authenticated Seller session.

### Confirmed by code inspection and available checks

- `resources/views/Seller/reviews/show.blade.php` contains the Vue root `data-vue-page="seller-review-details-page"` and passes the Laravel-generated data and return URLs.
- `resources/js/mountVuePages.js` discovers `SellerReviewDetailsPage.vue` through its existing eager page glob. Its filename maps to `seller-review-details-page`; `resources/js/app.js` invokes the existing mount function.
- `resources/views/Layout/Seller/master.blade.php` includes the existing `resources/js/app.js` Vite entry. The review detail view continues loading `public/seller/css/pages/reviews.css`.
- `resources/js/services/sellerReviewService.js` uses the shared Axios client and overrides its `/api` base URL for this same-origin web route. URL composition was checked to produce `/seller/reviews/1/data` on `secondbook.test`.
- `GET seller.reviews.data` is in the `auth` + `seller` route group. `ReviewController::showData` returns only fields consumed by the page and checks that the review's book belongs to `auth()->id()` before returning JSON.
- The Vue component retains the review detail page's CSS class structure, text, Laravel-formatted dates, empty-comment content, and back-to-reviews URL. It has loading and error branches.
- `npm run build` succeeded. PHP lint succeeded for `ReviewController.php` and `routes/web.php`.
- The five deleted files under `resources/views/Components/Admin/` were left untouched and not restored.

### Not confirmed at runtime

- The browser request to `http://secondbook.test/seller/reviews/1` redirected to `/frontend/auth/login`; no authenticated Seller session was available.
- Consequently, Vue mounting in the Seller layout, review JSON rendering, the no-comment state, data values, and visual parity against the original Blade page have not been verified in an authenticated browser session.
- The data endpoint also redirected an unauthenticated browser to login. This confirms the unauthenticated gate, but does not test cross-seller ownership or confirm the JSON response in an authorized session.
- Browser Console and authenticated Network activity were not inspected.
- `php artisan route:list --path=seller/reviews` and `php artisan test` could not boot in the shell sandbox because its MySQL socket connection was denied. Do not report those checks as passed.

### Required checks when a Seller session is available

1. Open `http://secondbook.test/seller/reviews/1` and verify the Vue component mounts without a blank page or mount error.
2. Confirm the data request goes to `/seller/reviews/1/data` (not `/api/seller/...`), returns the expected review for its owning seller, and has no failed Network requests.
3. Check Console for JavaScript/Vue errors; inspect loading, populated, and empty-comment states.
4. Compare the page against the original Blade appearance at desktop and responsive widths, including sidebar active state and the Back to Reviews link.
5. Using existing test data, request a review belonging to a different seller and verify the request returns 403 with no review fields in the response.
6. Run the suitable Seller authorization tests and route listing where the test/runtime database is available.

## Phase 2 — Reviews index

**Status: code migrated; runtime verification pending.** This page has not been confirmed in an authenticated Seller browser session.

### Changes made

- `resources/views/Seller/reviews/index.blade.php` now provides the Vue mount root and route URLs; it continues to extend `Layout.Seller.master` and load `seller/css/pages/reviews.css`.
- Added `resources/js/pages/SellerReviewsIndexPage.vue`, registered automatically through the existing `mountVuePages.js` page glob. Its filename maps to `seller-reviews-index-page`.
- Added `GET /seller/reviews/data` (`seller.reviews.list-data`) and `ReviewController::indexData`, within the existing `auth` + `seller` route group.
- Added `getReviews` to the existing `sellerReviewService.js`. It uses the shared Axios instance with a same-origin base override, keeping the request outside the default `/api` prefix.
- `ReviewController::index` and `indexData` now share the seller-scoped query and search/rating filter helpers.

### Behavior retained by code

- Four statistics cards; statistics remain scoped to the seller's reviews for the last six months and are not affected by list filters.
- Only reviews for books owned by the authenticated seller, with archived reviews excluded and the same six-month cutoff.
- Search across comment, book title, buyer name/first name/last name/email and the existing rating filter.
- Ten results per page. Search/filter submission remains a GET navigation, the current query string initializes Vue filter values, and Laravel pagination links point back to `/seller/reviews` while retaining query parameters.
- Reviews table fields, details links, empty and no-comment states, responsive CSS selectors and Seller layout/navigation.
- Loading and request error states are included in Vue.

### Available verification and runtime limits

- `npm run build` succeeded after the Reviews index changes.
- PHP lint succeeded for `ReviewController.php` and `routes/web.php`.
- Axios URL composition check produced `/seller/reviews/data?search=foo+bar&rating=5&page=2` on `secondbook.test`, not `/api/seller/...`.
- The browser request to `http://secondbook.test/seller/reviews` redirected to `/frontend/auth/login`; no Seller session was available. Vue rendering, actual JSON content, search/filter/pagination behavior, visual comparison, Console/Network inspection and cross-seller 403 behavior remain unverified at runtime.
- `php artisan route:list --path=seller/reviews` and `php artisan test` could not boot from the shell sandbox because MySQL socket access was denied. No Seller reviews-specific test was found by the source search. These checks are not reported as passed.

### Required runtime checks when a Seller session is available

1. Open `http://secondbook.test/seller/reviews`; confirm Vue mount and matching existing design at desktop and responsive widths.
2. Inspect Console and Network. Confirm the list request uses `/seller/reviews/data`, and search, rating, page links, refresh and back navigation preserve the expected query values/results.
3. Confirm statistics and review rows match the seller-scoped six-month data; verify the empty and no-comment states.
4. With existing test data, request `/seller/reviews/data` while authenticated as another seller and verify their data is absent from both list and statistics. Also verify the existing review detail endpoint still rejects a foreign review.
5. Run route listing and relevant authorization tests where CLI access to the test DB is available.

## Git protection notes

The working tree has unrelated existing changes, including five deleted Admin component files. Keep all current changes intact; do not restore, discard, commit, or push them as part of this migration work.

## Phase 3 — Store and Settings

**Status: Partially verified.** Both pages now mount Vue page components in the existing Laravel Seller layout. No authenticated Seller browser session was available for runtime or visual parity confirmation.

### Store

- Replaced the Store Blade content with the `seller-store-page` Vue mount root and safely encoded initial data from the existing seller-owned Store model.
- Added `resources/js/pages/SellerStorePage.vue`. It preserves the existing Seller Store CSS selectors and screen text, submits multipart PUT data to the existing `seller.store.update` route, and keeps CSRF, server validation, success feedback, current store metadata, and existing Laravel validation rules.
- Moved the live store name/description/logo preview, logo selection/drag-and-drop, close-alert behavior, and 2 MB/type client checks into Vue. Server-side validation remains authoritative.
- The Store controller and route behavior were not changed.

### Settings

- Replaced the Settings Blade content with the `seller-settings-page` Vue mount root and encoded current/old input, validation errors, CSRF token, success message and update URL.
- Added `resources/js/pages/SellerSettingsPage.vue`. Existing styling selectors, settings fields and form method spoofing are retained; submission continues through the existing `seller.settings.update` route and controller validation/business rules.
- The Settings controller and route behavior were not changed.

### Verification

- `npm run build` succeeded after both page integrations.
- `php -l` succeeded for `StoreController.php` and `StoreSettingsController.php`.
- `git diff --check` completed without whitespace errors; Git reports existing CRLF/LF normalization warnings.
- No authenticated browser/runtime, responsive comparison, Network/Console inspection or DB-backed feature tests were performed. Both pages remain partially verified until those checks can be run.

## Remaining Seller pages

The 10 pages from the current migration batch have code-level Vue integrations. Runtime confirmation is pending for every page because no authenticated Seller browser session is available. Review index and Review Details remain as documented above and have not been rewritten.

## Runtime limitations

The current shell could build assets and lint PHP, but Laravel CLI boot and DB-backed tests previously failed because MySQL socket access is denied in this environment. Authenticated Seller browser testing also requires an available Seller session. Do not report runtime behavior as verified until those constraints are removed and the listed checks are executed.

### Books — Details

**Status: Partially verified.** `resources/views/Seller/books/show.blade.php` now mounts `SellerBookDetailsPage.vue` using seller-owned Book data prepared by the existing controller. The Vue component preserves the existing detail-page CSS classes, status/condition display, cover fallback, information grid, description fallback, and index/edit links. `BookController::show` ownership check and route are unchanged. `npm run build` and `php -l app/Http/Controllers/Seller/BookController.php` succeeded. Runtime/browser parity remains unverified.

### Sales

**Status: Partially verified.** `resources/views/Seller/sales/index.blade.php` now mounts `SellerSalesPage.vue`. The controller supplies the existing seller-scoped, delivered, non-archived six-month result set, summary statistics, pagination links, and search value as page data; no endpoint or controller query was changed. The Vue page retains the Sales CSS classes and provides GET search/reset, table rows, responsive table wrapper, pagination, and empty state. `npm run build`, PHP lint for `SalesController.php`, and `git diff --check` succeeded at this stage. Runtime browser and responsive comparison remain unverified.

## Phase 4 — Remaining Seller pages

**Status: Partially verified (code integration done; runtime pending).** The following pages now mount dedicated Vue components through the existing `mountVuePages.js` eager glob. They reuse controller-provided data serialized into the mount root, so no second Axios client or duplicate API was added. Existing session auth, Seller middleware, CSRF tokens, controller validation and server-side ownership scopes remain in place.

| Page | Vue component | Existing Laravel behavior kept |
|---|---|---|
| Dashboard | `SellerDashboardPage.vue` | Seller-scoped dashboard statistics and recent orders from `SellerDashboardController`; existing links to Seller functions |
| Books index | `SellerBooksIndexPage.vue` | Seller-scoped search/status/condition filters, pagination, details/edit links, confirmation and CSRF DELETE |
| Books create | `SellerBookFormPage.vue` | Existing catalog choices, validation feedback, cover preview/type/size check, multipart POST |
| Books edit | `SellerBookFormPage.vue` | Existing book values, validation feedback, cover preview/type/size check, multipart PUT and existing ownership authorization |
| Orders index | `SellerOrdersIndexPage.vue` | Seller-scoped six-month query, search/status/payment filters, pagination, details and eligible-order deletion |
| Orders details | `SellerOrderDetailsPage.vue` | Existing `OrderController::show` ownership check, current status/progress, PATCH status form and server validation |
| Analytics | `SellerAnalyticsPage.vue` | Existing controller-computed seller analytics, range GET form, Chart.js revenue chart, order status/inventory/top books/recent orders |
| Messages index | `SellerMessagesIndexPage.vue` | Seller-scoped six-month query, search/read status tabs, pagination, conversation links |
| Messages details | `SellerMessageDetailsPage.vue` | Existing controller ownership check and mark-read behavior, reply validation/CSRF, seller-owned reply deletion endpoint |
| Notifications | `SellerNotificationsPage.vue` | Authenticated user notification query, read/unread filters, individual and bulk read/delete actions, pagination |

### Phase 4 verification

- `npm run build` succeeded after each page integration. The final build succeeds with a Vite chunk-size advisory because the app bundle exceeds 500 kB; no build error was reported.
- PHP lint succeeded for the changed Seller controllers: Book, Order, Dashboard, Analytics, Messages and Notification controllers. No controller or route logic was changed for these integrations.
- `git diff --check` succeeded without whitespace errors; Git emits CRLF/LF normalization warnings for some Blade files.
- No new Seller API endpoint was required. Pages consume the same data already prepared by their authorized web controllers; write actions continue to use existing web routes with CSRF and method spoofing.
- Runtime page rendering, data values, responsive appearance, browser Console/Network, upload workflows, CRUD authorization and status transition behavior remain unverified without an authenticated Seller session and available DB.
- `php artisan route:list --path=seller` and DB-backed tests remain blocked by denied MySQL socket access in this environment. They are not claimed as passed.

### Runtime checklist when a Seller session is available

For each of these ten pages, verify Vue mounts, page data matches the existing Seller-scoped controller result, desktop/mobile appearance matches the pre-migration page, and Console/Network have no errors. Exercise book create/edit/delete and cover upload, order filtering/status updates/deletion, analytics ranges/chart, message search/reply/delete-reply, and notification read/delete/bulk actions. Use another seller's book/order/message IDs to confirm server-side ownership checks still deny access.
