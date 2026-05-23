# Migration Plan: Bootstrap → Tailwind CSS + Alpine.js

## Scope

Full replacement af Bootstrap 5, ChoicesJS, SweetAlert2, Toastify og npm/sass-pipeline med:

| Fra | Til |
|-----|-----|
| Bootstrap 5 CSS | Tailwind CSS v4 + Flowbite |
| Bootstrap JS (dropdowns, modals, collapse, popover, tab) | Alpine.js + custom vanilla JS |
| ChoicesJS | Tom Select |
| SweetAlert2 | Custom `admin.confirm()` (Promise-based, vanilla JS) |
| Toastify | Custom `admin.toastr` (vanilla JS, session flash) |
| `bootstrap.Modal` | Custom `admin.modal.create(element)` API |
| `bootstrap.Popover` | Custom `admin.grid.inline_edit.create_popover()` |
| `bootstrap.Tab` | Custom `admin.tabs` |
| `bootstrap.Collapse` | Alpine.js `x-collapse` plugin |
| npm + sass watch | Vite (laravel-vite-plugin) |

**Beholdes uændret:** Flatpickr, Leaflet, Coloris, Font Awesome, SortableJS, NProgress, Bootstrap Carousel (sjælden brug — ikke migreret).

## Mål

Et moderne ZiiX Admin baseret på Tailwind utility-first CSS, Alpine.js interaktivitet og Flowbite-komponenter. Frisk design (ikke 1:1 kopi af Bootstrap-layoutet). RTL-support bevaret via Tailwind `rtl:` variants og `dir` attribut. Zero Bootstrap-afhængigheder i slutproduktet.

## Branch-strategi

- Én branch pr. step. Format: `tw/<step-id>-<kort-navn>`.
- `tw/staging` er integration-branchen — merge hvert færdigt step hertil via `--no-ff`.
- Når alle steps er færdige merges `tw/staging` → `main` som én PR.
- **Regel: Ingen ny opgave startes uden at en ny branch er oprettet fra `tw/staging`.**

| Step | Branch | Status |
|------|--------|--------|
| B1 Asset pipeline | `tw/b1-asset-pipeline` | ✅ merged til staging |
| B2 Field renderers | `tw/b2-field-renderers` | ✅ merged til staging |
| B3 Grid displayers | `tw/b3-grid-displayers` | ✅ merged til staging |
| B4 Notifications | `tw/b4-notifications` | ✅ merged til staging |
| F1 Build verify | `tw/f1-build-verify` | ✅ merged til staging |
| F2 Base layout | `tw/f2-base-layout` | ✅ merged til staging |
| F3 Form core | `tw/f3-form-core` | ✅ merged til staging |
| F4 Form advanced | `tw/f4-form-advanced` | ✅ merged til staging |
| F5 Grid views | `tw/f5-grid` | ✅ merged til staging |
| F6 Show & Tree | `tw/f6-show-tree` | ✅ merged til staging |
| F7 Modals & Popover | `tw/f7-modals-popover` | ✅ merged til staging |
| F8 Final cleanup | `tw/f8-final-cleanup` | ✅ merged til staging |

---

## BACKEND

Backend-steps handler om PHP-kode der outputter HTML/CSS-klasser eller initialiserer JS.

---

### Step B1 — Asset pipeline (branch: `tw/b1-asset-pipeline`)

**Mål:** Vite erstatter npm/sass. AdminServiceProvider loader Vite-assets i stedet for hardcodede stier.

#### Task B1.1 — Installer og konfigurer Vite + Tailwind + Alpine + Flowbite + Tom Select

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b1-asset-pipeline (opret fra main)

Udfør følgende i package.json og tilhørende config-filer:

1. Fjern fra package.json devDependencies: alle sass/bootstrap-relaterede pakker
2. Tilføj til package.json:
   devDependencies: vite, laravel-vite-plugin, tailwindcss@4, @tailwindcss/vite,
                    autoprefixer, postcss
   dependencies: alpinejs, @alpinejs/collapse, @alpinejs/focus,
                 flowbite, tom-select, flatpickr, leaflet, sortablejs,
                 nprogress, @fortawesome/fontawesome-free

3. Opret vite.config.js i projektrod:
   import { defineConfig } from 'vite'
   import laravel from 'laravel-vite-plugin'
   export default defineConfig({
     plugins: [laravel({ input: ['resources/css/app.css', 'resources/js/app.js'], refresh: true })],
   })

4. Opret tailwind.config.js:
   content: ['./resources/views/**/*.blade.php', './src/**/*.php'],
   plugins: [require('flowbite/plugin')],
   (inkludér safelist for dynamisk genererede klasses-strenge: badge farver, status farver)

5. Opret postcss.config.js med tailwindcss og autoprefixer.

Verificér at npm run build kører uden fejl.
```

#### Task B1.2 — Opret resources/css/app.css og resources/js/app.js

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b1-asset-pipeline

1. Opret resources/css/app.css:
   @import 'tailwindcss';
   @import 'flowbite';
   /* Custom CSS-variabler til admin-theme (sidebar bredde, brand-farver) */
   :root { --sidebar-width: 16rem; --brand: #4f46e5; }
   [dir="rtl"] { --sidebar-direction: right; }

2. Opret resources/js/app.js:
   import Alpine from 'alpinejs'
   import Collapse from '@alpinejs/collapse'
   import Focus from '@alpinejs/focus'
   import 'flowbite'
   import TomSelect from 'tom-select'
   import flatpickr from 'flatpickr'
   import NProgress from 'nprogress'
   import Sortable from 'sortablejs'

   Alpine.plugin(Collapse)
   Alpine.plugin(Focus)
   window.Alpine = Alpine
   window.TomSelect = TomSelect
   window.flatpickr = flatpickr
   window.NProgress = NProgress
   window.Sortable = Sortable
   Alpine.start()

3. Slet resources/assets/sass/ og resources/assets/bootstrap/
   (Behold: resources/assets/flatpickr/, leaflet/, fontawesome/, sortable/, nprogress/)
```

