# How I Built It — Northline Apparel WooCommerce Demo
A step-by-step guide to rebuild this store yourself on your laptop with **LocalWP**. Plan for 4–6 hours the first time. Do one part per sitting if you need to.

---

## Part 1 — Create the site in LocalWP
1. Install **Local** from https://localwp.com.
2. Click **+ → Create a new site** → name it `northline-apparel`.
3. Choose **Custom** → PHP 8.2 or newer, **Web server: Apache** (so `.htaccess` works later), MySQL default.
4. Set your admin username/password → **Add Site**.
5. Click **WP Admin** to log in.

## Part 2 — Theme and plugins
1. **Appearance → Themes → Add New** → install and activate **Hello Elementor**.
2. **Plugins → Add New**, install and activate:
   - **WooCommerce**
   - **Elementor**
   - **Limit Login Attempts Reloaded**
3. Delete **Hello Dolly** and **Akismet** if you don't need them.
4. When WooCommerce shows the setup wizard, you can skip it. We set things manually below.

## Part 3 — WooCommerce basic settings
1. **WooCommerce → Settings → General**: store address (any demo address), currency **USD**.
2. **WooCommerce → Settings → Site visibility**: set to **Live**. Otherwise visitors see a "Coming soon" page.
3. **Shipping → Add zone**, or edit "Locations not covered by your other zones", and add **Free shipping**.
4. **Payments**: enable **Cash on delivery** and rename it "Cash on delivery (demo)". On a real client store you would set up Stripe or PayPal here.
5. **Settings → Permalinks** → **Post name** → Save. This gives clean links like `/product/ridge-pullover-hoodie/`.
6. Check that **Pages** has Shop, Cart, Checkout and My account. WooCommerce creates them automatically.

## Part 4 — Product attributes and categories
1. **Products → Attributes** → add **Size**, then click *Configure terms* → add `S, M, L, XL`.
2. Add attribute **Color** → terms `Navy, Sand, Forest, Charcoal, Oat, Rust`.
   *Why global attributes?* They can be reused across products and used for filters later.
3. **Products → Categories** → add **Tops, Outerwear, Bottoms, Accessories**, and give each a thumbnail image.

## Part 5 — Product images
I drew simple vector clothing illustrations (SVG) and turned them into 1000×1000 JPGs, one per color. You can:
- Reuse my images from `assets/products/` in this folder (they are original, safe to use), or
- Make your own in **Canva**: square 1000×1000, light background, product centered.
Never use photos from real brands' websites. Use your own, AI-generated, or CC0 images only (for example from Unsplash, Pexels or rawpixel's public-domain section).
Keep every product image the same size and background. That's what makes a shop grid look professional.

## Part 6 — Create the products
**Simple product** (Canvas Market Tote, $34; Merino Socks, $18 with a $15 sale price):
1. **Products → Add New** → title, description, short description.
2. Product data = **Simple product** → Regular price (and Sale price).
3. Inventory → SKU (e.g. `NL-TOTE`). Pick the category and set the **Product image** → Publish.

**Variable product** (Harbor Crew Tee, $28, sizes S–XL × colors Navy/Sand/Forest):
1. Product data = **Variable product**.
2. **Attributes** tab → add **Size** → *Select all*. Tick **Used for variations**. Add **Color** → pick Navy, Sand, Forest → tick **Used for variations** → **Save attributes**.
3. **Variations** tab → **Generate variations** (this makes 12 combinations).
4. Bulk action → **Set regular prices** → 28.
5. Open each color's variations and set the **variation image** to the matching color photo. Now the main image changes when a shopper picks a color.
6. Set **Default form values** (e.g. M / Navy) → Update.
7. Tick **★ Featured** (Products list → star icon) for 4 products. The homepage grid shows these.

My 8 products: Harbor Crew Tee ($28), Ridge Pullover Hoodie ($64, on sale for $54), Trailhead Utility Jacket ($129), Everyday Joggers ($52, sizes only), Coastline Beanie ($22, colors only), Classic 6-Panel Cap ($26, colors only), Canvas Market Tote ($34), Merino Crew Socks 3-Pack ($18, on sale for $15).

## Part 7 — Homepage in Elementor
1. **Pages → Add New** → "Home" → **Edit with Elementor**.
2. Page Settings (gear icon) → Page Layout: **Elementor Full Width** (keeps the theme header/footer).
3. **Settings → Reading** → static homepage = Home.

**Colors:** Ink `#1E2230`, Rust `#B4583F`, Cream `#F7F3EC`, Sand `#E9DFD0`, Muted text `#6B6457`.
**Fonts:** *DM Serif Display* for headings, *Inter* for text. Set them in **Site Settings → Global Fonts**.

