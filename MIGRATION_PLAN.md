# Migration Plan: Bootstrap → Tailwind CSS + Alpine.js

## Scope

Full replacement of Bootstrap 5, ChoicesJS, SweetAlert2, Toastify og npm/sass-pipeline med:

| Fra | Til |
|-----|-----|
| Bootstrap 5 | Tailwind CSS v4 + Flowbite |
| Bootstrap JS (dropdowns, modals) | Alpine.js |
| ChoicesJS | Tom Select |
| SweetAlert2 | Flowbite Modal |
| Toastify | Flowbite Toast |
| npm + sass watch | Vite (laravel-vite-plugin) |

**Beholdes uændret:** Flatpickr, Leaflet, Coloris, Font Awesome, SortableJS, NProgress.

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
| F1 Build verify | `tw/f1-build-verify` | ⬜ |
| F2 Base layout | `tw/f2-base-layout` | ⬜ |
| F3 Form core | `tw/f3-form-core` | ⬜ |
| F4 Form advanced | `tw/f4-form-advanced` | ⬜ |
| F5 Grid views | `tw/f5-grid` | ⬜ |
| F6 Show & Tree | `tw/f6-show-tree` | ⬜ |
| F7 Dashboard & Actions | `tw/f7-dashboard-actions` | ⬜ |
| F8 Auth & Cleanup | `tw/f8-auth-cleanup` | ⬜ |

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

### Step B4 — Notifications & action dialogs (branch: `tw/b4-notifications`)

**Mål:** Erstat SweetAlert2 og Toastify med Flowbite-baserede løsninger. Opdater helpers.

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

### Step F6 — Show page og Tree (branch: `tw/f6-show-tree`)

**Mål:** Show-sider og tree-views i Tailwind + Flowbite.

#### Task F6.1 — Show page panel og field templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f6-show-tree
Filer: resources/views/show/panel.blade.php, show/field.blade.php,
       resources/views/show/divider.blade.php

Panel: Flowbite card komponent
<div class="bg-white rounded-lg shadow p-6 mb-4">
  <h5 class="text-lg font-semibold mb-4">{{ $title }}</h5>
  <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">

Field (description list):
<dt class="text-sm font-medium text-gray-500">{{ $label }}</dt>
<dd class="text-sm text-gray-900">{{ $value }}</dd>

RTL: dl bruger rtl:text-right via dir="rtl" på parent
```

#### Task F6.2 — Show relation og tools templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f6-show-tree
Filer: resources/views/show/relation.blade.php, show/tools.blade.php

1. Relation: embedded mini-tabel (Flowbite table, kompakt) under show-panel
2. Tools: Edit-knap og Delete-knap i Tailwind button-styling (øverst på siden)
3. Delete: Flowbite confirm modal (erstat SweetAlert2)
4. Back-knap: Tailwind outline button
5. Sikr at show displayers (alle Show\Field subklasser) renderes korrekt i ny template
```

#### Task F6.3 — Tree view templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f6-show-tree
Filer: resources/views/tree/ (alle filer)

1. Tree container: Tailwind nested ul/li struktur
2. Node: Tailwind flex items-center gap-2 med expand/collapse knap
3. Alpine.js x-data="{ open: true }" per node til expand/collapse animation
4. Drag-drop: SortableJS bevares, Tailwind klasser til drag-over state
5. Tree actions (edit/delete per node): Flowbite dropdown eller inline knapper
```

---

### Step F7 — Dashboard, widgets og action views (branch: `tw/f7-dashboard-actions`)

**Mål:** Dashboard, widgets og action-dialogs i Tailwind + Flowbite.

#### Task F7.1 — Dashboard og widget templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f7-dashboard-actions
Filer: resources/views/dashboard/*.blade.php,
       resources/views/widgets/*.blade.php (11 filer)

Dashboard:
1. Responsive grid layout: Tailwind grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4
2. Stat card widget: Flowbite card med ikon, titel og tal
3. Info box: farvet Flowbite card

Widgets:
1. Box widget → Flowbite card
2. InfoBox widget → Flowbite info card med farvet sidebar
3. Collapse widget → Alpine.js x-collapse
4. Table widget → kompakt Flowbite tabel
```

