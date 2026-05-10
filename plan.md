# THE DAILY BROADSHEET — News Portal Plan

## Project Name: "The Daily Broadsheet"

## _A nod to classic print newspapers reimagined for the web_

## 1. DESIGN PHILOSOPHY — Handcrafted Aesthetic

The key to looking "NOT AI-made" is **imperfection with intention**:

- **Ink bleeds & textures** — subtle paper grain overlay, slight color variations
- **Deliberate asymmetry** — images intentionally bleed off edges, overlapping elements
- **Mixed typography** — serif + sans-serif clash, intentional sizing inconsistencies
- **Organic spacing** — not perfectly evenly spaced, some sections tighter/wider
- **Hand-crafted details** — subtle underlines, crossed-out text effects, margin notes
- **Motion with weight** — nothing is perfectly smooth; animations have slight bounce/spring
- **Sound design** — subtle page-turn audio, ink-splash effects on interactions

---

## 2. TECH STACK

| Layer            | Technology                                      | Why                                    |
| ---------------- | ----------------------------------------------- | -------------------------------------- |
| Frontend         | HTML5, CSS3, Vanilla JS                         | Full control over design, no bloat     |
| Backend          | PHP 8+ (Vanilla MVC)                            | Simple, reliable                       |
| Database         | MySQL                                           | Already set up with XAMPP              |
| Rich Text Editor | Quill.js                                        | Clean article writing with media embed |
| Media Storage    | Local filesystem + uploads/ dir                 | Simple, fast, easy backup              |
| Excerpt helper   | Deterministic first-N sentences (local)         | No API, no external dependency         |
| i18n             | Custom JSON-based translation files             | Lightweight, flexible                  |
| SEO              | Semantic HTML, JSON-LD structured data, sitemap | Built-in                               |
| Animations       | CSS transitions + Intersection Observer         | No heavy libraries                     |

---

## 3. COLOR PALETTE

| Token            | Hex       | Usage                              |
| ---------------- | --------- | ---------------------------------- |
| `--paper`        | `#F5F0E8` | Main background (warm off-white)   |
| `--paper-dark`   | `#EDE8DF` | Cards, secondary backgrounds       |
| `--ink`          | `#1A1A1A` | Primary text (near-black)          |
| `--ink-light`    | `#4A4A4A` | Secondary text                     |
| `--ink-faded`    | `#8A8A8A` | Muted text, captions               |
| `--accent`       | `#C84B31` | Headlines accent, CTAs (brick red) |
| `--accent-dark`  | `#9A3623` | Hover states                       |
| `--rule`         | `#D4CFC7` | Dividers, borders                  |
| `--highlight`    | `#FFF3B0` | Text highlighting (like marker)    |
| `--dark-overlay` | `#2A2520` | Footer, contrast sections          |

---

## 4. TYPOGRAPHY

| Role                  | Font                      | Why                                  |
| --------------------- | ------------------------- | ------------------------------------ |
| Headlines             | `Playfair Display`        | Classic editorial serif, distinctive |
| Body / Article        | `Lora`                    | Highly readable serif for long-form  |
| UI / Meta / Nav       | `DM Sans`                 | Clean geometric sans for contrast    |
| Accents / Pull Quotes | `Playfair Display Italic` | Ornamental feel                      |
| Code / Data           | `JetBrains Mono`          | Monospace for technical sections     |

## Font sizes use a fluid scale (clamp-based) so they scale smoothly across all devices.

## 5. LAYOUT — Unique & Anti-Stereotype

### Homepage Layout:

