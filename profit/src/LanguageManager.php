<?php

namespace App;

class LanguageManager
{
    private array $translations = [];
    private string $currentLanguage;
    private string $langPath;
    private array $availableLanguages = ['de', 'en'];
    
    public function __construct(string $langPath)
    {
        $this->langPath = $langPath;
        $this->currentLanguage = 'de';
    }
    
    public function setLanguage(string $language): void
    {
        if (in_array($language, $this->availableLanguages, true)) {
            $this->currentLanguage = $language;
            $this->loadTranslations();
        }
    }
    
    public function getCurrentLanguage(): string
    {
        return $this->currentLanguage;
    }
    
    public function getAvailableLanguages(): array
    {
        return $this->availableLanguages;
    }
    
    private function loadTranslations(): void
    {
        $file = $this->langPath . '/' . $this->currentLanguage . '.json';
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $this->translations = json_decode($content, true) ?? [];
        } else {
            $this->translations = [];
        }
    }
    
    public function get(string $key, array $params = []): string
    {
        if (empty($this->translations)) {
            $this->loadTranslations();
        }
        
        $keys = explode('.', $key);
        $value = $this->translations;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $key;
            }
        }
        
        if (!is_string($value)) {
            return $key;
        }
        
        foreach ($params as $param => $replacement) {
            $value = str_replace(':' . $param, $replacement, $value);
        }
        
        return $value;
    }
    
    public function getAllTranslations(): array
    {
        if (empty($this->translations)) {
            $this->loadTranslations();
        }
        return $this->translations;
    }
    
    public function formatNumber(float $number, int $decimals = 2): string
    {
        if ($this->currentLanguage === 'de') {
            return number_format($number, $decimals, ',', '.');
        } else {
            return number_format($number, $decimals, '.', ',');
        }
    }
    
    public function formatCurrency(float $amount): string
    {
        if ($this->currentLanguage === 'de') {
            return number_format($amount, 2, ',', '.') . ' €';
        } else {
            return '€ ' . number_format($amount, 2, '.', ',');
        }
    }
}