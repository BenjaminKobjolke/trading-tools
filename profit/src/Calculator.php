<?php

namespace App;

class Calculator
{
    public function calculate(
        float $amount, 
        float $rate, 
        string $period, 
        ?int $customDays = null, 
        bool $useTaxAllowance = false,
        bool $untilYearEnd = false,
        float $monthlyContribution = 0
    ): array {
        // Calculate time factor (full year or until year-end)
        $timeFactor = 1.0;
        $monthsRemaining = 12;
        
        if ($untilYearEnd) {
            $currentMonth = (int)date('n');
            $currentDay = (int)date('j');
            $daysInCurrentMonth = (int)date('t');
            
            // Calculate remaining months including partial current month
            $monthsRemaining = 12 - $currentMonth + ($currentDay / $daysInCurrentMonth);
            $timeFactor = $monthsRemaining / 12;
        }
        
        $periodsPerYear = $this->getPeriodsPerYear($period, $customDays);
        $periodsToCalculate = $periodsPerYear * $timeFactor;
        
        $r = $rate / 100.0;
        
        // Calculate compound interest with monthly contributions
        if ($monthlyContribution > 0) {
            // Calculate final amount with regular contributions
            // Using formula for compound interest with regular deposits
            $monthlyRate = pow(1 + $r, 1 / ($periodsPerYear / 12)) - 1;
            $monthsToCalculate = $monthsRemaining;
            
            // Initial amount grows with compound interest
            $finalFromInitial = $amount * pow(1 + $r, $periodsToCalculate);
            
            // Monthly contributions with compound interest
            $finalFromContributions = 0;
            if ($monthlyRate > 0) {
                $finalFromContributions = $monthlyContribution * 
                    ((pow(1 + $monthlyRate, $monthsToCalculate) - 1) / $monthlyRate) * 
                    (1 + $monthlyRate);
            } else {
                $finalFromContributions = $monthlyContribution * $monthsToCalculate;
            }
            
            $finalAmount = $finalFromInitial + $finalFromContributions;
            $totalInvested = $amount + ($monthlyContribution * $monthsToCalculate);
        } else {
            // Simple compound interest without contributions
            $finalAmount = $amount * pow(1 + $r, $periodsToCalculate);
            $totalInvested = $amount;
        }
        
        $profit = $finalAmount - $totalInvested;
        $effectiveAnnualRate = $timeFactor < 1 ? 
            (pow($finalAmount / $totalInvested, 1 / $timeFactor) - 1) : 
            ($finalAmount / $totalInvested - 1);
        
        // Calculate German capital gains tax (Abgeltungssteuer) - 25% on profit
        $taxRate = 0.25;
        $taxFreeAllowance = $useTaxAllowance ? 1000.0 : 0.0; // Sparerpauschbetrag
        
        // Only profits above the tax-free allowance are taxed
        $taxableProfit = max(0, $profit - $taxFreeAllowance);
        $taxAmount = $taxableProfit * $taxRate;
        $profitAfterTax = $profit - $taxAmount;
        $finalAmountAfterTax = $totalInvested + $profitAfterTax;
        
        // Calculate multi-year projections (2-10 years) - only if not year-end mode
        $multiYearProjections = [];
        if (!$untilYearEnd) {
            $years = [2, 3, 4, 5, 6, 7, 8, 9, 10];
            foreach ($years as $year) {
                $yearlyData = $this->calculateForYears($amount, $rate, $period, $customDays, $useTaxAllowance, $monthlyContribution, $year);
                $multiYearProjections[$year] = $yearlyData;
            }
        }

        return [
            'periods_per_year' => $periodsPerYear,
            'periods_calculated' => $periodsToCalculate,
            'months_calculated' => $monthsRemaining,
            'factor' => $finalAmount / $totalInvested,
            'effective_annual_rate' => $effectiveAnnualRate,
            'final_amount' => $finalAmount,
            'profit' => $profit,
            'tax_rate' => $taxRate,
            'tax_free_allowance' => $taxFreeAllowance,
            'taxable_profit' => $taxableProfit,
            'tax_amount' => $taxAmount,
            'profit_after_tax' => $profitAfterTax,
            'final_amount_after_tax' => $finalAmountAfterTax,
            'initial_amount' => $amount,
            'total_invested' => $totalInvested,
            'monthly_contribution' => $monthlyContribution,
            'total_contributions' => $monthlyContribution * $monthsRemaining,
            'period_rate' => $rate,
            'period_type' => $period,
            'custom_days' => $customDays,
            'use_tax_allowance' => $useTaxAllowance,
            'until_year_end' => $untilYearEnd,
            'time_factor' => $timeFactor,
            'multi_year_projections' => $multiYearProjections
        ];
    }
    
