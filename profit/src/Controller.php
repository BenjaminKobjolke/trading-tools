<?php

namespace App;

use Twig\Environment;

class Controller
{
    private Calculator $calculator;
    private InputValidator $validator;
    private LanguageManager $language;
    private Environment $twig;
    
    public function __construct(
        Calculator $calculator,
        InputValidator $validator,
        LanguageManager $language,
        Environment $twig
    ) {
        $this->calculator = $calculator;
        $this->validator = $validator;
        $this->language = $language;
        $this->twig = $twig;
    }
    
    public function handleRequest(array $post, array &$session): array
    {
        $this->handleLanguageSwitch($post, $session);
        
        // Load saved form data from session
        $savedFormData = $session['form_data'] ?? [];
        
        $data = [
            'lang' => $this->language,
            'translations' => $this->language->getAllTranslations(),
            'current_language' => $this->language->getCurrentLanguage(),
            'available_languages' => $this->language->getAvailableLanguages(),
            'result' => null,
            'error' => null,
            'form_data' => [
                'amount' => $savedFormData['amount'] ?? null,
                'rate' => $savedFormData['rate'] ?? null,
                'period' => $savedFormData['period'] ?? 'monthly',
                'custom_days' => $savedFormData['custom_days'] ?? null,
                'use_tax_allowance' => $savedFormData['use_tax_allowance'] ?? false,
                'until_year_end' => $savedFormData['until_year_end'] ?? false,
                'monthly_contribution' => $savedFormData['monthly_contribution'] ?? null
            ]
        ];
        
        if (!empty($post) && isset($post['calculate'])) {
            $data = $this->processCalculation($post, $data, $session);
        }
        
        return $data;
    }
    
    private function handleLanguageSwitch(array $post, array &$session): void
    {
        if (isset($post['language'])) {
            $session['language'] = $post['language'];
            $this->language->setLanguage($post['language']);
        } elseif (isset($session['language'])) {
            $this->language->setLanguage($session['language']);
        }
    }
    
    private function processCalculation(array $post, array $data, array &$session): array
    {
        $locale = $this->language->getCurrentLanguage();
        
        $errors = [];
        $amount = null;
        $rate = null;
        $monthlyContribution = 0;
        
        // Parse and validate inputs with proper error handling
        try {
            $amount = $this->validator->parseDecimal($post['amount'] ?? null, $locale);
        } catch (\InvalidArgumentException $e) {
            if ($e->getMessage() === 'ambiguous_format') {
                $errors[] = $this->language->get('errors.ambiguous_format');
            }
        }
        
        try {
            $rate = $this->validator->parseDecimal($post['rate'] ?? null, $locale);
        } catch (\InvalidArgumentException $e) {
            if ($e->getMessage() === 'ambiguous_format') {
                $errors[] = $this->language->get('errors.ambiguous_format');
            }
        }
        
        try {
            $monthlyContribution = $this->validator->parseDecimal($post['monthly_contribution'] ?? null, $locale) ?? 0;
        } catch (\InvalidArgumentException $e) {
            if ($e->getMessage() === 'ambiguous_format') {
                $errors[] = $this->language->get('errors.ambiguous_format');
            }
        }
        
        $period = $post['period'] ?? 'monthly';
        $customDays = null;
        $useTaxAllowance = isset($post['use_tax_allowance']) && $post['use_tax_allowance'] === 'on';
        $untilYearEnd = isset($post['until_year_end']) && $post['until_year_end'] === 'on';
        
        if ($period === 'custom') {
            $customDays = (int) ($post['custom_days'] ?? 0);
        }
        
        // Store raw input values for display (preserve user input)
        $data['form_data'] = [
            'amount' => $amount,
            'rate' => $rate,
            'period' => $period,
            'custom_days' => $customDays,
            'use_tax_allowance' => $useTaxAllowance,
            'until_year_end' => $untilYearEnd,
            'monthly_contribution' => $monthlyContribution,
            // Store raw input for session persistence
            'amount_input' => $post['amount'] ?? '',
            'rate_input' => $post['rate'] ?? '',
            'monthly_contribution_input' => $post['monthly_contribution'] ?? ''
        ];
        
        // Save form data to session for persistence
        $session['form_data'] = $data['form_data'];
        
        // Continue with validation if no parsing errors
        if (empty($errors)) {
            if ($error = $this->validator->validateAmount($amount)) {
                $errors[] = $this->language->get('errors.' . $error);
            }
            
            if ($error = $this->validator->validateRate($rate)) {
                $errors[] = $this->language->get('errors.' . $error);
            }
            
            if ($monthlyContribution < 0) {
                $errors[] = $this->language->get('errors.contribution_negative');
            }
            
            if (!$this->calculator->isValidPeriod($period)) {
                $errors[] = $this->language->get('errors.invalid_period');
            }
            
            if ($error = $this->validator->validateCustomDays($customDays, $period)) {
                $errors[] = $this->language->get('errors.' . $error);
            }
        }
        
        if (!empty($errors)) {
            $data['error'] = implode(' ', $errors);
        } else {
            $data['result'] = $this->calculator->calculate(
                $amount, 
                $rate, 
                $period, 
                $customDays, 
                $useTaxAllowance,
                $untilYearEnd,
                $monthlyContribution
            );
        }
        
        return $data;
    }
    
    public function render(array $data): string
    {
        return $this->twig->render('calculator.html.twig', $data);
    }
}