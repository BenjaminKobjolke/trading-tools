<?php

namespace App;

class InputValidator
{
    public function parseDecimal(?string $input, string $locale = 'de'): ?float
    {
        if ($input === null || trim($input) === '') {
            return null;
        }
        
        $input = trim($input);
        
        // Remove spaces
        $input = str_replace(['\u{00A0}', ' '], '', $input);
        
        // Count occurrences of dots and commas
        $dotCount = substr_count($input, '.');
        $commaCount = substr_count($input, ',');
        
        // If there are multiple dots or multiple commas, it's an error
        if ($dotCount > 1 || $commaCount > 1) {
            return null;
        }
        
        // Check for ambiguous format like "1,2.3" or "1.2,3" 
        if ($dotCount === 1 && $commaCount === 1) {
            $dotPos = strpos($input, '.');
            $commaPos = strpos($input, ',');
            
            // Check if it's an ambiguous format where we can't be sure
            // Example: "1,2.3" - is it 1.23 or 12.3?
            $beforeFirstSeparator = min($dotPos, $commaPos);
            $betweenSeparators = abs($dotPos - $commaPos) - 1;
            
            // If there are only 1-2 digits between separators, it's ambiguous
            if ($betweenSeparators <= 2 && $beforeFirstSeparator <= 2) {
                throw new \InvalidArgumentException('ambiguous_format');
            }
            
            // The one that appears last is the decimal separator
            if ($dotPos > $commaPos) {
                // Dot is decimal separator (English format: 1,234.56)
                $input = str_replace(',', '', $input);
            } else {
                // Comma is decimal separator (German format: 1.234,56)
                $input = str_replace('.', '', $input);
                $input = str_replace(',', '.', $input);
            }
        } elseif ($commaCount === 1) {
            // Only comma present - treat as decimal separator
            $input = str_replace(',', '.', $input);
        }
        // If only dot present or no separator, leave as is
        
        if (!is_numeric($input)) {
            return null;
        }
        
        return (float) $input;
    }
    
    public function validateAmount(?float $amount): ?string
    {
        if ($amount === null) {
            return 'amount_required';
        }
        if ($amount < 0) {
            return 'amount_negative';
        }
        return null;
    }
    
    public function validateRate(?float $rate): ?string
    {
        if ($rate === null) {
            return 'rate_required';
        }
        if ($rate < -100) {
            return 'rate_too_low';
        }
        return null;
    }
    
    public function validateCustomDays(?int $days, string $period): ?string
    {
        if ($period !== 'custom') {
            return null;
        }
        
        if ($days === null || $days < 1) {
            return 'custom_days_required';
        }
        if ($days > 365) {
            return 'custom_days_too_high';
        }
        return null;
    }
    
    public function sanitizeInput(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}