┌──────────────────────────────────────────────┐
│ MASTHEAD (logo left, date center, nav right)│
├────────┬─────────────────────────────────────┤
│ │ FEATURED HERO (bleeds right edge) │
│ TRENDING│ image overflows container by 15% │
│ STORIES │ headline on semi-transparent overlay│
│ (sidebar│ │
│ sticky) │─────────────────────────────────────│
│ │ ╔════════════╗ SECONDARY GRID │
│ │ ║ STORY 1 ║ (masonry-like, │
│ │ ╚════════════╝ mixed aspect ratios)│
│ │ ┌─────────┐ ┌─────────┐ │
│ │ │ STORY 2 │ │ STORY 3 │ │
│ │ └─────────┘ └─────────┘ │
├────────┴─────────────────────────────────────┤
│ CATEGORY SECTION: SPORTS (horizontal scroll)│
├──────────────────────────────────────────────┤
│ ╔══════════════════════════════════════════╗ │
│ ║ FULL-BLEED IMAGE + OVERLAPPING TEXT ║ │
│ ║ (image left 60%, text overlaps by 10%) ║ │
│ ╚══════════════════════════════════════════╝ │
├──────────────────────────────────────────────┤
│ PODCAST / VIDEO SECTION │
├──────────────────────────────────────────────┤
│ FOOTER (dark contrast, links in columns) │
└──────────────────────────────────────────────┘

### Key Layout Tricks That Make It Unique:

- **Masthead** — newspaper-style top bar with issue date, weather widget, edition tag
- **Bleeding images** — featured images intentionally overflow their containers
- **Overlapping elements** — text cards overlap images slightly (like magazine cutouts)
- **Sidebar is DEAD** — no right sidebar. Left sidebar for trending ONLY on desktop
- **Horizontal category strips** — scroll horizontally on mobile, side-by-side on desktop
- **Rule lines** — thick/bold horizontal rules as section separators (newspaper style)
- **Margin annotations** — small italic notes in margins (like print newspapers)
- **Numbering sections** — Vol. 1, Issue 42 — gives it a real newspaper identity
- **Pull quotes** — large italic quotes bleed into the margin

### Article Page Layout:

┌──────────────────────────────────────────────┐
│ NAVIGATION │
├──────────────────────────────────────────────┤
│ CATEGORY TAG + READING TIME │
│ │
│ MASSIVE HEADLINE (Playfair, 48-72px) │
│ │
│ by Author Name · Mar 15, 2026 · 4 min read │
│ │
│ ┌─────────────────────────────────────────┐│
│ │ SHARE BUTTONS (sticky left sidebar) ││
│ │ fb tw li wa copy ││
│ └─────────────────────────────────────────┘│
│ │
│ FEATURED IMAGE (full bleed, no radius) │
│ Caption + credit in small italic │
│ │
│ Body text in Lora, 18-20px, max 70ch wide │
│ │
│ > PULL QUOTE (large, bleeds into margin) │
│ │
│ [Inline image, float left, overflow) │
│ Body text continues... │
│ │
│ RELATED ARTICLES (horizontal scroll) │
├──────────────────────────────────────────────┤
│ COMMENTS SECTION │
│ AdSense placeholder below article │
└──────────────────────────────────────────────┘

---

## 6. RESPONSIVE BREAKPOINTS

| Breakpoint | Width       | Layout Changes                                                                                                                      |
| ---------- | ----------- | ----------------------------------------------------------------------------------------------------------------------------------- |
| Mobile     | < 640px     | Single column, horizontal strips become vertical scrolls, pull quotes stack, images full width, sticky share bar becomes bottom bar |
| Tablet     | 640–1024px  | 2-column grid, sidebar hidden, masthead collapses to hamburger                                                                      |
| Desktop    | 1024–1440px | Full layout with left sidebar                                                                                                       |
| Wide       | > 1440px    | Max-width container (1400px), generous margins                                                                                      |

**Key responsive rules:**

- Images always `width: 100%` with `object-fit: cover`
- Typography uses `clamp()` — no breakpoint-specific font sizes
- Touch targets minimum 44x44px on mobile
- Horizontal scrolls use `scroll-snap` for smooth feel
- Animations disabled/reduced on `prefers-reduced-motion`

---

## 7. SEO STRATEGY

### Technical SEO:

- Semantic HTML5 tags (`<article>`, `<header>`, `<nav>`, `<main>`, `<aside>`, `<footer>`)
- Schema.org JSON-LD structured data:
  - `NewsArticle` schema for all article pages
  - `BreadcrumbList` for navigation
  - `Organization` for the site
- Dynamic `sitemap.xml` generated by PHP
- Canonical URLs on all pages
- `robots.txt` with sitemap reference
- Open Graph + Twitter Card meta tags per article