Sections, top to bottom:
1. **Hero** (cream background, 2 columns): small rust eyebrow text "NEW SEASON · CONCEPT DEMO STORE", a big H1, a paragraph, a black **Shop New Arrivals** button (link `/shop/`) and an outlined **Explore Outerwear** button. Right column: an Image widget with the hero collage and 24px rounded corners.
2. **Shop by category** (4 columns): an **Image Box** widget in each, with the category image, title, short text and link (e.g. `/product-category/tops/`). On mobile, set each column's width to **50%** (Advanced → Responsive) so they show 2 per row.
3. **Featured products**: a heading and a **Shortcode** widget:
   `[products limit="4" columns="4" visibility="featured"]`
   (Elementor Free has no Products widget, so the WooCommerce shortcode does the job.)
4. **Benefits strip** (dark ink background, 4 columns): an **Icon Box** in each (truck, undo, leaf, lock) with short titles.
5. **Newsletter** (sand background): heading "Get 10% off your first order", text, and an email box with a button. On a real site, connect it to Mailchimp or MailPoet. In the demo it's only a visual mock-up, with a note saying so.
6. The **footer** comes from the theme (see Part 8).

Shortcut: import `northline-homepage-elementor-template.json` via **Templates → Saved Templates → Import**. After import, re-select the images in the hero and category boxes, because they point to my local site.

## Part 8 — Header, footer, demo notice and store styling
Elementor Free can't build theme headers or footers (that needs Pro). So I used a small **must-use plugin**: `northline-demo-mu-plugin.php` in this folder.
1. In Local: right-click the site → **Go to site folder** → `app/public/wp-content/`.
2. Create a folder `mu-plugins` (if it doesn't exist) and copy `northline-demo-mu-plugin.php` into it.
3. Reload the site. You now get:
   - The rust **CONCEPT DEMO** bar on every page
   - A custom header (logo, menu, cart count) and a 4-column footer
   - Shop grid, product page and cart/checkout styling (rounded images, pill buttons, nice dropdowns)
   - Free shipping / returns badges on product pages
   - The security tweaks from Part 9
Read the file. It's short and commented, and a great way to learn WordPress **hooks** (`wp_body_open`, `wp_footer`, `wp_head`, `woocommerce_single_product_summary`).

## Part 9 — Security hardening
1. In `wp-config.php` (in `app/public`), above `/* That's all, stop editing! */`, add:
   ```php
   define('DISALLOW_FILE_EDIT', true);
   ```
2. **Limit Login Attempts Reloaded**: active with default settings.
3. Add **security headers** to the top of `.htaccess` (Apache):
   ```apache
   <IfModule mod_headers.c>
     Header always set X-Frame-Options "SAMEORIGIN"
     Header always set X-Content-Type-Options "nosniff"
     Header always set Referrer-Policy "strict-origin-when-cross-origin"
     Header always set Permissions-Policy "geolocation=(), camera=(), microphone=()"
   </IfModule>
   <FilesMatch "^(wp-config\.php|xmlrpc\.php|readme\.html|license\.txt)$">
     Require all denied
   </FilesMatch>
   Options -Indexes
   ```
   On nginx, use `nginx-security-snippet.conf` from this folder instead.
4. The mu-plugin also disables XML-RPC, hides the WordPress version, and hides the public user list (`/wp-json/wp/v2/users`).
5. Check it: F12 → Network → reload → click the page → Response Headers should show `x-frame-options` etc. Visit `/xmlrpc.php` and it should be blocked.
6. For a real store, also make sure of: SSL (https), strong admin passwords, daily backups, and keeping WooCommerce updated.

## Part 10 — Test the whole shop flow
1. Open a product → pick size and color → the image should change → **Add to cart**.
2. Go to **Cart** → change quantity → **Proceed to Checkout**.
3. Fill in a test address → **Place Order** (Cash on delivery). Check **WooCommerce → Orders** to see the order.
4. Test on mobile: F12 → device toolbar → 390×844.

## Part 11 — Screenshots and cover for Upwork
1. Chrome F12 → **Ctrl+Shift+P** → "Capture full size screenshot" for the homepage, shop and product pages (window about 1440px wide).
2. Device toolbar 390×844 → full-size screenshots of the homepage and a product page.
3. Cover (1600×1200) in Canva: dark background, "CONCEPT DEMO" badge, the title "WooCommerce Online Store (Demo)", a laptop mockup with the homepage, a phone mockup with the product page, and tool tags.

## Practice next
- Add a **size guide** popup and **product filters** (by color/size) in the shop sidebar.
- Add Stripe in **test mode** and do a test payment.
- Speed: compress images to WebP, add a caching plugin, and check PageSpeed Insights.