#### Task B1.3 — Opdater AdminServiceProvider.php til Vite-assets

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b1-asset-pipeline
Fil: src/AdminServiceProvider.php

1. Find alle steder der registrerer Bootstrap CSS/JS og gamle asset-stier via Admin::css() / Admin::js()
2. Erstat med Vite::asset() kald eller @vite blade-direktiv
3. Fjern registrering af: bootstrap.css, bootstrap.js, choices.js, sweetalert2.js, toastify.js
4. Tilføj: Vite-manifest-baseret asset-loading for app.css og app.js
5. Sørg for at admin_asset() helper stadig virker for extensions der loader egne assets
```

#### Task B1.4 — Opdater HasAssets trait

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b1-asset-pipeline
Fil: src/Traits/HasAssets.php

1. Fjern $defaultStyles array-entries for Bootstrap og andre erstattede libs
2. Fjern $defaultScripts array-entries for Bootstrap JS, ChoicesJS, SweetAlert2, Toastify
3. Tilføj Vite-baseret asset-registrering (app.css, app.js) som default
4. Bevar entries for: flatpickr, leaflet, fontawesome (eller flyt til npm-bundlet i app.js)
5. Opdater Admin::css() og Admin::js() static methods til at fungere med ny pipeline
```

#### Task B1.5 — Opdater composer.json scripts og fjern sass-script

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b1-asset-pipeline

1. I package.json: fjern "sass" watch-script
2. Tilføj scripts: "dev": "vite", "build": "vite build"
3. Opdater CLAUDE.md build-sektion til at afspejle ny pipeline
4. Kør npm run build og verificér at public/ eller dist/ indeholder korrekte assets
5. Opdater .gitignore hvis nødvendigt (Vite output-mappe)
```

---

### Step B2 — PHP Form Field renderers (branch: `tw/b2-field-renderers`)

**Mål:** Fjern alle hardcodede Bootstrap CSS-klasser fra PHP field-klasser. Erstat ChoicesJS med Tom Select.

#### Task B2.1 — Audit og erstat Bootstrap form-klasser i base Field

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b2-field-renderers (opret fra tw/b1-asset-pipeline eller main efter merge)

Filer: src/Form/Field.php (base), src/Form/Builder.php

1. Søg efter alle Bootstrap-klassestrenge: 'form-control', 'form-group', 'input-group',
   'col-sm-', 'col-md-', 'col-lg-', 'row', 'form-check', 'form-select'
2. Erstat med Tailwind-klasser:
   - form-control → 'bg-white border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 focus:ring-blue-500 focus:border-blue-500'
   - form-group → 'mb-4'
   - label → 'block mb-2 text-sm font-medium text-gray-900'
3. Opdater getElementClass() / buildClass() metoder
4. Bevar RTL-kompatibilitet: brug rtl:text-right klasser hvor relevant
5. Kør eksisterende PHPUnit tests for at sikre ingen regression
```

#### Task B2.2 — Erstat ChoicesJS med Tom Select i Select og Multiselect fields

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b2-field-renderers
Filer: src/Form/Field/Select.php, src/Form/Field/Multiselect.php

1. Find JS-initialiseringskode der kalder 'new Choices(...)' eller 'choices('
2. Erstat med Tom Select init: 'new TomSelect(selector, { ... })'
3. Map ChoicesJS options til Tom Select ækvivalenter:
   - searchEnabled → plugins: ['input_autogrow'] + searchField
   - removeItemButton → plugins: ['remove_button']
   - placeholderValue → placeholder
4. Opdater AJAX-søgning i BelongsTo-felter (remote data loading i Tom Select)
5. Test at dynamiske select-options (via cascade/depends) stadig virker
```

#### Task B2.3 — Erstat Bootstrap i text/textarea/file field PHP-klasser

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b2-field-renderers
Filer: src/Form/Field/Text.php, Textarea.php, Number.php, Email.php,
       Password.php, Url.php, File.php, Image.php, MultipleImage.php

For hver fil:
1. Find $attributes array eller class() kald med Bootstrap-klasser
2. Erstat 'form-control' med Tailwind input-klasser (se B2.1 for standardklasser)
3. For File/Image: erstat 'custom-file-input' / 'btn btn-primary' med Tailwind-ækvivalenter
4. Fjern eventuelle 'input-group-prepend' / 'input-group-append' Bootstrap-struktuer
   fra PHP-output (flyttes til Blade-template i F4)
5. Verificér at prepend/append addon-funktionalitet (Text::prepend(), Text::append()) er bevaret
```

#### Task B2.4 — Erstat Bootstrap i relationship field PHP-klasser

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b2-field-renderers
Filer: src/Form/Field/BelongsTo.php, BelongsToMany.php, HasMany.php,
       src/Form/EmbeddedForm.php

1. Find Bootstrap-klasse referencer i PHP render/render() og script() metoder
2. Erstat btn-klasser: 'btn btn-primary' → 'text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2'
3. Erstat Bootstrap panel/card: 'panel panel-default' → Tailwind card-klasser
4. I HasMany/NestedForm: erstat Bootstrap row/col grid med Tailwind flex/grid
5. Sikr at add-row / remove-row JS-logik stadig virker (tilpas selectors hvis nødvendigt)
```

#### Task B2.5 — Erstat Bootstrap i checkbox, radio, switch, slider, date fields

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b2-field-renderers
Filer: src/Form/Field/Checkbox.php, Radio.php, SwitchField.php,
       Slider.php, DatePicker.php, TimePicker.php, DatetimeRange.php

1. Checkbox/Radio: erstat 'form-check', 'form-check-input', 'form-check-label'
   → Flowbite checkbox klasser: 'w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500'
2. SwitchField: erstat Bootstrap custom-switch med Flowbite toggle HTML
3. Slider: erstat Bootstrap slider-styling med Tailwind range input klasser
4. DatePicker: erstat Bootstrap form-control wrapping — Flatpickr beholdes men ny Tailwind wrapper
5. DatetimeRange: erstat input-group Bootstrap struktur med Tailwind flex-row container
```

