# UI Guidelines

## Goal
Maintain a clean, minimal, and professional Bootstrap-first interface across all Blade pages.

## Design System
- Use Bootstrap 5 components first (`card`, `table`, `form-control`, `form-select`, `btn`, `alert`, `badge`).
- Use shared app classes from `resources/css/app.css` for shell-level consistency (`app-*`, `hris-*`, `card-soft`, `status-*`).
- Keep spacing consistent with Bootstrap spacing utilities (`mb-3`, `gap-2`, `py-4`) and avoid ad-hoc inline styles.

## Typography and Content
- Keep titles short and action-focused.
- Use sentence case for labels and button text.
- Prefer concise helper text under titles and forms.

## Icons
- Use Bootstrap Icons only.
- Prefer semantic icons that communicate action/state:
  - View: `bi-eye`
  - Edit: `bi-pencil`
  - Delete: `bi-trash`
  - Back: `bi-arrow-left`
  - Success/Warning/Error: `bi-check-circle-fill`, `bi-exclamation-triangle-fill`
- Do not use emoji in UI text, actions, or navigation.

## Layout
- Authenticated pages should render through the shared shell:
  - `resources/views/layouts/app.blade.php`
  - `resources/views/layouts/partials/sidebar.blade.php`
- Auth pages should render through:
  - `resources/views/layouts/guest.blade.php`

## Forms
- Use `form-label`, `form-control`, `form-select`.
- Show validation messages consistently through shared components (`x-input-error`, `x-alert`).
- Keep primary action at the end of forms with secondary cancel action nearby.

## Tables and Lists
- Use `table`, `table-striped`, `table-hover`, `align-middle`.
- Keep action buttons compact and icon-based (`btn-sm`, `btn-icon`).
- Ensure table actions include `aria-label` for accessibility.

## Accessibility
- Ensure interactive elements have descriptive labels.
- Keep color contrast readable; do not rely on color alone for status.
- Use proper heading order (`h1` once per page, then `h2` sections).

## Reusable Components
Prefer shared components before creating new one-off markup:
- Buttons: `x-primary-button`, `x-secondary-button`, `x-danger-button`, `x-button`
- Alerts: `x-alert`
- Inputs: `x-text-input`, `x-input-label`, `x-input-error`

## Do / Do Not
- Do keep pages visually aligned with existing dashboard/forms/tables patterns.
- Do keep classes readable and grouped by structure then spacing.
- Do not reintroduce Tailwind-only utility-heavy patterns in new templates.
- Do not duplicate layout chrome inside page-level templates.
