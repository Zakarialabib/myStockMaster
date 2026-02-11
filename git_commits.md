# Git Repository Analysis & Commit Plan

## 1. Repository Status Analysis
The repository currently has a large number of untracked and modified files (>500). These changes span across core infrastructure, new features (Desktop, Cart), UI improvements, and configuration updates.

### Key Observations:
- **New Features**: Desktop application support (NativePHP), advanced cart management, analytics, and finance modules.
- **Infrastructure**: New providers, middleware, and configuration files.
- **UI/UX**: Extensive updates to Livewire components, Blade views, and CSS/JS assets.
- **Testing**: Introduction of Laravel Dusk for browser testing.

## 2. Commit Strategy (Chunks)
To manage the large volume of changes, we will split the commits into logical chunks based on functionality and severity.

### Chunk 1: Git Ignore & Cleanup (Priority: High)
*Purpose*: Ensure no unwanted files are committed.
- **Files**: `.gitignore`

### Chunk 2: Core Infrastructure & Config
*Purpose*: Establish the base for new features.
- **User Points**:
    - Add desktop SQLite database configuration and service provider
    - Add installation check middleware to application bootstrap
    - Update PHPStan configuration to include Carbon extension
- **Files**:
    - `config/*`
    - `app/Providers/*`
    - `app/Http/Middleware/*`
    - `routes/*`
    - `app/Models/*` (Base model updates)

### Chunk 3: Database & Migrations
*Purpose*: Update database schema.
- **User Points**:
    - Add installation completed flag to settings table
- **Files**:
    - `database/migrations/*`
    - `database/seeders/*`

### Chunk 4: Backend Logic (Actions, Services, Traits)
*Purpose*: Implement business logic.
- **User Points**:
    - Add strict types declaration to ProductAttribute model
    - Remove trailing whitespace in Expense model frequency field
    - Implement comprehensive cart exception handling
- **Files**:
    - `app/Actions/*`
    - `app/Services/*`
    - `app/Traits/*`
    - `app/Helpers/*`
    - `app/Console/*`
    - `app/Exceptions/*`

### Chunk 5: Desktop & NativePHP Integration
*Purpose*: Specific files for the desktop application.
- **Files**:
    - `resources/js/desktop.js`
    - `resources/css/desktop.css`
    - `NATIVEPHP_TRANSFORMATION_PLAN.md`

### Chunk 6: Livewire Components & UI (Bulk)
*Purpose*: Front-end interactions and visual updates.
- **User Points**:
    - Fix missing newline at end of file in multiple Livewire components
    - Add name attribute to modal components for Livewire 3 compatibility
    - Replace deprecated CSS classes (flex-shrink → shrink, outline-none → outline-hidden)
    - Add dark mode toggle button to navbar
    - Fix cart quantity update logic in POS interface
    - Improve form input components with better attribute handling
    - Fix date picker and select component styling
- **Files**:
    - `app/Livewire/*`
    - `resources/views/livewire/*`
    - `resources/views/components/*`

### Chunk 7: PDF & Reports
*Purpose*: Reporting templates.
- **User Points**:
    - Fix company name fallback in email templates and PDF reports
- **Files**:
    - `resources/views/pdf/*`

**Step 2: Core Config & Infrastructure**
```bash
git add config/ app/Providers/ app/Http/Middleware/ routes/ app/Models/
git commit -m "feat: add core infrastructure for desktop and advanced cart features"
```

**Step 3: Database**
```bash
git add database/
git commit -m "database: add migrations and seeders for new features"
```

**Step 4: Backend Services**
```bash
git add app/Actions/ app/Services/ app/Traits/ app/Helpers/ app/Console/ app/Exceptions/
git commit -m "feat: implement backend logic for analytics, finance, and cart systems"
```

**Step 5: Desktop Integration**
```bash
git add resources/js/desktop.js resources/css/desktop.css NATIVEPHP_TRANSFORMATION_PLAN.md
git commit -m "feat: add desktop application assets and documentation"
```

**Step 6: UI & Livewire**
```bash
git add app/Livewire/ resources/views/
git commit -m "refactor: update UI components and Livewire logic for desktop support"
```