---

### Step B3 — Grid displayers & tools (branch: `tw/b3-grid-displayers`)

**Mål:** Fjern Bootstrap badge/label/btn klasser fra alle Grid displayers og tools.

#### Task B3.1 — Erstat Bootstrap i Badge, Status, Label, Tags displayers

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b3-grid-displayers (opret fra main/seneste merged branch)
Filer: src/Grid/Displayers/Badge.php, Status.php, Label.php, Tags.php

Bootstrap → Tailwind mapping:
- badge badge-primary → 'bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded'
- badge badge-success → 'bg-green-100 text-green-800 ...'
- badge badge-danger → 'bg-red-100 text-red-800 ...'
- badge badge-warning → 'bg-yellow-100 text-yellow-800 ...'
- badge badge-secondary → 'bg-gray-100 text-gray-800 ...'

For dynamiske farver (Status-displayer): opret color-map array i PHP
der mapper farve-navn til Tailwind klasse-sæt. Tilføj til tailwind.config.js safelist
alle disse dynamiske klasser så de ikke purges.
```

#### Task B3.2 — Erstat Bootstrap i Link, Button, Image, Datetime displayers

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b3-grid-displayers
Filer: src/Grid/Displayers/Link.php, Button.php, Image.php, Datetime.php,
       Progressbar.php, Orderable.php, QRCode.php

1. Link: erstat 'btn btn-xs btn-link' → Tailwind link styling
2. Button: erstat btn Bootstrap klasser → Tailwind button klasser
3. Image: erstat img-thumbnail → Tailwind rounded ring klasser
4. Datetime: fjern Bootstrap text-klasser, brug Tailwind
5. Progressbar: erstat Bootstrap progress/progress-bar → Tailwind w-full bg-gray-200 / h-2.5 rounded
```

#### Task B3.3 — Erstat Bootstrap i Grid Column og Filter PHP-output

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b3-grid-displayers
Filer: src/Grid/Column.php, src/Grid/Filter.php, src/Grid/Filter/*.php

1. Column.php: fjern Bootstrap col-* klasser fra PHP-genereret HTML
2. Filter klasser (Equal, Like, Between, etc.): erstat form-control, form-group
3. Filter presenter klasser: erstat Bootstrap panel/card HTML med Tailwind ækvivalenter
4. Grid/Model.php: ingen CSS-ændringer forventet — verificér at ingen er gemt der
5. Tilføj Tailwind klasser til filter wrapper divs der genereres i PHP
```

#### Task B3.4 — Erstat Bootstrap i Grid Tools PHP-klasser

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b3-grid-displayers
Filer: src/Grid/Tools/CreateButton.php, BatchDelete.php, BatchActions.php,
       ExportButton.php, FilterButton.php, ColumnSelector.php, QuickCreate.php

1. CreateButton: 'btn btn-sm btn-success' → Tailwind green button klasser
2. BatchDelete: 'btn btn-sm btn-danger' → Tailwind red button
3. ExportButton: 'btn btn-sm btn-default' → Tailwind neutral button
4. FilterButton: erstat Bootstrap toggle-button → Alpine.js x-data toggle + Tailwind
5. ColumnSelector: erstat Bootstrap dropdown → Flowbite dropdown komponent
```

#### Task B3.5 — Erstat Bootstrap i Grid Row Actions

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b3-grid-displayers
Filer: src/Grid/Actions/*, src/Actions/GridAction.php, RowAction.php, BatchAction.php

1. RowAction: erstat 'btn btn-xs' → Tailwind mini-button klasser
2. Dropdown actions: erstat Bootstrap dropdown HTML → Flowbite dropdown
3. BatchAction: erstat Bootstrap modal trigger → Flowbite modal trigger
4. Gennemgå Authorizable trait: ingen CSS, men verificér at output HTML er korrekt
5. Actions/Response.php: verificér at redirect/refresh ikke afhænger af Bootstrap modal JS
```

---

### Step B4 — Notifications & action dialogs (branch: `tw/b4-notifications`) ✅

**Mål:** Erstat SweetAlert2 og Toastify med Flowbite-baserede løsninger. Opdater helpers.

> **Implementeret anderledes end planlagt:** Flowbite Modal og Flowbite Toast blev IKKE brugt.
> I stedet blev custom vanilla JS løsninger implementeret:
> - `admin.confirm()` — Promise-based confirm dialog (erstatter SweetAlert2)
> - `admin.toastr` — Custom toast system (erstatter Toastify)
> Se detaljer i "Completed Implementation Summary" nederst.

#### Task B4.1 — Opret FlowbiteModal som erstatning for SweetAlert2

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b4-notifications (opret fra main/seneste merged branch)
Filer: src/Actions/SweatAlert2.php → omdøb og refaktorér til src/Actions/FlowbiteModal.php

Flowbite Modal bruges via Alpine.js:
- Bevar samme public interface: confirm(title, body, onConfirm, onCancel)
- Output Alpine.js x-data modal HTML i stedet for SweetAlert2 JS-kald
- Flowbite modal markup: <div id="modal" data-modal-target ...> med Tailwind klasser
- JS-trigger: FlowbiteModal.confirm() skal kalde Flowbite modal API
- Opdater src/Actions/Action.php til at bruge FlowbiteModal i stedet for SweatAlert2
```

#### Task B4.2 — Opret FlowbiteToast som erstatning for Toastify

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b4-notifications
Filer: src/Actions/Toastr.php → refaktorér til src/Actions/FlowbiteToast.php

1. Bevar samme interface: success(msg), error(msg), warning(msg), info(msg)
2. Output Flowbite toast HTML + Alpine.js x-show/x-transition
3. Toast HTML injectes i <body> via blade @stack('toasts') + @push('toasts')
4. Auto-dismiss efter 3s via Alpine.js $timeout
5. Stack-support: multiple toasts vises over hinanden
```

#### Task B4.3 — Opdater helpers.php notification-funktioner

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b4-notifications
Fil: src/helpers.php

Funktioner der skal opdateres:
- admin_toastr($message, $type, $options) → output Flowbite toast HTML til session flash
- admin_success($message) → kalder admin_toastr med type='success'
- admin_error($message) → kalder admin_toastr med type='error'
- admin_warning($message) → kalder admin_toastr med type='warning'
- admin_info($message) → kalder admin_toastr med type='info'

Session flash-baseret: gem HTML i session('admin_toastr'), vis i layout-template.
Fjern alle Toastify JS-kald fra helpers.
```

#### Task B4.4 — Opdater Action Interactor (dialog forms)

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b4-notifications
Filer: src/Actions/Interactor/Dialog.php, Form.php, Interactor.php

1. Dialog.php: erstat Bootstrap modal HTML med Flowbite modal struktur
2. Form.php (action inline form): erstat Bootstrap form-klasser med Tailwind
3. Interactor.php: verificér at JS-håndtering af modal open/close bruger Flowbite API
4. Test at grid row-actions med confirm dialog virker end-to-end
5. Test at batch actions med form-dialog virker
```

#### Task B4.5 — Fjern SweetAlert2 og Toastify fra hele codebase

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/b4-notifications

1. Grep efter 'sweetalert', 'swal', 'Swal', 'toastify', 'Toastify' i hele src/
2. Erstat alle resterende forekomster med Flowbite-ækvivalenter
3. Grep efter 'sweetalert' og 'toastify' i resources/views/ og resources/js/
4. Fjern sweetalert2 og toastify fra package.json dependencies
5. Kør PHPUnit test-suite og verificér ingen test fejler
```

---

## FRONTEND

Frontend-steps handler om Blade-templates, CSS og JS.

---

### Step F1 — Build system verifikation (branch: `tw/f1-build-verify`)

**Mål:** Bekræft at Vite pipeline fra B1 producerer korrekte assets og at HMR virker i udviklingsmiljø.

#### Task F1.1 — Verificér Tailwind purging og safelist

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f1-build-verify

1. Kør npm run build og inspicér output CSS-størrelse
2. Tjek at alle dynamiske Tailwind-klasser fra PHP-filer er inkluderet
   (grep src/**/*.php for tw-klasser, verificér mod compiled CSS)
