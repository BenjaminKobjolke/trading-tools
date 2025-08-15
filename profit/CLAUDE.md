# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Setup and Installation
```bash
composer install          # Install PHP dependencies
php -S localhost:8000     # Start development server
```

### No Build Process
This is a vanilla PHP application with no build step, bundling, or compilation required. Changes to PHP, CSS, and JS files are immediately reflected.

## Architecture Overview

### MVC-Style Structure
The application follows a clean separation of concerns:

- **Entry Point**: `index.php` - Bootstraps the application, handles session management
- **Controller Layer**: `src/Controller.php` - Processes form submissions, coordinates between components
- **Business Logic**: `src/Calculator.php` - Core financial calculations for compound interest
- **Validation**: `src/InputValidator.php` - Handles number format parsing (German/English) and validation
- **Internationalization**: `src/LanguageManager.php` - JSON-based translation system with session persistence
- **Templates**: Twig-based templating in `templates/` directory

### Key Application Features

**Financial Calculations**:
- Compound interest with configurable periods (daily, weekly, monthly, custom days)
- Multi-year projections (1-10 years) with card-based results display
- German tax calculations (25% Abgeltungssteuer with €1000 Sparerpauschbetrag)
- Monthly contribution support with proper annuity calculations
- Year-end vs full-year calculation modes

**Number Format Handling**:
- Auto-detects German (1.234,56) vs English (1,234.56) number formats
- Throws specific validation errors for ambiguous formats like "1,2.3"
- Session persistence preserves exact user input on validation errors

**Multi-language Support**:
- German/English translations stored in `lang/*.json` files
- Language preference persists in session
- All UI text is translatable including form labels, error messages, and results

### Form Data Flow

1. **Input Processing**: Controller receives POST data, uses InputValidator to parse numbers with locale awareness
2. **Session Persistence**: All form values automatically saved to session for page reloads
3. **Calculation**: Calculator class handles all financial computations including tax implications
4. **Result Display**: Twig templates render results in consistent card-based layout
5. **Auto-scroll**: JavaScript automatically scrolls to results using URL fragments (#results)

### Template Structure

**Base Layout**: `templates/base.html.twig` provides dark theme, Bootstrap 5, language switcher
**Calculator UI**: `templates/calculator.html.twig` contains form, results cards, and multi-year projections

Results are displayed as 4-card layouts:
- Total Investment (blue) - Amount invested including contributions
- Final Amount (green) - Gross return before taxes  
- Tax Amount (yellow) - German capital gains tax deduction
- Final After Tax (blue) - Net result after taxes

### Error Handling

The application validates:
- Number format ambiguity (e.g., "1,2.3" triggers specific error)
- Input ranges (negative amounts, rates below -100%)
- Custom period validation (1-365 days)
- Monthly contribution validation

### Data Persistence

**Session Storage**: Form values, language preference, and calculation state persist across page reloads
**No Database**: Application is stateless except for session data

## Important Implementation Notes

### Twig Template Limitations
- Cannot use PHP functions like `pow()` or `max()` in templates
- All complex calculations must be done in PHP classes and passed to templates
- Use ternary operators instead of `max()/min()` functions in Twig

### Financial Calculation Accuracy
- The Calculator class uses proper compound interest formulas
- Monthly contributions use annuity calculations with period-specific rates
- Multi-year projections correctly compound over time periods
- Tax calculations apply German Sparerpauschbetrag (€1000 allowance) per year

### Number Format Auto-Detection
The InputValidator automatically detects number formats but throws errors for ambiguous cases. This prevents silent data corruption from misinterpreted user input.