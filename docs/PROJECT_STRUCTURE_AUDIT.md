# SecondBook struktur auditi (2026-10-18)

## Əhatə və Git bazası

- Repo: `C:\laragon\www\SecondBook`; branch `main`; remote `origin` GitHub.
- Auditin başlanğıcındakı son commit `d3f59e9` idi. İstifadəçinin qeyd etdiyi `aab1eeeca23550e605e6f89d5c091d4b7d954e83` bu checkout-un `HEAD`-i deyil.
- Başlanğıcda mövcud, qorunan dəyişikliklər: `resources/js/pages/FaqPage.vue`, `resources/js/pages/PrivacyPolicyPage.vue` (hər ikisi yalnız `v-for` parametrindən istifadə olunmayan index parametrini çıxarır).
- Başlanğıcda 665 izlənən fayl var idi. `.env` mövcuddur və ignore olunur; məzmunu oxunmayıb və hesabatda yoxdur.

## Struktur qərarı

Layihə Laravel 10-un tanış kök qovluqlarından istifadə edir: `app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `storage/`, `tests/`. Controller-lər Admin/API/Auth/Frontend/Seller məsuliyyətinə, modellər `app/Models`, services `app/Services`, Blade ekranları Admin/Auth/Frontend/Seller sahələrinə, layout-lar Layout altında ayrılıb. Bu bölgünü köçürməklə dəyişdirməyə əsas tapılmadı; boş Vue `components/`, `composables/`, `layouts/` qovluqları isə `.gitkeep` ilə gələcək üçün saxlanıb.

Vue `app.js` → `mountVuePages.js` entrypoint-i `import.meta.glob('./pages/*.vue')` ilə səhifələri yükləyir və Blade-dəki `data-vue-page` atributuna əsasən mount edir. Vue `pages/` glob-u real view-lərdə mount edilir; Vue servisləri istifadə olunan səhifələr tərəfindən import olunur.

`Frontend/account/settings.blade.php` öz yerində saxlanıldı: `AccountSettingsController@index` onu `Frontend.account.settings` adı ilə render edir; settings route-u həmin controller-ə bağlıdır; view `account-settings-page` Vue root-u və account settings asset-lərini daşıyır. Profil və account order səhifələri ayrıca `Auth/` və `Frontend/` altında olsa da, onların hər biri fərqli ekran və route məsuliyyətidir; bu fərq settings view-in mövcud yerini etibarsız etmir.

## İcra edilən dəyişiklik

Linux kimi böyük/kiçik hərfə həssas fayl sistemlərində view tapılmaması riski yaradan istinadlar düzəldildi. Fiziki qovluqlar `Admin/`, `Auth/`, `Seller/`, `Layout/` böyük hərflə olduğu halda bir sıra PHP `view()` çağırışları, Blade `@extends`/`@include` və admin view composer kiçik hərflə yazılmışdı. İstinadlar fiziki qovluq adlarına hizalandı. `route('admin.…')` route adlarına toxunulmadı. Dəyişən 117 faylın dəyişiklikləri adətən yalnız view namespace ilk hərfinin registridir; heç bir fayl köçürülməyib və silinməyib. Risk: aşağı.

Əsas nümunələr:

- `view('admin.books.index')` → `view('Admin.books.index')` (Admin controller-ləri)
- `view('auth.login')` → `view('Auth.login')` (Auth controller-ləri və route closure-ları)
- `view('seller.books.index')` → `view('Seller.books.index')` (Seller controller-ləri)
- `@extends('layout.admin.master')` → `@extends('Layout.Admin.master')`; eyni qayda `@include` və view composer-ə tətbiq olundu.

## Tam inventory

`project_structure.txt`-in əvvəlki snapshot-u `.git/` daxili fayllarını göstərirdi və real source inventory kimi yanıltıcı idi. Hazırkı işçi ağacında bu tracked fayl silinmiş görünür; əvvəlki snapshot-u bu audit mərhələsində bərpa edilməyib. Bu hesabat yüksək səviyyəli arxitektura və inventory xülasəsi verir, lakin əvvəlki faylın tam inventory məzmununun əvəzi deyil. `.git/`, `.env`, `vendor/`, `node_modules/` və ignore olunan build/cache faylları source inventory-yə daxil edilməyib.

Başlanğıc inventory üzrə əsas qovluq sayları: `app/` 127, `bootstrap/` 2, `config/` 15, `database/` 72, `docs/` 17, `public/` 188, `resources/` 208, `routes/` 5, `storage/` 9, `tests/` 9; root konfiqurasiya və sənədlər qalan izlənən fayllardır. `routes/admin.php` hazırda boşdur; admin route-ları `routes/web.php`-dədir. Migration/seeder-lər istifadə edilməsələr belə tarixçə və test data mənbəyidir, dəyişdirilməyib.

İşçi checkout-da generasiya/asılılıq qovluqlarının ölçüləri yoxlanıldı: `vendor/` 9,333 fayl / 56.87 MB; `node_modules/` 1,228 / 52.91 MB; `storage/` 128 / 11.21 MB; `bootstrap/cache/` 3 fayl. Bunlar Git inventory-sinə qarışdırılmayıb. Storage məzmunu istifadəçi məlumatı ola biləcəyindən dəyişdirilməyib.

## İstinad və təkrar yoxlamaları

- Blade `asset()` yollarından 177 istinad yoxlanıldı; `public/` altında tapılmayan yol aşkarlanmadı.
- Vue səhifələri eager glob ilə yüklənir; cleanup-da yalnız ayrıca test route-u ilə mount olunan `CatalogPreview` çıxarıldı.
- SHA-256 eyni məzmunlu public şəkil cütləri: `admin/images/logo2.png` / `logo2.png`; `frontend-assets/images/client-image1.png` / `item1.png`; `client-image2.png` / `item.png`; `client-image3.png` / `item5.png`; `client-image4.png` / `item4.png`; `client-image5.png` / `item3.png`. Müxtəlif məqsədli adlarla istinad oluna bildiyindən birləşdirilməyib.
- `docs/screenshots/` daxilində 16 adlandırılmış PNG faylı 0 byte-dır və bir-biri ilə eyni boş məzmundadır. `secondbook.test_frontend_books_44.png` isə 2,758,962 byte-dır. Boş PNG-lər silinməyib; screenshot artefaktı kimi yararsız namizəddir.
- `resources/css/app.css` 0 byte-dir və Vite entry point olaraq qalır; production build-də boş CSS asset yaradır. `vite.config.js`-də entry point kimi nəzərdə tutulduğundan avtomatik olaraq çıxarılmayıb.
- Frontend Vue service istifadə istinadları yoxlanıldı; account/auth/book/cart/catalog/content servisləri səhifələrdən istifadə olunur. `api.js` onların ortaq request qatıdır.

## Həll olunmamış tapıntılar və risklər

1. Admin order yaratma səhifəsi (`resources/views/Admin/orders/create.blade.php`) “Create Coupon” mətnini və coupons form action-ını göstərən kupon səhifəsinin surətidir. `OrdersController@create` `$users`/`$books` göndərir və `@store` order sahələri gözləyir, lakin view onları istifadə etmir. Bu açıq funksional səhvdir; düzgün order formu və validasiya axını ayrıca hazırlanmalıdır. Yüksək risk; bu struktur auditi çərçivəsində səhifə kor-koranə dəyişdirilməyib.
2. `npm audit` advisory endpoint-ə şəbəkə/DNS məhdudiyyəti səbəbindən çata bilmədi; dependency təhlükəsizliyi qiymətləndirilməyib.
3. `artisan route:list` və PHPUnit testləri `AppServiceProvider`/mail configuration boot zamanı MySQL-ə qoşulduğu üçün sandbox-da `secondbook` DB bağlantı icazəsi xətası ilə dayandı. DB-yə və real məlumatlara müdaxilə edilməyib. Test database config `phpunit.xml`-də ayrıca `secondbook_api_test_20261016` göstərsə də app boot xətası yaranır.
4. Authenticated storefront/admin/seller browser yoxlaması aparılmayıb; DB və autentifikasiya hesabları bu mühitdə əlçatan deyil.
5. Başqa screenshot boş faylları silinməyib. `public/` şəkillərinin eyni hash-lı olması silinməyə əsas deyil.
6. `Admin/` qovluğundakı `orders/create` template səhvi istisna olmaqla təkrarlanan Laravel view/controller/service-ləri sübutla müəyyən edilmədi; birləşdirmə və abstraksiya əlavə olunmayıb.

## Yoxlama nəticəsi

- `npm run build`: uğurlu; Vite 101 module build etdi (cleanup sonrası).
- `php artisan route:list --except-vendor`: MySQL connection denied; route siyahısı alına bilmədi.
- `php artisan test --no-parallel`: eyni MySQL connection denied xətası; testlər başlamadı.
- `npm audit --omit=dev`: npm registry DNS sorğusu uğursuz.
- `git diff --check`: uğurlu. Son status: 118 izlənən dəyişiklik və yeni `docs/PROJECT_STRUCTURE_AUDIT.md`; bunlara başlanğıcdan dəyişmiş iki Vue səhifəsi də daxildir.

## Struktur planı / yol qərarları

- Backend: mövcud Laravel standart bölgüsünü saxla; view reference casing düzəlişi aşağı riskli və tətbiq olunub.
- Blade: Admin/Auth/Frontend/Seller ekran qruplarını və Layout/Components/emails/errors qruplarını saxla. Account settings account alt qovluğunda qalır.
- Vue: pages, services, components, composables, layouts ayrımını saxla; hazırda boş UI qovluqları `.gitkeep` ilə saxlanır.
- Assets: Admin/Seller/Frontend CSS və public vendor asset-lərini mövcud faktiki Blade path-ləri ilə saxla.
- Database, routes bölünməsi, dependency files, migrations və local storage üçün köçürmə/silmə edilmədi.
- Hərəkətlər: 0 fayl köçürüldü, 0 fayl silindi, 0 təkrar asset birləşdirildi, 115 faylda view namespace casing istinadları düzəldildi, `project_structure.txt`-ə canonical inventory əlavə edildi, audit hesabatı yaradıldı.

## Follow-up audit: `resources/views/vue` cleanup (2026-10-18)

The folder contained only `test.blade.php` and `catalog.blade.php`; there was no `VueTest.vue`. `/vue-test` rendered only the `catalog-preview` mount used by `CatalogPreview.vue` and asserted by one demo-specific test. `/catalog` rendered a standalone legacy Blade catalog with `$books` references but supplied no data. It had no inbound links or consumers; the real catalog is `/frontend/books`, backed by `Frontend\BooksController` and `BooksPage.vue`.

Removed the two routes, both Blade views, `CatalogPreview.vue`, its explicit mount registry entry, and the one demo-only test assertion. Kept the same feature test coverage for frontend home/books/cart mount points. Retained Vue/Vite core, all real pages and API services, including `BooksPage.vue`, `CategoriesPage.vue`, `CartPage.vue`, and `CheckoutPage.vue`. No package or Vite dependency/config changes were needed.

Validation after cleanup: `npm run build` passed (101 modules); PHP syntax checks passed for `routes/web.php`, `tests/Feature/ExampleTest.php`, and `app/Providers/AppServiceProvider.php`; old demo references were removed from runtime source; `git diff --check` passed. `php artisan test --no-parallel` still fails before tests run because app boot cannot connect to the configured MySQL server (`secondbook`). No database was changed. The existing working-tree changes `D project_structure.txt`, `D resources/views/Frontend/account/settings.blade.php`, and untracked `resources/views/Frontend/settings.blade.php` were left untouched.