3. Tilføj manglende dynamiske klasser til tailwind.config.js safelist
4. Kør npm run dev og verificér HMR virker med en test Blade-template
5. Dokumentér hvilke klasser der kræver safelist i tailwind.config.js kommentar
```

#### Task F1.2 — Opsæt Alpine.js devtools og test grundkomponenter

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f1-build-verify

1. Tilføj @alpinejs/devtools til dev-afhængigheder (kun i dev-mode)
2. Opret en simpel test Blade-template med 3 Alpine.js komponenter:
   - x-data toggle (vis/skjul)
   - x-data dropdown
   - Tom Select initialisering
3. Verificér at alle 3 virker i browser
4. Test Flowbite modal og toast i samme template
5. Test RTL: sæt dir="rtl" og verificér at rtl: Tailwind-varianter virker
```

---

### Step F2 — Base layout & navigation (branch: `tw/f2-base-layout`)

**Mål:** Hoved-layout, sidebar og topnav bygges med Flowbite + Tailwind + Alpine.js.

#### Task F2.1 — Opret hoved-layout template

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f2-base-layout
Fil: resources/views/layouts/admin.blade.php (ny fil erstatter content.blade.php)

HTML-struktur:
<html dir="{{ app()->getLocale() === 'ar' || ... ? 'rtl' : 'ltr' }}">
<head>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('css')
</head>
<body class="bg-gray-50 dark:bg-gray-900">
  [sidebar] [main content: topnav + content area + footer]
</body>

Krav:
- Responsive: sidebar kollapser til hamburger på mobile (md breakpoint)
- Alpine.js x-data="{ sidebarOpen: true }" på body
- @stack('scripts') og @stack('toasts') i bunden
- Fjern Bootstrap container/row/col struktur
```

#### Task F2.2 — Byg Flowbite sidebar

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f2-base-layout
Fil: resources/views/partials/sidebar.blade.php

Flowbite sidebar-komponent krav:
1. Fast sidebar på desktop (w-64), overlay på mobile med Alpine x-show
2. Menu-items fra Admin::menu() — recursive rendering af nested items
3. Active state: sammenlign current route med menu item URL
4. Icons: Font Awesome klasse-support (menu items har icon-felt)
5. RTL: sidebar skal automatisk flyttes til højre side når dir="rtl"
   (brug Tailwind rtl:right-0 rtl:left-auto klasser)
```

#### Task F2.3 — Byg Flowbite topnav

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f2-base-layout
Fil: resources/views/partials/navbar.blade.php

Flowbite navbar krav:
1. Hamburger-knap der toggler sidebar via Alpine ($dispatch('toggle-sidebar'))
2. Breadcrumb/page-titel sektion
3. User-dropdown (højre): bruger-avatar, navn, link til settings, logout
4. Søgefelt (hvis QuickSearch er aktiveret på grid)
5. RTL: dropdown åbner til venstre når dir="rtl"
```

#### Task F2.4 — Opdater Content layout klasse

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f2-base-layout
Fil: src/Layout/Content.php, resources/views/content.blade.php

1. Content.php: erstat Bootstrap container/row/col klasser med Tailwind
2. content.blade.php: erstat Bootstrap .content-wrapper med Tailwind main tag
3. Layout/Row.php og Layout/Column.php: erstat Bootstrap col-md-X med Tailwind col-span-X grid
4. Sikr at eksisterende $content->row(function($row) { ... }) API stadig virker
5. Test med en eksisterende AdminController at layout renderes korrekt
```