    private function getPeriodsPerYear(string $period, ?int $customDays = null): float
    {
        return match($period) {
            'daily' => 365,
            'weekly' => 52,
            'monthly' => 12,
            'custom' => $customDays ? (365 / $customDays) : 1,
            default => 12,
        };
    }
    
    public function isValidPeriod(string $period): bool
    {
        return in_array($period, ['daily', 'weekly', 'monthly', 'custom'], true);
    }
    
    private function calculateForYears(
        float $amount, 
        float $rate, 
        string $period, 
        ?int $customDays, 
        bool $useTaxAllowance, 
        float $monthlyContribution, 
        int $years
    ): array {
        $periodsPerYear = $this->getPeriodsPerYear($period, $customDays);
        $periodsToCalculate = $periodsPerYear * $years;
        $monthsToCalculate = $years * 12;
        
        $r = $rate / 100.0;
        
        // Calculate compound interest with monthly contributions
        if ($monthlyContribution > 0) {
            $monthlyRate = pow(1 + $r, 1 / ($periodsPerYear / 12)) - 1;
            
            // Initial amount grows with compound interest
            $finalFromInitial = $amount * pow(1 + $r, $periodsToCalculate);
            
            // Monthly contributions with compound interest
            $finalFromContributions = 0;
            if ($monthlyRate > 0) {
                $finalFromContributions = $monthlyContribution * 
                    ((pow(1 + $monthlyRate, $monthsToCalculate) - 1) / $monthlyRate) * 
                    (1 + $monthlyRate);
            } else {
                $finalFromContributions = $monthlyContribution * $monthsToCalculate;
            }
            
            $finalAmount = $finalFromInitial + $finalFromContributions;
            $totalInvested = $amount + ($monthlyContribution * $monthsToCalculate);
        } else {
            // Simple compound interest without contributions
            $finalAmount = $amount * pow(1 + $r, $periodsToCalculate);
            $totalInvested = $amount;
        }
        
        $profit = $finalAmount - $totalInvested;
        
        // Calculate tax for this year only (not cumulative)
        $taxRate = 0.25;
        $taxFreeAllowance = $useTaxAllowance ? 1000.0 : 0.0;
        $taxableProfit = max(0, $profit - $taxFreeAllowance);
        $taxAmount = $taxableProfit * $taxRate;
        $profitAfterTax = $profit - $taxAmount;
        $finalAmountAfterTax = $totalInvested + $profitAfterTax;
        
        // Calculate effective annual rate for this time period
        $effectiveAnnualRate = pow($finalAmountAfterTax / $totalInvested, 1 / $years) - 1;
        
        return [
            'years' => $years,
            'final_amount' => $finalAmount,
            'total_invested' => $totalInvested,
            'profit' => $profit,
            'tax_amount' => $taxAmount,
            'final_amount_after_tax' => $finalAmountAfterTax,
            'profit_after_tax' => $profitAfterTax,
            'total_contributions' => $monthlyContribution * $monthsToCalculate,
            'effective_annual_rate' => $effectiveAnnualRate
        ];
    }
}