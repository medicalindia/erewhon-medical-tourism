# Erewhon Architecture & Coding Standards

You are the Lead Developer for the Erewhon Medical Tourism project. This is a custom WordPress theme built on a lightweight, modular foundation. You must follow these strict guidelines for all code generation and file edits.

## 1. CSS & Design Tokens
- **System:** We use a "Token-First" approach inspired by ACSS/Fluid Design.
- **Source of Truth:** All variables must come from `assets/css/tokens.css`.
- **Colors:** We use **OKLCH** exclusively. Do not use hex codes or RGB.
- **Fluidity:** Use the `clamp()` based fluid tokens for typography and spacing (e.g., `var(--space-xl)`, `var(--h1)`).
- **Vanilla Only:** No frameworks (Tailwind, Bootstrap) or pre-processors (SCSS). Write only pure Vanilla CSS.
- **Organization:** - Layouts go in `assets/css/base.css`.
  - Specific components go in their respective files (e.g., `cards.css`, `button.css`).

## 2. WordPress & PHP Templating
- **No Bloat:** Maintain the "Underscores" style of clean, readable PHP.
- **Data Layer:** Keep complex logic and data fetching inside the `/inc/` folder.
- **ACF Pro:** Use `get_field()` for all custom data. Reference `inc/acf-fields.php` for correct field names.
- **Templates:** Page templates (e.g., `page-doctors.php`) should be focused on HTML structure. Use helper functions for data processing.

## 3. Responsive Strategy
- **Modern Standards:** Prioritize **Container Queries** (as seen in `cards.css`) over standard media queries for component-level responsiveness.
- **Layouts:** Use the CSS Grid and Flexbox utilities defined in `base.css` and `utilities.css`.

## 4. Specific Component Rules
- **Pills/Tags:** Use the `.pill` system from `utilities.css`.
- **Cards:** Always use the `.card` base class and its specific modifiers (e.g., `.card--doctor-compact`).
- **Icons:** Use inline SVGs with `currentColor` to match your design tokens.