#### Task F2.5 — Opdater login-side og flash messages

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f2-base-layout
Fil: resources/views/login.blade.php, resources/views/partials/alert.blade.php

Login:
- Flowbite card centreret på skærmen (flex min-h-screen items-center justify-center)
- Email + password inputs med Flowbite form styling
- Submit knap: Tailwind full-width button
- Error messages: Flowbite alert komponent

Flash messages (admin_toastr / session):
- Byg resources/views/partials/toasts.blade.php med Alpine.js auto-dismiss
- Inkludér @include('admin::partials.toasts') i hoved-layout
```

---

### Step F3 — Form views: core fields (branch: `tw/f3-form-core`)

**Mål:** Alle basis-formfelter har nye Blade-templates med Tailwind + Flowbite styling.

#### Task F3.1 — Form layout templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f3-form-core
Filer: resources/views/form/form.blade.php, row.blade.php,
       resources/views/form/layout/column.blade.php

1. form.blade.php: erstat Bootstrap form-horizontal med Tailwind form layout
   Standard label/field layout: label over input (ikke inline)
   Valgfrit: $form->horizontal() aktiverer 2-kolonne grid layout
2. row.blade.php: erstat Bootstrap .row med Tailwind .grid .grid-cols-{n}
3. column.blade.php: erstat Bootstrap col-md-X med Tailwind col-span-X
4. Tab-template: erstat Bootstrap nav-tabs med Flowbite tabs-komponent + Alpine.js
5. Form tools (submit, reset, tilbage-knap): Tailwind button styling
```

#### Task F3.2 — Text-type field templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f3-form-core
Filer: resources/views/form/text.blade.php, textarea.blade.php,
       number.blade.php, email.blade.php, password.blade.php, url.blade.php

For hvert felt:
- Label: <label class="block mb-2 text-sm font-medium text-gray-900">
- Input wrapper: <div class="relative">
- Input: class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
         focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
- Error state: ring-red-500 border-red-500 + rød fejlbesked nedenunder
- Prepend/append addon: Tailwind flex + styled addon div (erstatter input-group)
- Help text: <p class="mt-2 text-sm text-gray-500">
- RTL: inputs sætter automatisk text-right via dir="rtl" på parent
```

#### Task F3.3 — Select og Multiselect templates med Tom Select

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f3-form-core
Filer: resources/views/form/select.blade.php,
       resources/views/form/multiselect.blade.php

1. Basis HTML: <select id="{{ $id }}" name="{{ $name }}">...</select>
2. Tom Select initialisering via inline <script>:
   new TomSelect('#{{ $id }}', {
     plugins: ['remove_button'],  // kun multiselect
     placeholder: '{{ $placeholder }}',
   })
3. AJAX remote search (BelongsTo): url og valueField options til Tom Select
4. Tailwind wrapper klasser rundt om Tom Select container (.ts-wrapper)
5. Overstyr Tom Select CSS med Tailwind-kompatible styles i app.css
```

#### Task F3.4 — Checkbox, Radio og Switch templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f3-form-core
Filer: resources/views/form/checkbox.blade.php,
       resources/views/form/radio.blade.php,
       resources/views/form/switch_field.blade.php

Checkbox (Flowbite styling):
<input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
  rounded focus:ring-blue-500">

Radio:
<input type="radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
  focus:ring-blue-500">

Switch (Flowbite toggle):
<label class="relative inline-flex items-center cursor-pointer">
  <input type="checkbox" class="sr-only peer">
  <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 rounded-full peer
    peer-checked:after:translate-x-full peer-checked:bg-blue-600 ..."></div>
</label>

Alle 3: støt multiple options som inline liste
```

#### Task F3.5 — File og Image upload templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f3-form-core
Filer: resources/views/form/file.blade.php, image.blade.php,
       resources/views/form/multiple_image.blade.php

1. File: Flowbite file input styling
   <input type="file" class="block w-full text-sm text-gray-900 border border-gray-300
   rounded-lg cursor-pointer bg-gray-50 focus:outline-none">

2. Image: preview thumbnail + fjern-knap via Alpine.js
   Alpine x-data="{ preview: '{{ $value }}' }" med img x-show + input @change preview

3. MultipleImage: Tailwind grid-cols-4 thumbnail grid
   Alpine.js håndterer tilføj/fjern billeder
   Sortable.js drag-sort bevares

4. Alle upload-felter: vis eksisterende fil/billede med fjern-knap
5. Fejlhåndtering: vis Flowbite alert ved upload-fejl
```

---

### Step F4 — Form views: advanced fields (branch: `tw/f4-form-advanced`)

**Mål:** Relationship-felter, date/time-felter, og nested forms i Tailwind.

#### Task F4.1 — BelongsTo og BelongsToMany templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f4-form-advanced
Filer: resources/views/form/belongs_to.blade.php,
       resources/views/form/belongs_to_many.blade.php

BelongsTo (single remote select):
- Tom Select med AJAX remote search (url fra PHP Field-klasse)
- Tailwind wrapper med loading-spinner under søgning
- Vis current selected item tydeligt

BelongsToMany (multi remote select):
- Tom Select med plugins: ['remove_button', 'dropdown_input']
- Chips/tags for valgte items (Tom Select har dette built-in)
- Tailwind container styling

