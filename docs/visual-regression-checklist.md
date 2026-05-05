# Visual Regression Checklist

## Scope
Run this checklist after UI updates to Blade templates, shared components, or `resources/css/app.css`.

## Test Accounts
- Admin
- HR
- Manager
- Employee

## Global Checks
- App shell renders correctly on desktop and mobile (sidebar, topbar, offcanvas behavior).
- No emoji appears in navigation, buttons, empty states, or headings.
- Bootstrap Icons render correctly (no missing glyphs).
- Flash alerts (`success`, `error`) display with consistent style.
- No layout overflow or clipped content at common breakpoints.

## Auth Screens
- Welcome page
- Login
- Register
- Forgot password
- Reset password
- Confirm password
- Verify email

Verify:
- Form spacing and labels are consistent.
- Error and status alerts are visible and readable.
- Primary and secondary actions are visually distinct.

## Dashboard and Core Modules
- Dashboard cards and activity panels
- Employees (index, create, edit, show)
- Departments (index, create, edit, show)
- Attendance index
- Leave requests (index, create, show)
- Profile page and profile partial sections

Verify:
- Cards, tables, and forms use consistent spacing and border styles.
- Status badges are readable and semantically colored.
- Action buttons are aligned and accessible.
- Empty states show icon + clear message.

## Reports
- Reports index
- Employee report
- Attendance report
- Leave report
- Department report
- Activity report

Verify:
- Summary stats align in grid correctly.
- Section headers and subtitles remain consistent.
- Tables and cards render without color/theme mismatches.

## Modal/Dropdown Checks
- Delete account modal opens/closes and traps focus correctly.
- Dropdowns position correctly and close on outside click.

## Browser/Viewport Pass
- Desktop: 1366x768 and 1920x1080
- Tablet: 768x1024
- Mobile: 390x844

## Build and Verification
1. Run `npm run build`.
2. Confirm no Blade diagnostics in modified files.
3. Spot-check role-based navigation visibility per account.
4. Capture before/after screenshots for changed pages.
