# 🌐 Laravel Multilingual System (Cookie-Based, No Database)

This project uses a **lightweight, clean multilingual system** based on:

✅ Cookie (1-year persistence)
✅ No database required
✅ Middleware auto-registered via `AppServiceProvider`
✅ Language switch via GET route
✅ Three supported languages: **English, Nepali, Maithili**
✅ Fully compatible with **Blade templates**, **Bootstrap**, **Tailwind**, or any frontend

This document explains **file structure**, **logic flow**, and **usage** so that the system can be maintained or expanded easily in the future.

---

# 📁 File Structure Overview

```
config/
    locales.php
app/
    Http/
        Middleware/
            SetLocale.php
        Controllers/
            LocaleController.php
    Providers/
        AppServiceProvider.php
resources/
    lang/
        en/
            menu.php
        ne/
            menu.php
        mai/
            menu.php
routes/
    web.php
views/
    (language switch dropdown inside your layout/header)
```

---

# ⚙️ 1. Supported Locales (config/locales.php)

This file defines which languages the application supports and the fallback default.

```php
<?php
return [
    'supported' => ['en', 'ne', 'mai'],   // Supported languages
    'default'   => 'en',                  // Default language
];
```

> To add more languages later, simply include them in `'supported'` and create translation files.

---

# 🔧 2. Locale Middleware (app/Http/Middleware/SetLocale.php)

This middleware determines which language to use for the current request.

Priority order:

1. **Cookie** (`locale`)
2. **Browser Accept-Language**
3. **Default locale**

```php
public function handle(Request $request, Closure $next)
{
    $supported = Config::get('locales.supported', ['en']);
    $default   = Config::get('locales.default', config('app.locale'));

    // 1. Cookie
    $cookieLocale = $request->cookie('locale');
    if ($cookieLocale && in_array($cookieLocale, $supported)) {
        App::setLocale($cookieLocale);
        return $next($request);
    }

    // 2. Accept-Language
    $preferred = $request->getPreferredLanguage($supported);
    if ($preferred && in_array($preferred, $supported)) {
        App::setLocale($preferred);
        return $next($request);
    }

    // 3. Default
    App::setLocale($default);
    return $next($request);
}
```

---

# 📌 3. Runtime Middleware Registration (AppServiceProvider)

Instead of editing `Kernel.php`, middleware is pushed into the `web` group here:

```php
public function boot()
{
    $router = $this->app->make(\Illuminate\Routing\Router::class);
    $router->pushMiddlewareToGroup('web', \App\Http\Middleware\SetLocale::class);
}
```

> This keeps your project clean and makes the language system portable.

---

# 🌍 4. Locale Switch Route (routes/web.php)

```php
Route::get('lang/{locale}', [LocaleController::class, 'switch'])
    ->name('lang.switch');
```

---

# 🧭 5. Locale Switching Controller (LocaleController.php)

When the user selects a language:

✔ It validates the language
✔ Stores it in a cookie
✔ Redirects back

```php
Cookie::queue('locale', $locale, 60 * 24 * 365); // 1 year
```

Full method:

```php
public function switch(Request $request, $locale)
{
    $supported = Config::get('locales.supported', ['en']);
    $default   = Config::get('locales.default', config('app.locale'));

    if (! in_array($locale, $supported)) {
        $locale = $default;
    }

    Cookie::queue('locale', $locale, 525600);

    return Redirect::to(url()->previous() ?: route('home'));
}
```

---

# 🖥️ 6. Blade Language Switch Dropdown

Place this in your layout header (Bootstrap or Tailwind friendly):

```blade
<select onchange="location.href='{{ url('lang') }}/' + this.value"
        class="form-select form-select-sm">
    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
    <option value="ne" {{ app()->getLocale() == 'ne' ? 'selected' : '' }}>नेपाली</option>
    <option value="mai" {{ app()->getLocale() == 'mai' ? 'selected' : '' }}>मैथिली</option>
</select>
```

This performs a simple GET to `/lang/{locale}`.

---

# 🗂️ 7. Translation Files (resources/lang)

Each language has its own folder:

```
resources/lang/en/menu.php
resources/lang/ne/menu.php
resources/lang/mai/menu.php
```

Example (English):

```php
<?php
return [
    'home' => 'Home',
    'gallery' => 'Gallery',
    'events' => 'Events',
];
```

Nepali:

```php
<?php
return [
    'home' => 'गृहपृष्ठ',
    'gallery' => 'ग्यालरी',
    'events' => 'कार्यक्रम',
];
```

Maithili:

```php
<?php
return [
    'home' => 'मुख्य पृष्ठ',
    'gallery' => 'गेलरी',
    'events' => 'कार्यक्रम',
];
```

---

# 🧩 8. Using Translations in Views

Just use:

```blade
{{ __('menu.home') }}
{{ __('menu.gallery') }}
{{ __('menu.events') }}
```

Laravel automatically loads the correct language based on cookie → middleware → locale.

---

# 🧪 9. How to Test

1. Open the website
2. Change language from dropdown
3. Page reloads
4. Check browser → Application → Cookies → look for `locale`
5. Navigate pages → language persists
6. Restart browser → still same language

---

# 🚀 10. Maintenance & Adding New Languages

To add a new language:

1. Add its code to `config/locales.php`
2. Create a folder: `resources/lang/{new_locale}`
3. Add translation files
4. Update the language dropdown in Blade

That's it!

---

# 🛠️ 11. Commands You May Need

```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

# 🎉 Done!

Your Laravel project is now fully multilingual with:

* ✔ 3 languages (EN / NE / MAI)
* ✔ Cookie persistence for 1 year
* ✔ No database usage
* ✔ Middleware auto-registration
* ✔ Clean Blade integration
* ✔ Easy to extend anytime

If you ever want **localized URLs** like `/ne/gallery`, ask — I can generate that system too.