Begge: sikr at value sættes korrekt ved edit (existing record)
```

#### Task F4.2 — HasMany / NestedForm template

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f4-form-advanced
Filer: resources/views/form/has_many.blade.php,
       resources/views/form/nested_form.blade.php (EmbeddedForm views)

1. Container: Tailwind border rounded-lg p-4 mb-4 per nested record
2. Alpine.js x-data="{ rows: [...] }" til add/remove rows uden page reload
3. Tilføj-knap: Tailwind outline-button med + ikon
4. Fjern-knap per row: lille rød X knap
5. Sikr at row-index i input names opdateres korrekt ved add/remove
   (brug Alpine.js til at styre indices dynamisk)
```

#### Task F4.3 — Date, Time og DatetimeRange templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f4-form-advanced
Filer: resources/views/form/date.blade.php, time.blade.php,
       resources/views/form/datetime.blade.php,
       resources/views/form/date_range.blade.php,
       resources/views/form/time_range.blade.php

Flatpickr beholdes — kun wrapper styling opdateres:
1. Input: samme Tailwind klasser som text input (F3.2)
2. Tilføj kalender-ikon i prepend position (Font Awesome fa-calendar)
3. DatetimeRange: to inputs side om side med Tailwind flex gap-2
4. Flatpickr initialisering: erstat bootstrap theming med Tailwind-compatible theme
   (flatpickr har 'confetti' og 'dark' themes — eller custom CSS i app.css)
5. RTL: flatpickr støtter RTL via position: 'auto'
```

#### Task F4.4 — Map, Color, Icon og Slider templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f4-form-advanced
Filer: resources/views/form/map.blade.php, color.blade.php,
       resources/views/form/icon.blade.php, slider.blade.php

1. Map (Leaflet): bevar Leaflet JS init, erstat Bootstrap container med
   Tailwind border rounded-lg overflow-hidden (map container)
2. Color: bevar Coloris, erstat Bootstrap input-group wrapper med Tailwind flex
3. Icon: erstat Bootstrap modal trigger med Flowbite modal
   Icon-søgning og grid af Font Awesome icons i Flowbite modal
4. Slider: erstat Bootstrap slider CSS med Tailwind range input styling
   <input type="range" class="w-full h-2 bg-gray-200 rounded-lg cursor-pointer accent-blue-600">
5. For alle 4: bevar JS-init logik, kun CSS-wrapper ændres
```

#### Task F4.5 — Embeds og resterende field templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f4-form-advanced
Filer: resources/views/form/embeds.blade.php, divider.blade.php,
       resources/views/form/hidden.blade.php, nullable.blade.php

1. Embeds: Tailwind card/border container rundt om embedded fields
2. Divider: <hr class="my-4 border-gray-200"> + valgfri label
3. Hidden: ingen visuel ændring nødvendig
4. Nullable: lille "sæt til null" toggle knap i Tailwind styling
5. Gennemgå resources/views/form/ og lav liste over eventuelle resterende
   blade-filer der ikke er dækket af F3-F4 og migrér dem
```

---

### Step F5 — Grid views (branch: `tw/f5-grid`)

**Mål:** Data-grid med alle filtre, tools og actions i Tailwind + Flowbite + Alpine.js.

#### Task F5.1 — Hoved grid tabel

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f5-grid
Fil: resources/views/grid/table.blade.php (og tilhørende partials)

Flowbite tabel-komponent:
<table class="w-full text-sm text-left rtl:text-right text-gray-500">
  <thead class="text-xs text-gray-700 uppercase bg-gray-50">
  <tbody class="divide-y divide-gray-200">

Krav:
1. Sorterbare kolonner: sort-pil ikon (Alpine toggle asc/desc), link til sort URL
2. Checkbox kolonne til batch-select: Flowbite checkbox styling
3. Actions kolonne: Flowbite dropdown per row
4. Tom tabel-state: centreret Tailwind besked med ikon
5. Loading state: NProgress topbar bevares, tabel overlay med Tailwind opacity
```

#### Task F5.2 — Grid filter templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f5-grid
Filer: resources/views/grid/filter/index.blade.php,
       resources/views/filter/ (alle 16 presenter-templates)

1. Filter container: Flowbite card, vises/skjules med Alpine x-show + slide transition
2. Filter inputs: samme Tailwind input-klasser som form fields
3. Filter knapper (submit/reset): Tailwind button par
4. Filter layouts: inline (kompakt) og blok (grid med labels)
5. Date-range filter: Flatpickr range mode, Tailwind wrapper
```

#### Task F5.3 — Grid tools templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f5-grid
Filer: resources/views/grid/tools.blade.php og tool-partials

1. Tools toolbar: Tailwind flex justify-between mb-4 (venstre: batch tools, højre: action tools)
2. CreateButton: Tailwind grøn knap med + ikon (Font Awesome)
3. ExportButton: Flowbite dropdown med CSV/Excel valgmuligheder
4. ColumnSelector: Flowbite dropdown med checkboxes (Alpine x-data)
5. QuickSearch: Tailwind søgefelt med submit ved Enter (Alpine @keydown.enter)
```

#### Task F5.4 — Batch actions og pagination

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f5-grid
Filer: resources/views/grid/batch_actions.blade.php,
       resources/views/pagination.blade.php

Batch actions:
1. Toolbar der vises når mindst 1 row er valgt (Alpine x-show)
2. "X valgt" tæller + BatchDelete knap + custom batch actions
3. Flowbite confirm modal til BatchDelete (erstat SweetAlert2)
4. Deselect all knap

Pagination:
1. Flowbite pagination komponent
2. "Viser X-Y af Z" tekst
3. Per-page selector (Tom Select eller native select)
```

#### Task F5.5 — Inline edit og QuickCreate

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f5-grid
Filer: resources/views/grid/inline_edit.blade.php (hvis eksisterer),
       resources/views/grid/quick_create.blade.php

1. Inline edit: klik på celle → Alpine skifter til input, Enter/blur gemmer via AJAX
2. Tailwind fokus-ring på aktiv inline-edit celle
3. QuickCreate: skjult form-row øverst i tabellen, Alpine x-show
4. QuickCreate inputs: kompakte Tailwind inputs (py-1 px-2)
5. Verificér at AJAX-kald til HandleController virker med ny HTML-struktur
```