#### Task F7.2 — Action dialog og interactor templates

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f7-dashboard-actions
Filer: resources/views/actions/ (alle filer)

1. Confirm dialog: Flowbite modal med titel, body, confirm + cancel knapper
2. Form dialog (Interactor\Form): Flowbite modal med form fields indeni
3. Response handling: AJAX success → Flowbite toast, fejl → Flowbite alert i modal
4. Alpine.js x-data til modal open/close state
5. Batch action form dialog: Flowbite modal med dynamisk form content
```

#### Task F7.3 — PJAX og NProgress integration

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f7-dashboard-actions
Filer: resources/js/pjax.js (eller tilsvarende), resources/views/partials/

1. NProgress: bevares, verificér at start/done kaldes korrekt ved PJAX navigation
2. PJAX container: opdater CSS-selectors i JS fra Bootstrap-specifikke selectors
3. After-PJAX reinit: sikr at Alpine.js komponenter reinitaliseres efter PJAX load
   (Alpine.$nextTick eller custom init events)
4. Tom Select: reinitialiser Tom Select efter PJAX navigation
5. Flatpickr: reinitialiser Flatpickr instances efter PJAX navigation
```

---

### Step F8 — Auth management sider og oprydning (branch: `tw/f8-auth-cleanup`)

**Mål:** Auth-admin-sider i nyt design. Komplet fjernelse af Bootstrap.

#### Task F8.1 — Auth admin sider (users, roles, permissions, menu)

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f8-auth-cleanup

Auth-siderne bruger standard Grid + Form templates og burde fungere automatisk
efter F3-F6 er merged. Denne task er en integrations-test:

1. Test /admin/auth/users (liste + opret + rediger + slet)
2. Test /admin/auth/roles (liste + opret + rediger + slet + tilknyt permissions)
3. Test /admin/auth/permissions (liste + opret + rediger + slet)
4. Test /admin/auth/menu (liste + drag-sort + opret + rediger + slet)
5. Test /admin/auth/logs (liste + filter + slet)

Dokumentér eventuelle rendering-fejl og ret dem.
```

#### Task F8.2 — RTL fuld test

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f8-auth-cleanup

1. Sæt app locale til 'ar' (Arabisk)
2. Gennemgå alle sidetyper: login, dashboard, grid, form, show, tree
3. Verificér at sidebar er på højre side (rtl:right-0)
4. Verificér at Tom Select dropdown åbner korrekt i RTL
5. Verificér at Flatpickr kalender vises korrekt i RTL
   (flatpickr RTL support via locale)

Fix alle RTL-layout-problemer med Tailwind rtl: varianter.
```

#### Task F8.3 — Responsivt design test

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f8-auth-cleanup

1. Test alle sidetyper på mobile (375px), tablet (768px) og desktop (1280px)
2. Verificér sidebar hamburger-menu virker på mobile
3. Verificér grid tabel er scrollbar horisontalt på mobile
4. Verificér form fields stabler korrekt på mobile (single kolonne)
5. Fix responsive problemer med Tailwind breakpoint klasser (sm:, md:, lg:)
```

#### Task F8.4 — Fjern Bootstrap og gamle dependencies

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f8-auth-cleanup

1. npm uninstall bootstrap (og evt. @popperjs/core)
2. npm uninstall sweetalert2 toastify-js choices.js
3. Slet resources/assets/bootstrap/, resources/assets/choices/,
   resources/assets/sweetalert2/, resources/assets/toastify/
4. Grep hele codebase efter 'bootstrap', 'choices', 'sweetalert', 'toastify'
   og fjern resterende referencer
5. Kør npm run build — verificér 0 errors, mål bundle-størrelse
```