### Content SEO:

- Clean URLs: `/article/category/slug-title`
- Auto-generated meta descriptions from article excerpt (150-160 chars)
- Heading hierarchy: one `<h1>` per page, logical `<h2>`/`<h3>` structure
- Internal linking between related articles
- Image alt text required in admin
- Keyword-aware URL slugs

---

## 8. ADSENSE INTEGRATION

AdSense-friendly placement without ruining UX:
| Placement | Format | Description |
|---|---|---|
| Below hero article | Responsive Leaderboard (728x90) | Immediately visible |
| Within article body | In-article ad after 3rd paragraph | Natural flow |
| Below article | Responsive Rectangle (300x250) | End of content |
| Between grid items | Responsive Rectangle | Visually non-intrusive |
| Mobile sticky bottom | Fixed 320x50 | Always visible on scroll |
| Desktop sidebar | Responsive Vertical (160x600) | Only on category/archive pages |
**AdSense best practices baked in:**

- Reserved ad slots in HTML (commented) — ad slots don't jump when ads load (prevents layout shift → better Core Web Vitals → higher AdSense approval)
- Ads served in `<aside class="ad-container">` wrappers — semantically separated from content
- Lazy-load ads below fold
- No ads on article pages within first 3 paragraphs (violates policy)
- Minimum 3 paragraphs before first in-article ad

---

## 9. MULTI-LANGUAGE SYSTEM

### Architecture:

articles/
├── id: 1
├── slug: "breaking-news"
├── author_id: 1
├── category_id: 2
├── status: "published"
├── featured_image: "..."
├── video_url: "..."
├── audio_url: "..."
├── attachments: ...
├── published_at: "2026-03-15"
└── translations/
├── en: { title, excerpt, body }
└── ne: { title, excerpt, body }

### How it works:

- Admin enters content in all languages simultaneously (side-by-side fields)
- `GET` request carries `?lang=ne` or session cookie stores preference
- PHP selects the right translation from DB
- Fallback to English if translation missing
- Language switcher in header — flag icons + language name
- **URL structure**: `/ne/article/category/slug` for Nepali pages

### Translation Files (UI strings):