---

### Step F6 — Show, Tree, Widgets, Dashboard, Components og resterende views (branch: `tw/f6-show-tree`)

**Mål:** Komplet Blade-template migration af show-sider, tree, widgets, dashboard, partials, form-felter og login. Bredere scope end planlagt — alt ikke-grid indhold blev migreret her.

#### Task F6.1 — Show page panel og field templates ✅

```
Filer: resources/views/show/panel.blade.php, show/field.blade.php,
       resources/views/show/divider.blade.php

Panel: Tailwind card med PHP-computed border-farve baseret på $style variabel
(primary/info/success/warning/danger/none → Tailwind border-color klasser).
Field: description list med dt/dd og Tailwind tekst-klasser.
```

#### Task F6.2 — Tree view templates ✅

```
Filer: resources/views/tree/ (alle filer)

Tailwind nested ul/li med SortableJS drag-drop bevaret.
Alpine.js x-data pr. node til expand/collapse.
```

#### Task F6.3 — Widgets ✅

```
Filer: resources/views/widgets/*.blade.php (alert, box, collapse, info-box, tab, table m.fl.)
Tilhørende PHP: src/Widgets/Alert.php, Box.php, InfoBox.php, Table.php

- Alert widget: Alpine x-data { show: true }, style-variabel → Tailwind farve-map, x-show dismissable
- Box widget: src/Widgets/Box.php class → 'bg-white rounded-lg shadow-sm border border-gray-200'
- Collapse widget: Alpine x-data { open: ... } per item + x-collapse plugin
- InfoBox widget: src/Widgets/InfoBox.php → 'info-box' klasse; view bruger PHP @php color-map array
- Tab widget: fjernet data-bs-toggle="tab", admin.tabs JS håndterer tab-skift
- Table widget: src/Widgets/Table.php → 'w-full text-sm text-left text-gray-700'
```

#### Task F6.4 — Dashboard views ✅

```
Filer: resources/views/dashboard/dependencies.blade.php,
       environment.blade.php, extensions.blade.php

Alpine x-data collapse-pattern erstatter Bootstrap collapse.
```

#### Task F6.5 — Grid components, partials og form-felter ✅

```
Filer: resources/views/components/column-selector.blade.php,
       components/export-btn.blade.php,
       partials/exception.blade.php,
       form/captcha.blade.php, form/rate.blade.php,
       form/daterange.blade.php, form/button.blade.php, form/editor.blade.php

- column-selector: Alpine x-data { open: false } dropdown, bevarede id og data-defaults attributter
- export-btn: Alpine dropdown, split-knap med rounded-s-lg/rounded-e-lg
- exception: Alpine x-data { show: true, trace: false }, erstatter data-bs-dismiss + d-none
- captcha/rate/daterange: input-group Bootstrap struktur → .admin-input-group CSS klasse
- button: btn {{ $class }} → btn-field {{ $class }}; src/Form/Field/Button.php → Tailwind klasser
- editor: fjernet form-control fra textarea
- src/Grid/Tools/PerPageSelector.php: form-group/form-select Bootstrap → Tailwind flex label + select
```

#### Task F6.6 — Login side ✅

```
Fil: resources/views/login.blade.php

Standalone Tailwind side — Bootstrap CDN link fjernet helt.
Loader Admin::asset('ziix-admin/dist/css/app.css') i stedet.
```

---

### Step F7 — Modals & Popover JS-replacement (branch: `tw/f7-modals-popover`)

**Mål:** Erstatte alle Bootstrap JS-komponenter (Modal, Popover, Collapse, Tab) med custom vanilla JS og Alpine.js. Migrere alle Blade-templates der bruger Bootstrap modal/popover triggers.

#### Task F7.1 — Custom admin.modal API ✅

```
Fil: resources/assets/open-admin/js/open-admin.js

Tilføjet admin.modal objekt med:
- admin.modal.create(element): returnerer { show(), hide(), element, _escHandler }
  - show(): opretter backdrop div, fjerner hidden klasse, låser body scroll,
             lytter på ESC-tast, dispatcherer CustomEvent 'modal.show'
  - hide(): fjerner backdrop, tilføjer hidden klasse, genaktiverer scroll,
             dispatcherer CustomEvent 'modal.hide'
- admin.modal.init(): global delegeret click-handler for [data-modal-close] attribut

Erstatter bootstrap.Modal i hele codebasen.
```

#### Task F7.2 — Custom admin.grid.inline_edit.create_popover() ✅

```
Fil: resources/assets/open-admin/js/open-admin-grid-inline-edit.js

Oprettet create_popover(el, getContent) funktion:
- Opretter positioneret div appendet til document.body
- Positionerer via getBoundingClientRect() relativt til trigger-element
- API: popover.show(), popover.hide(), popover.toggle(), popover.tip (div element)
- hide_other_popovers() funktion (korrigeret stavefejl fra hide_ohter_popovers)
- Legacy alias hide_ohter_popovers bevaret for kompatibilitet

Erstatter new bootstrap.Popover() i inline-edit.
```

#### Task F7.3 — open-admin-selectable.js: bootstrap.Modal → admin.modal ✅

```
Fil: resources/assets/open-admin/js/open-admin-selectable.js

- Erstattet new bootstrap.Modal(modal_elm) med admin.modal.create(modal_elm)
- show.bs.modal event → modal.show CustomEvent via event.detail.relatedTarget
- related target sættes direkte i trigger click-handler FØR modal.show() kaldes
- _initShow(relatedTarget) funktion håndterer value/URL-loading
```

