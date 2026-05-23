# ZiiX Admin

> Administrative interface builder for Laravel. Build CRUD backends with just a few lines of code.

[![License](https://img.shields.io/github/license/ziixdk/admin.svg?style=flat-square&color=brightgreen)](LICENSE)

---

## Requirements

- PHP >= 8.3
- Laravel >= 11.0
- Fileinfo PHP Extension

---

## Installation

Install via Composer:

```bash
composer require ziixdk/admin
```

Publish assets and config:

```bash
php artisan vendor:publish --provider="ZiiX\Admin\AdminServiceProvider"
```

Configuration is in `config/admin.php` — change install directory, DB connection or table names here.

Run the installer:

```bash
php artisan admin:install
```

Open `http://localhost/admin/` in your browser. Default credentials: `admin` / `admin`.

---

## Updating

After upgrading the package, republish assets:

```bash
php artisan vendor:publish --tag=ziix-admin-assets --force
```

---

## Configuration

All options are in `config/admin.php`.

---

## Artisan Commands

```bash
php artisan admin:install           # First-time setup
php artisan admin:make ModelName    # Generate admin resource controller
php artisan admin:create-user       # Create an admin user
php artisan admin:reset-password    # Reset a user's password
php artisan admin:publish           # Re-publish assets and views
```

---

## Quick Example

```php
// Grid (data table)
$grid = new Grid(User::class);
$grid->column('name')->sortable();
$grid->column('email');
$grid->filter(fn($f) => $f->like('name'));

// Form (create / edit)
$form = new Form(new User);
$form->text('name')->required();
$form->email('email')->required();
$form->select('role_id')->options(Role::pluck('name', 'id'));

// Show (detail page)
$show = new Show(User::find($id));
$show->field('name');
$show->field('email');
```

---

## Extensions

| Extension | Description |
| --------- | ----------- |
| [helpers](https://github.com/open-admin-org/helpers) | Development helper tools |
| [media-manager](https://github.com/open-admin-org/media-manager) | Web interface for local file management |
| [config](https://github.com/open-admin-org/config) | Config manager |
| [grid-sortable](https://github.com/open-admin-org/grid-sortable) | Sortable grids |
| [api-tester](https://github.com/open-admin-org/api-tester) | Test API calls from admin |
| [scheduling](https://github.com/open-admin-org/scheduling) | View and test cron jobs |
| [log-viewer](https://github.com/open-admin-org/log-viewer) | Log viewer for Laravel |
| [reporter](https://github.com/open-admin-org/reporter) | Exception viewer |
| [redis-manager](https://github.com/open-admin-org/redis-manager) | Redis manager |

---

## Built with

- [Laravel](https://laravel.com/)
- [Bootstrap 5](https://getbootstrap.com/)
- [Alpine.js](https://alpinejs.dev/) — Tailwind branch
- [Tailwind CSS v4](https://tailwindcss.com/) — Tailwind branch
- [Tom Select](https://tom-select.js.org/)
- [Flatpickr](https://flatpickr.js.org/)
- [SortableJS](https://sortablejs.github.io/Sortable/)
- [Leaflet](https://leafletjs.com/)
- [NProgress](https://ricostacruz.com/nprogress/)
- [Coloris](https://github.com/mdbassit/Coloris/)
- [Font Awesome](https://fontawesome.com/)
- [Axios](https://github.com/axios/axios)

---

## Credits

Forked from [open-admin](https://github.com/open-admin-org/open-admin) — thanks to Sjors Broersen.
Originally forked from [laravel-admin](https://github.com/z-song/laravel-admin) — thanks to Z-song.

---

## License

ZiiX Admin is open-sourced software licensed under the [MIT License](LICENSE).
