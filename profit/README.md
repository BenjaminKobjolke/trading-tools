# Profit Calculator - Annual Return Calculator

A modern, dark-themed web application for calculating annual returns with compound interest. Features multi-language support (German/English) and custom period calculations.

## Features

- 🌙 **Dark Theme**: Modern dark design with excellent contrast
- 🌍 **Multi-language**: German and English support with easy switching
- 📊 **Multiple Periods**: Daily, Weekly, Monthly, or Custom (1-365 days)
- 🎯 **Clean Architecture**: Separated concerns with MVC pattern
- 📱 **Responsive**: Mobile-friendly Bootstrap 5 design
- 🔧 **Template Engine**: Twig for clean separation of logic and presentation

## Installation

1. Install dependencies via Composer:
```bash
composer install
```

2. Start a local PHP server:
```bash
php -S localhost:8000
```

3. Open your browser and navigate to:
```
http://localhost:8000
```

## Project Structure

```
php/
├── src/                      # Application classes
│   ├── Calculator.php        # Business logic
│   ├── InputValidator.php    # Input validation
│   ├── LanguageManager.php   # Translation handling
│   └── Controller.php        # Request handling
├── templates/                # Twig templates
│   ├── base.html.twig       # Base layout
│   └── calculator.html.twig # Calculator UI
├── lang/                     # Language files
│   ├── de.json              # German translations
│   └── en.json              # English translations
├── public/assets/           # Public assets
│   ├── css/dark-theme.css  # Dark theme styles
│   └── js/app.js           # UI interactions
├── config/                  # Configuration
│   └── config.php          # App settings
└── index.php               # Entry point
```

## Usage

1. Enter an amount in EUR
2. Enter the return rate per period (%)
3. Select the period type:
   - Daily (365 periods/year)
   - Weekly (52 periods/year)
   - Monthly (12 periods/year)
   - Custom (specify days)
4. Click "Calculate" to see results

## Technologies

- PHP 8.0+
- Twig Template Engine
- Bootstrap 5
- Modern CSS with CSS Variables
- Vanilla JavaScript

## License

MIT