```json
{
  "nav.home": { "en": "Home", "ne": "गृहपृष्ठ" },
  "nav.category": { "en": "Category", "ne": "श्रेणी" },
  "article.readmore": { "en": "Read More", "ne": "थप पढ्नुहोस्" }
}
---
10. EXCERPT WORKFLOW — NO API REQUIRED
This is a deterministic excerpt helper — no external API, no paid services, works 100% offline.
How it works:
1. Admin writes article body (500+ words recommended)
2. Clicks "Auto-excerpt" button in admin (optional)
3. PHP takes the first 2-3 sentences and trims to 150-160 chars if needed
4. Admin reviews, edits if needed, saves
Implementation (pure PHP, zero dependencies):
function buildExcerpt(string $text, int $sentences = 3, int $maxChars = 160): string {
    // 1. Clean and normalize text
    // 2. Split into sentences
    // 3. Take first N sentences
    // 4. Trim to maxChars without cutting a word
    // 5. Return as readable excerpt
}
Admin UI:
- "Auto-excerpt" button in article editor
- Shows loading state while processing
- Results appear in excerpt field
- Manual edit always available (never auto-overwrites)
Graceful Degradation:
- If helper fails → show message "Excerpt generation failed. Please write a brief excerpt manually."
- Never breaks the page, never blocks saving
- Admin can always write excerpt manually
---
11. ADMIN PANEL — "The Desk"
A clean, professional editorial dashboard that feels like a newsroom:
Pages:
Page	Features
Dashboard	Today's stats, drafts count, scheduled articles, recent comments
Articles	Full CRUD, drag to reorder featured, bulk publish, filter by status/category/author
New Article	Rich editor, media uploader, excerpt helper, language fields side-by-side, SEO fields, schedule picker, video/audio upload
Media Library	Grid view of all uploads, search, filter by type (image/video/audio/document), drag-drop upload, bulk delete
Categories	Add/edit/delete/reorder categories
Authors	Manage user roles (admin/editor/author), profile editing
Comments	Approve/reject/delete, threaded replies, spam filter
Settings	Site name, logo, social links, AdSense codes, default language
Translations	Manage UI string translations
Admin UX Highlights:
- Media Uploader: Drag-drop zone, shows upload progress, generates thumbnail immediately
- Rich Editor: Quill.js — bold, italic, links, headings, image embed, video embed
- Image Gallery Picker: Browse and select from media library within editor
- Video Support: Upload MP4 or embed YouTube/Vimeo URLs
- Document Attachments: PDF, DOC upload for downloadable resources
- Keyboard shortcuts: Ctrl+S to save, Ctrl+Shift+P to publish
- Auto-save: Drafts save every 30 seconds automatically
- Version history: Can see previous versions of article
---
12. FILE UPLOAD HANDLING
Storage Structure:
uploads/
  ├── articles/
  │   ├── featured/{article_id}/ (original + thumbnails)
  │   ├── gallery/{article_id}/
  │   ├── video/{article_id}/
  │   ├── audio/{article_id}/
  │   └── documents/{article_id}/
  └── media_library/
      ├── images/
      ├── videos/
      ├── audio/
      └── documents/
Upload Rules:
Type
Images
Videos
Audio
Documents
PHP Processing:
- Validate file type via MIME + extension (double-check)
- Sanitize filename (random prefix + slug)
- Auto-generate thumbnails via GD (no Imagick needed)
- Store file path in DB with metadata (size, MIME, dimensions)
- Multi-file upload supported per article
---
13. IMPLEMENTATION PHASES
Phase 1 — Foundation
- [ ] Set up PHP MVC folder structure
- [ ] Create MySQL database schema (all tables)
- [ ] Build auth system (admin login with bcrypt)
- [ ] Set up asset pipeline (CSS custom properties, JS modules)
Phase 2 — Admin Panel (The Newsroom)
- [ ] Admin dashboard with stats cards
- [ ] Article CRUD with Quill.js rich editor
- [ ] Media library with drag-drop upload + GD thumbnails
- [ ] Video/audio/document upload per article
- [ ] Category management (drag to reorder)
- [ ] Author management (roles: admin/editor/author)
Phase 3 — Frontend Core (The Paper)
- [ ] Master layout (masthead, nav, footer)
- [ ] Homepage with hero + grid layouts
- [ ] Article page (full reader experience)
- [ ] Category pages
- [ ] Author pages
- [ ] 404 page
Phase 4 — Polish & Uniqueness (The Soul)
- [ ] Paper texture overlay + warm color palette
- [ ] Typography implementation (Playfair/Lora/DM Sans via Google Fonts)
- [ ] All unique layout tricks (bleeding images, overlapping text, margin notes)
- [ ] Scroll-triggered reveal animations (Intersection Observer)
- [ ] Dark/light mode toggle
- [ ] Horizontal category strips
- [ ] Pull quote component
- [ ] Newsletter signup form
Phase 5 — SEO & AdSense (The Revenue)
- [ ] Semantic HTML5 structure
- [ ] JSON-LD structured data (NewsArticle, BreadcrumbList, Organization)
- [ ] Dynamic sitemap.xml generation
- [ ] Open Graph + Twitter Card meta per article
- [ ] AdSense slot integration (reserved slots, lazy-load)
- [ ] Page speed optimization (lazy images, minimal CSS)
Phase 6 — Multi-language (The Reach)
- [ ] i18n architecture with JSON translation files
- [ ] English + Nepali content entries in admin
- [ ] Language switcher + URL routing (/ne/ prefix)
- [ ] Translated meta tags (title, description)
- [ ] Translated UI strings
Phase 7 — Excerpts (The Clarity)
- [ ] Deterministic excerpt helper (first sentences)
- [ ] "Auto-excerpt" button in admin article editor
- [ ] Auto-fill excerpt field with excerpt
- [ ] Graceful fallback if processing fails
Phase 8 — Features & Community (The Life)
- [ ] Breaking news ticker (auto-scrolling marquee)
- [ ] Infinite scroll on homepage/category pages
- [ ] Real-time search with instant results
- [ ] Social sharing buttons (sticky sidebar)
- [ ] Comments system (approve/reject workflow)
- [ ] Related articles (by category + tags)
- [ ] Reading time calculator
- [ ] Sound effects toggle (Web Audio API)
Phase 9 — Launch
- [ ] AdSense application
- [ ] Performance audit (Lighthouse)
- [ ] Mobile testing on real devices
- [ ] DNS + hosting deployment
---
14. SOUND DESIGN (Subtle but Optional)
Interaction
Page load
Button click
Article open
Image expand
Error
Sounds are OFF by default. Toggle in footer. Uses Web Audio API — no file downloads needed.
---
15. ANIMATION PHILOSOPHY
- Nothing is perfectly linear — use cubic-bezier(0.34, 1.56, 0.64, 1) for subtle bounce
- Scroll-triggered reveals — articles fade up with staggered delays
- Image hover — slight scale + shadow lift (not boring zoom)
- Link hovers — custom animated underline (drawn via CSS)
- Pull quotes — slide in from the side they bleed into
- Reduced motion respected — all animations wrapped in @media (prefers-reduced-motion: no-preference)
- Page transitions — subtle fade between pages
---
16. DATABASE SCHEMA
-- Users (authors, admins)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'author') DEFAULT 'author',
    avatar VARCHAR(255),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) UNIQUE NOT NULL,
    name_en VARCHAR(100) NOT NULL,
    name_ne VARCHAR(100),
    color VARCHAR(7),
    sort_order INT DEFAULT 0
);
-- Articles
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(200) UNIQUE NOT NULL,
    author_id INT NOT NULL,
    category_id INT NOT NULL,
    status ENUM('draft', 'published', 'scheduled', 'archived') DEFAULT 'draft',
    featured BOOLEAN DEFAULT FALSE,
    view_count INT DEFAULT 0,
    featured_image VARCHAR(255),
    video_url VARCHAR(500),
    audio_url VARCHAR(500),
    attachments JSON,
    scheduled_at DATETIME,
    published_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
-- Article Content (per language)
CREATE TABLE article_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    lang VARCHAR(5) DEFAULT 'en',
    title VARCHAR(300) NOT NULL,
    excerpt TEXT,
    body LONGTEXT NOT NULL,
    meta_title VARCHAR(300),
    meta_desc VARCHAR(160),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    UNIQUE KEY unique_article_lang (article_id, lang)
);
-- Article Media
CREATE TABLE article_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    type ENUM('image', 'video', 'audio', 'document') NOT NULL,
    filename VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255),
    caption VARCHAR(500),
    sort_order INT DEFAULT 0,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);
-- Tags
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_ne VARCHAR(100),
    slug VARCHAR(100) UNIQUE NOT NULL
);
-- Article Tags (pivot)
CREATE TABLE article_tags (
    article_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);
-- Comments
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    user_name VARCHAR(100),
    email VARCHAR(100),
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'spam') DEFAULT 'pending',
    parent_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE
);
-- Site Settings
CREATE TABLE site_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT
);
-- Insert default admin
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin', 'admin@dailybroadsheet.com', '$2y$10$...', 'admin');
---
17. FOLDER STRUCTURE
daily-broadsheet/
├── backend/
│   ├── config/
│   │   └── database.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── ArticleController.php
│   │   ├── CategoryController.php
│   │   ├── MediaController.php
│   │   ├── CommentController.php
│   │   ├── UserController.php
│   │   └── SettingsController.php
│   ├── models/
│   │   ├── User.php
│   │   ├── Article.php
│   │   ├── Category.php
│   │   ├── Tag.php
│   │   ├── Comment.php
│   │   └── Setting.php
│   ├── routes/
│   │   ├── api.php
│   │   ├── admin.php
│   │   └── web.php
│   ├── services/
│   │   ├── SummarizerService.php
│   │   ├── UploadService.php
│   │   └── SitemapService.php
│   ├── middleware/
│   │   └── AuthMiddleware.php
│   ├── utils/
│   │   ├── i18n.php
│   │   └── helpers.php
│   ├── public/
│   │   └── index.php
│   └── .htaccess
├── frontend/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── main.css
│   │   │   ├── components.css
│   │   │   ├── admin.css
│   │   │   └── animations.css
│   │   ├── js/
│   │   │   ├── main.js
│   │   │   ├── animations.js
│   │   │   ├── search.js
│   │   │   ├── sounds.js
│   │   │   └── admin/
│   │   │       ├── editor.js
│   │   │       ├── uploader.js
│   │   │       └── dashboard.js
│   │   └── fonts/
│   ├── pages/
│   │   ├── index.php
│   │   ├── article.php
│   │   ├── category.php
│   │   └── author.php
│   └── components/
│       ├── header.php
│       ├── footer.php
│       ├── masthead.php
│       └── sidebar.php
├── admin/
│   ├── assets/
│   │   ├── css/
│   │   │   └── admin.css
│   │   └── js/
│   │       ├── article.js
│   │       └── media.js
│   ├── index.php
│   ├── articles.php
│   ├── article-edit.php
│   ├── media.php
│   ├── categories.php
│   ├── comments.php
│   ├── settings.php
│   ├── translations.php
│   └── login.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── uploads/
│   ├── articles/
│   │   ├── featured/
│   │   ├── gallery/
│   │   ├── video/
│   │   ├── audio/
│   │   └── documents/
│   └── media_library/
│       ├── images/
│       ├── videos/
│       ├── audio/
│       └── documents/
├── translations/
│   ├── en.json
│   └── ne.json
├── database/
│   └── schema.sql
├── robots.txt
├── sitemap.xml
├── .env
└── README.md
---
18. KEY UNIQUE TOUCHES SUMMARY
Element
Masthead
Bleeding images
Overlapping cards
Margin notes
Paper texture
Pull quotes
Rule lines
Horizontal strips
Sound effects
Warm palette
Bounce easing
Organic spacing
Hand-drawn details
Volume/Issue numbering
---
19. AI SUMMARIZER — FULL PHP IMPLEMENTATION
Place in backend/services/SummarizerService.php:
class SummarizerService {
    public function summarize(string $text, int $sentences = 3): string {
        $sentences = $this->splitIntoSentences($text);
        if (count($sentences) <= $sentences) {
            return $text;
        }
        $scored = $this->scoreSentences($sentences);
        $top = array_slice($scored, 0, $sentences);
        usort($top, fn($a, $b) => $a['index'] - $b['index']);
        return implode(' ', array_column($top, 'text'));
    }
    private function splitIntoSentences(string $text): array {
        $text = preg_replace('/\s+/', ' ', trim($text));
        $sentences = preg_split('/(?<=[.!?])\s+/', $text);
        return array_filter($sentences, fn($s) => strlen($s) > 10);
    }
    private function scoreSentences(array $sentences): array {
        $allWords = [];
        foreach ($sentences as $s) {
            $words = $this->getWords($s);
            foreach ($words as $w) { $allWords[] = strtolower($w); }
        }
        $freq = array_count_values($allWords);
        $maxFreq = max($freq);
        $scores = [];
        foreach ($sentences as $i => $s) {
            $words = $this->getWords($s);
            $score = 0;
            foreach ($words as $w) {
                $w = strtolower($w);
                if (isset($freq[$w])) {
                    $score += $freq[$w] / $maxFreq;
                }
            }
            $scores[] = ['text' => $s, 'score' => $score, 'index' => $i];
        }
        usort($scores, fn($a, $b) => $b['score'] - $a['score']);
        return $scores;
    }
    private function getWords(string $text): array {
        preg_match_all('/\b[a-zA-Zà-žअ-ह]+/', $text, $matches);
        return $matches[0];
    }
}
---
## 20. FREE AI ALTERNATIVE — NewsAPI Summaries
If you want real AI summaries without paying, use **news API sources** that provide summaries:
1. **NewsData.io Free Tier** — fetch articles + their AI-generated summaries
2. **Free News API** — aggregator with summarized content
3. **GNews API Free Tier** — news with descriptions
Or use **ollama** (self-hosted LLM) if you have a decent GPU — runs Llama3 locally, zero API cost.
---
```
