# GitHub Pages deployment

This repository is a normal Laravel application. GitHub Pages cannot execute its PHP controllers, Blade templates, database queries, authentication, or payment endpoints.

The Pages workflow builds `static-site/` from `static-site-src/index.html` with:

```bash
npm run build:pages
```

The build copies only the public logo, favicon, and `public/uploads` media into the artifact. All links and media use relative paths, so they work at the project Pages subpath:

`/ukk_Website_hotel_Diageng_Ferdiyanto_XI-RPL-2025-2026/`

The static page supports browsing the featured villas and facilities. These Laravel features remain backend-dependent and are intentionally not faked in the static version:

- login, registration, logout, and user profiles
- database-backed villa search and live availability
- booking submission and reservation forms
- Midtrans payment and payment status pages
- admin and receptionist dashboards
- Laravel Reverb/WebSocket notifications

## Blade view assessment

The public guest views are `guest/homepage.blade.php`, `guest/home.blade.php`, `guest/villa_search.blade.php`, `guest/villa_detail.blade.php`, `guest/home_before.blade.php`, `guest/home_after.blade.php`, `guest/reservation_form.blade.php`, and the payment result views. The first seven require controller data, route generation, or database availability; the payment and reservation views also submit to backend endpoints. They therefore remain Laravel views locally rather than being copied to Pages.

The admin, receptionist, authenticated user, feedback, authentication, payment, and WebSocket views are all backend-dependent. `welcome.blade.php` is the default Laravel welcome page and is not the hotel homepage. The Pages homepage is a purpose-built static representation of the public guest experience, using the same committed public media without exposing Blade or PHP.

The workflow in `.github/workflows/pages.yml` deploys the generated `static-site/` directory on pushes to `main` or `master`. In the repository settings, set **Pages > Build and deployment > Source** to **GitHub Actions**.
