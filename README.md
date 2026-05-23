<p align="center">
  <a href="https://ziix.eu">
    <img src="https://ziix.eu/og-image-en.png" alt="ZiiX Admin" style="max-height:200px;">
  </a>
</p>

<p align="center">
  Administrative interface builder for Laravel — build CRUD backends with just a few lines of code.
</p>

<p align="center">
  <a href="https://github.com/ziixdk/admin">
    <img src="https://img.shields.io/badge/Laravel-11%2B-red?style=flat-square" alt="Laravel 11+">
  </a>
  <a href="https://github.com/ziixdk/admin">
    <img src="https://img.shields.io/badge/PHP-8.3%2B-blue?style=flat-square" alt="PHP 8.3+">
  </a>
  <a href="LICENSE">
    <img src="https://img.shields.io/github/license/ziixdk/admin.svg?style=flat-square&color=brightgreen" alt="MIT License">
  </a>
</p>

---

## Versions

ZiiX Admin is available in two editions:

| | v3 (stable) | v4 (Tailwind) |
|---|---|---|
| **CSS** | Bootstrap 5 | Tailwind CSS v4 |
| **JS** | Bootstrap JS + Choices.js | Alpine.js + Tom Select |
| **Build** | Sass | Vite |
| **Install** | `composer require ziixdk/admin:^3.0` | `composer require ziixdk/admin:^4.0` |
| **Branch** | `main` | `v4` |

---

## Requirements

- PHP >= 8.3
- Laravel >= 11.0
- Fileinfo PHP Extension

---

## Installation

```bash
# v3 — Bootstrap 5 (stable)
composer require ziixdk/admin:^3.0

# v4 — Tailwind CSS v4 + Alpine.js
composer require ziixdk/admin:^4.0
```

Publish assets and config:

```bash
php artisan vendor:publish --provider="ZiiX\Admin\AdminServiceProvider"
```

Run the installer:

```bash
php artisan admin:install
```

Open `http://localhost/admin/` — default login: `admin` / `admin`.

---

## Updating

```bash
php artisan vendor:publish --tag=ziix-admin-assets --force
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

## Artisan Commands

```bash
php artisan admin:install           # First-time setup
php artisan admin:make ModelName    # Generate admin resource controller
php artisan admin:create-user       # Create an admin user
php artisan admin:reset-password    # Reset a user's password
php artisan admin:publish           # Re-publish assets and views
```

---

## Configuration

All options are in `config/admin.php`.

---

## Extensions

| Extension | Install | Description |
|-----------|---------|-------------|
| [admin-ext-media-manager](https://packagist.org/packages/ziixdk/admin-ext-media-manager) | `composer require ziixdk/admin-ext-media-manager` | Web interface for local file management |
| [admin-ext-ckeditor](https://packagist.org/packages/ziixdk/admin-ext-ckeditor) | `composer require ziixdk/admin-ext-ckeditor` | CKEditor rich text editor for forms |

---

## Built with (v4)

| Package | Purpose |
|---------|---------|
| [Tailwind CSS v4](https://tailwindcss.com/) | Utility-first CSS framework |
| [Alpine.js](https://alpinejs.dev/) | Lightweight JS reactivity |
| [Vite](https://vitejs.dev/) | Asset bundler |
| [Tom Select](https://tom-select.js.org/) | Select / multiselect inputs |
| [Flatpickr](https://flatpickr.js.org/) | Date & time pickers |
| [SortableJS](https://sortablejs.github.io/Sortable/) | Drag & drop sorting |
| [Leaflet](https://leafletjs.com/) | Interactive maps |
| [NProgress](https://ricostacruz.com/nprogress/) | Page load progress bar |
| [Coloris](https://github.com/mdbassit/Coloris/) | Color picker |
| [Font Awesome](https://fontawesome.com/) | Icons |
| [Axios](https://github.com/axios/axios) | HTTP client |

## Built with (v3)

| Package | Purpose |
|---------|---------|
| [Bootstrap 5](https://getbootstrap.com/) | CSS framework & UI components |
| [Choices.js](https://github.com/Choices-js/Choices) | Enhanced select inputs |
| [SweetAlert2](https://sweetalert2.github.io/) | Confirm dialogs |
| [Toastify](https://github.com/apvarun/toastify-js) | Toast notifications |
| [Flatpickr](https://flatpickr.js.org/) | Date & time pickers |
| [SortableJS](https://sortablejs.github.io/Sortable/) | Drag & drop sorting |
| [Leaflet](https://leafletjs.com/) | Interactive maps |
| [NProgress](https://ricostacruz.com/nprogress/) | Page load progress bar |
| [Coloris](https://github.com/mdbassit/Coloris/) | Color picker |
| [Font Awesome](https://fontawesome.com/) | Icons |
| [Axios](https://github.com/axios/axios) | HTTP client |
| [Inputmask](https://github.com/RobinHerbots/Inputmask) | Input masking |
| [Dual Listbox](https://github.com/maykinmedia/dual-listbox/) | Dual listbox widget |

### PHP dependencies

| Package | Purpose |
|---------|---------|
| [laravel/framework](https://laravel.com/) >= 11 | Core framework |
| [intervention/image](https://image.intervention.io/) ^3.8 | Image upload & resize |
| [doctrine/dbal](https://www.doctrine-project.org/projects/dbal.html) >= 4.0 | Schema introspection |
| [symfony/dom-crawler](https://symfony.com/doc/current/components/dom_crawler.html) >= 7.0 | DOM parsing |

---

## Credits

Forked from [open-admin](https://github.com/open-admin-org/open-admin) — thanks to Sjors Broersen.
Originally forked from [laravel-admin](https://github.com/z-song/laravel-admin) — thanks to Z-song.

---

## License

ZiiX Admin is open-sourced software licensed under the [MIT License](LICENSE).
