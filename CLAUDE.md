# ZiiX Admin — Project Guide

## What this project is

ZiiX Admin is a Laravel admin panel library (PHP package) — a fork of Open-Admin. It lets developers build CRUD backends for Laravel 11+ apps with minimal code via a fluent PHP API.

- **Namespace**: `ZiiX\Admin\`
- **PHP**: >= 8.3 | **Laravel**: >= 11
- **License**: MIT
- **Entry point**: `src/Admin.php` (main facade/service class)

---

## Repository layout

```
src/                  PHP source (autoloaded as ZiiX\Admin\)
  Admin.php           Main class — asset mgmt, menu, routing bootstrap
  Grid.php            Data table builder
  Form.php            Form builder
  Show.php            Detail page builder
  Tree.php            Hierarchical data builder
  Controllers/        Base + auth controllers (11 files)
  Form/Field/         68+ field types
  Grid/               Columns, filters, displayers, tools, actions
  Actions/            Grid / row / batch action system
  Auth/Database/      Eloquent models (Administrator, Role, Permission, Menu)
  Middleware/         7 middleware classes
  Console/            19 Artisan commands (install, make:admin, etc.)
  Traits/             Reusable traits (HasAssets, ModelTree, …)
  Layout/             Content / Row / Column layout helpers
  Widgets/            UI widget components
  helpers.php         Global helper functions (admin_url, admin_toastr, …)

resources/
  assets/             SCSS + JS source (Bootstrap 5, Flatpickr, Choices.js, …)
  views/              143 Blade templates
  lang/               22 language files

config/admin.php      All package configuration (routes, DB tables, auth, upload…)
database/migrations/  Single migration: admin tables
tests/                PHPUnit test suite (17+ test classes)
```

---

## Core building blocks

### Grid (data tables)
```php
$grid = new Grid(Model::class);
$grid->column('name')->sortable();
$grid->filter(fn($f) => $f->like('name'));
$grid->tools->append(new CustomTool());
```

### Form (create / edit)
```php
$form = new Form(new Model);
$form->text('name')->required();
$form->select('status')->options([…]);
$form->belongsTo('user_id', User::class, 'name');
```

### Show (detail page)
```php
$show = new Show(Model::find($id));
$show->field('name');
$show->relation('orders', fn($orders) => $orders->column('total'));
```

### AdminController base
Extend `ZiiX\Admin\Controllers\AdminController` and implement `grid()`, `form()`, `detail()`.

---

## Database tables (configurable in config/admin.php)

| Table | Model |
|-------|-------|
| `admin_users` | `Auth\Database\Administrator` |
| `admin_roles` | `Auth\Database\Role` |
| `admin_permissions` | `Auth\Database\Permission` |
| `admin_menu` | `Auth\Database\Menu` |
| `admin_operation_log` | `Auth\Database\OperationLog` |

---

## Key dependencies

| Package | Purpose |
|---------|---------|
| `laravel/framework >= 11` | Core framework |
| `intervention/image ^3.8` | Image upload & resize |
| `doctrine/dbal >= 4.0` | Schema introspection |
| `symfony/dom-crawler >= 7.0` | DOM parsing |

Frontend (built assets in `resources/assets/`):
Bootstrap 5, Choices.js, Flatpickr, SortableJS, Sweet Alert 2, Toastify, Leaflet, Font Awesome, Coloris.

---

## Development workflow

### Run tests
```bash
composer test
# or
./vendor/bin/phpunit
```

### Compile frontend assets
```bash
npm run sass          # watch + compile SCSS → CSS
```

### Useful Artisan commands (after package install)
```bash
php artisan admin:install           # first-time setup
php artisan admin:make ModelName    # generate admin resource
php artisan admin:create-user       # create admin user
php artisan admin:reset-password    # reset user password
php artisan admin:publish           # re-publish assets/views
```

---

## Coding conventions

- **PSR-4** — one class per file, filename matches class name.
- **No comments by default** — only add a comment when the *why* is non-obvious.
- **No backwards-compat shims** — if something is unused, delete it cleanly.
- Field classes live in `src/Form/Field/` and must extend `Form\Field`.
- Grid displayers live in `src/Grid/Displayers/` and must extend `Grid\Displayers\AbstractDisplayer`.
- Use `admin_trans()` for all user-facing strings; add keys to all 22 lang files when adding new text.

---

## Configuration reference (config/admin.php)

| Key | Default | Purpose |
|-----|---------|---------|
| `route.prefix` | `admin` | URL prefix |
| `route.middleware` | `['web', 'admin']` | Applied middleware |
| `auth.guard` | `admin` | Auth guard name |
| `upload.disk` | `admin` | Filesystem disk for uploads |
| `database.connection` | `''` (default) | DB connection override |
| `operation_log.enable` | `true` | Log all admin actions |
| `check_route_permission` | `true` | Enforce route-level permissions |

---

## Current branch: `ziix_namespacing`

Modified files at branch start:
- `src/Form.php`
- `src/Form/EmbeddedForm.php`

Recent version tags: v3.0.24 (latest), v3.0.23, v3.0.22.