#### Task F8.5 — Final test og CHANGELOG opdatering

```
Repo: /Users/hurup/docker/ziix/admin
Branch: tw/f8-auth-cleanup

1. Kør komplet PHPUnit test-suite: vendor/bin/phpunit
2. Ret eventuelle test-fejl (primært HTML-selector tests)
3. Manuel smoke test: login → dashboard → opret record → rediger → slet
4. Opdater CHANGELOG.md med ny version entry for Tailwind migration
5. Opdater README.md med ny frontend stack beskrivelse og build-instruktioner
```

---

## Starter prompt til første step

Kopier denne prompt til en ny Claude Code-session for at starte Step B1:

```
Du skal udføre Step B1 (Asset Pipeline Migration) i ZiiX Admin projektet.

Projekt: /Users/hurup/docker/ziix/admin
Branch-strategi: Opret ALTID en ny branch før du laver ændringer.

OPRET BRANCH: tw/b1-asset-pipeline fra main-branchen.

Stack der skal sættes op:
- Vite (laravel-vite-plugin) erstatter npm sass-pipeline
- Tailwind CSS v4 + @tailwindcss/vite plugin
- Alpine.js + @alpinejs/collapse + @alpinejs/focus
- Flowbite (Tailwind komponentbibliotek med Alpine.js)
- Tom Select (erstatter ChoicesJS)
- Beholdes uændret: Flatpickr, Leaflet, SortableJS, NProgress, Font Awesome

Udfør disse 5 tasks i rækkefølge (én ad gangen, verificér hver før næste):

TASK B1.1: Opdater package.json
- Fjern: sass, bootstrap, choices.js, sweetalert2, toastify-js
- Tilføj devDependencies: vite, laravel-vite-plugin, tailwindcss@4, @tailwindcss/vite, autoprefixer, postcss
- Tilføj dependencies: alpinejs, @alpinejs/collapse, @alpinejs/focus, flowbite, tom-select
- Behold: flatpickr, leaflet, sortablejs, nprogress, @fortawesome/fontawesome-free
- Opdater scripts: "dev": "vite", "build": "vite build" (fjern sass script)
- Opret vite.config.js med laravel-vite-plugin, input: ['resources/css/app.css', 'resources/js/app.js']
- Opret tailwind.config.js med content-paths for resources/views/**/*.blade.php og src/**/*.php, og flowbite plugin
- Opret postcss.config.js med tailwindcss og autoprefixer

TASK B1.2: Opret resources/css/app.css og resources/js/app.js
- app.css: @import 'tailwindcss' + @import 'flowbite' + CSS-variabler for sidebar (--sidebar-width: 16rem) + RTL support (:root [dir="rtl"])
- app.js: import og window-eksponering af Alpine.js (med Collapse + Focus plugins), Flowbite, TomSelect, flatpickr, NProgress, Sortable. Kald Alpine.start()

TASK B1.3: Opdater src/AdminServiceProvider.php
- Find Bootstrap/gamle asset-registreringer
- Fjern registrering af bootstrap.css, bootstrap.js, choices.js, sweetalert2.js, toastify.js
- Tilføj Vite asset-loading for app.css og app.js

TASK B1.4: Opdater src/Traits/HasAssets.php
- Fjern Bootstrap og erstattede libs fra $defaultStyles og $defaultScripts arrays
- Tilføj Vite-baseret loading

TASK B1.5: Verifikation
- Kør npm install && npm run build
- Verificér at output genereres uden fejl
- Rapportér bundle-størrelse

Når alle 5 tasks er udført, lav en git commit på tw/b1-asset-pipeline med besked:
"feat: migrate build pipeline from Bootstrap/sass to Vite + Tailwind CSS v4 + Alpine.js + Flowbite"
```
