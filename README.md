# Visitor Management System

A custom WordPress theme based on [_tw](https://underscoretw.com) and integrated with Tailwind CSS.

---

## 🚀 Quickstart

### 1. Installation

1. Move this folder to your local WordPress installation under:
   ```
   wp-content/themes
   ```
2. Install dependencies and build assets:
   ```bash
   npm install && npm run dev
   ```
3. Activate this theme via **Appearance → Themes** in your WordPress dashboard.

> **For WordPress Multisite**  
> Make sure to enable this theme via the **Network Admin** before activating on any subsite.

---

### 2. Development

Run the watcher for live updates:

```bash
npm run watch
```

You can now edit your theme files, SCSS/Tailwind classes, and JavaScript. Changes will automatically recompile.

---

### 3. Deployment

1. Build for production:
   ```bash
   npm run bundle
   ```
2. Upload the resulting **ZIP file** via **Appearance → Themes → Add New → Upload Theme**.

For advanced deployment options, see the [_tw deployment docs](https://underscoretw.com/docs/deployment/#h-other-deployment-options).

---

## 📖 Documentation

### Fundamentals
- **[Installation](https://underscoretw.com/docs/installation/)** – How to set up and run your first Tailwind build.
- **[Development](https://underscoretw.com/docs/development/)** – Watching changes, hot reload, and development workflow.
- **[Deployment](https://underscoretw.com/docs/deployment/)** – Best practices for releasing your theme.
- **[Troubleshooting](https://underscoretw.com/docs/troubleshooting/)** – Common issues and solutions.

### In Depth
- **[Using Tailwind Typography](https://underscoretw.com/docs/tailwind-typography/)** – Typographic customization for front-end and back-end.
- **[JavaScript Bundling with esbuild](https://underscoretw.com/docs/esbuild/)** – Using esbuild for fast JS compilation.
- **[Adding Custom Fonts](https://underscoretw.com/docs/custom-fonts/)** – How to self-host or use third-party fonts.
- **[Linting & Formatting](https://underscoretw.com/docs/linting-code-formatting/)** – Keep your code clean and bug-free.
- **[Updating Theme](https://underscoretw.com/docs/updating/)** – Keep your theme up-to-date.

### Extras
- **[On Tailwind and WordPress](https://underscoretw.com/docs/wordpress-tailwind/)** – Understanding the integration.
- **[Styling HTML from Outside the Theme](https://underscoretw.com/docs/styling-html-from-outside-the-theme/)** – Tailwind with plugin-generated markup.
- **[Custom Blocks Styling](https://underscoretw.com/docs/custom-blocks/)** – Tailwind in block editor blocks.
- **[Browsersync Setup](https://underscoretw.com/docs/browsersync/)** – Enable synchronized cross-device live previews.

---

## 🛠 Development Workflow

- Tailwind classes can be added directly to PHP templates, block patterns, or JavaScript-rendered HTML.
- All theme-specific assets are compiled from `/src` into `/dist`.
- Page templates follow the WordPress `page-templates` standard for custom layouts.

---

## 📌 Notes

- This theme is intended for **Visitor Management System** integration but can be adapted for other projects.
- All branding and CSS come from this theme to ensure style consistency across plugins and features.
- Uses **WordPress roles and capabilities** to manage dashboard and access control.

---

**Author:** Wilson Mbuthia 
**License:** GPLv2 or later  
**Version:** 1.0.0