#### Task F7.4 — open-admin-grid.js: Bootstrap Collapse og Dropdown ✅

```
Fil: resources/assets/open-admin/js/open-admin-grid.js

- show.bs.dropdown / hide.bs.dropdown → table.style.overflow = 'visible'
- new bootstrap.Collapse() + bsCollapse.toggle()
  → myCollapse.classList.toggle('show')
```

#### Task F7.5 — Action Interactor Form.php: modal.hide fix ✅

```
Fil: src/Actions/Interactor/Form.php

Fejl: modal.hide() kaldt uden at modal var defineret i scope.
Fix: var modal = admin.modal.create(myModalEl) tilføjet øverst i click-handler,
     modal.show() og modal.hide() bruges derfra.
{ once: true } tilføjet til form submit listener for at forhindre dublette handlers.
```

#### Task F7.6 — Blade-templates: Bootstrap modal triggers → admin.modal ✅

```
Filer migreret:
- resources/views/actions/form/modal.blade.php
  Tailwind modal-struktur: hidden overflow-y-auto fixed inset-0 z-50 flex...
  Close buttons bruger data-modal-close="{{ $modal_id }}"

- resources/views/components/column-modal.blade.php
  data-bs-toggle="modal" → grid-modal-trigger klasse + custom click JS
  show.bs.modal → direkte trigger+load pattern med admin.modal.create()

- resources/views/components/mediapicker.blade.php
  Tailwind modal-struktur, data-modal-close knapper, footer hidden initialt

- resources/views/components/valuepicker.blade.php
  Tailwind modal-struktur, modal-footer synlig fra start (ikke hidden)

- resources/views/grid/inline-edit/belongsto.blade.php
  data-bs-toggle="modal" fjernet, trigger: '#{{ $display_field }}-{{$key}}'
  tilføjet til selectable-config, Tailwind modal HTML

- resources/views/grid/inline-edit/comm.blade.php
  data-bs-popover fjernet, Tailwind ie-action knapper (px-2 py-1 text-xs...)

- resources/views/components/column-expand.blade.php
  data-bs-toggle/target fjernet
  bootstrap.Collapse.getOrCreateInstance(target).show()
  → target.style.display = 'block'
  expand.click() (virker ikke på NodeList) → expand.forEach(el => el.click())
```

---

### Step F8 — Final cleanup (branch: `tw/f8-final-cleanup`)

**Mål:** Rydde op i resterende Bootstrap-referencer og sikre at alle Vite builds kører fejlfrit.

#### Task F8.1 — Bootstrap-klasser fjernet fra resterende PHP-filer ✅

```
Alle Bootstrap CSS-klassestrenge auditeret og fjernet/erstattet med Tailwind
i PHP-filer der genererer HTML (displayers, tools, widgets).
```

#### Task F8.2 — Vite build verificeret ✅

```
Vite build kører uden fejl i Docker-miljø:
docker run --rm -v /Users/hurup/docker/ziix/admin:/app -w /app node:20-alpine \
  sh -c "node node_modules/.bin/vite build 2>&1"

Output: ✓ 213 modules transformed
CSS: ~265 KB (gzip ~45 KB)
JS:  ~547 KB (gzip ~163 KB)
```

#### Task F8.3 — Bootstrap JS-afhængigheder fjernet ✅

```
Alle bootstrap.Modal, bootstrap.Popover, bootstrap.Collapse, bootstrap.Tab kald
erstattet med custom admin.* API og Alpine.js.
Bootstrap JS loader ikke længere i admin-panel.
```

---

## Completed Implementation Summary

### Arkitektoniske beslutninger

**admin.modal.create(element)**
Lightweight vanilla JS modal API der erstatter `bootstrap.Modal`. Oprettet i `open-admin.js`.
- Opretter og fjerner backdrop-div dynamisk
- Låser body scroll under åben modal
- ESC-tast lukker modal
- Dispatcherer `modal.show` / `modal.hide` CustomEvents (erstatter Bootstrap's `show.bs.modal`)
- `admin.modal.init()` håndterer `[data-modal-close]` click-delegation globalt

**admin.grid.inline_edit.create_popover(el, getContent)**
Positioneret floating div som erstatter `bootstrap.Popover`. Placeres via `getBoundingClientRect()`.
API: `popover.show()`, `popover.hide()`, `popover.toggle()`, `popover.tip`.

**admin.tabs**
Custom tab-switcher der håndterer `.nav-link` / `.tab-pane.active` CSS-pattern uden Bootstrap Tab JS.

**admin.filter.init()**
Filter-box toggle via `data-filter-target` attribut.

**admin.confirm()**
Promise-based confirm dialog (erstatter SweetAlert2). Vanilla JS, ingen Flowbite afhængighed.

**admin.toastr**
Custom toast-notifikation (erstatter Toastify). Session flash-baseret.

**Bootstrap Carousel**
Bevidst ikke migreret — sjælden brug, Bootstrap carousel JS beholdes som eneste Bootstrap-rest.

**Alpine.js x-collapse**
Erstatter `bootstrap.Collapse` i Collapse-widget og accordion-elementer.

**.admin-input-group CSS klasse**
Custom CSS klasse der erstatter Bootstrap `.input-group` i prepend/append input-patterns
(captcha, rate, daterange felter).

### Vite build output (verificeret)
- **Modules:** 213 transformeret
- **CSS:** ~265 KB (gzip ~45 KB)
- **JS:** ~547 KB (gzip ~163 KB)
- **Docker build-kommando:** `docker run --rm -v /Users/hurup/docker/ziix/admin:/app -w /app node:20-alpine sh -c "node node_modules/.bin/vite build 2>&1"`

---

## Næste skridt

**Opret PR: `tw/staging` → `main`**

Alle 12 steps er merged til `tw/staging`. Migration er komplet.
Opret final pull request fra `tw/staging` til `main`.
