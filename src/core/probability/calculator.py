from typing import List, Tuple
from tqdm import tqdm
import numpy as np

from .models import ProbabilityConfig

class StreakCalculator:
    """Calculator for streak probabilities using dynamic programming."""
    
    def __init__(self, config: ProbabilityConfig):
        """Initialize calculator with configuration."""
        self.config = config

    def calculate_probability(self, streak_length: int) -> float:
        """Calculate probability for a specific streak length using iterative DP."""
        n = self.config.num_games
        # dp[i][j] represents probability of NOT having seen streak_length
        # consecutive losses by position i with current streak j
        dp = np.zeros((n + 1, streak_length + 1))
        
        # Base case: at the start (no trades yet), probability is 1
        dp[0, 0] = 1
        
        # Fill the dp table
        for pos in range(n):
            for cons_losses in range(streak_length):
                if dp[pos, cons_losses] == 0:
                    continue
                    
                # Case 1: Win - reset streak
                dp[pos + 1, 0] += self.config.win_probability * dp[pos, cons_losses]
                
                # Case 2: Loss - extend streak
                next_losses = cons_losses + 1
                if next_losses < streak_length:
                    dp[pos + 1, next_losses] += (1 - self.config.win_probability) * dp[pos, cons_losses]
        
        # Sum up all probabilities of not having the streak at the end
        prob_no_streak = sum(dp[n, :])
        
        # Return probability of getting the streak at least once
        prob = float(1 - prob_no_streak)
        print(f"Debug: Streak length {streak_length} has probability {prob:.4f}")
        return prob

    def calculate_streaks_with_progress(self) -> List[Tuple[int, float]]:
        """Calculate probabilities for all streak lengths up to max_streak."""
        results = []
        print(f"\nCalculating probabilities (min threshold: {self.config.min_probability:.4f}):")
        for streak_len in tqdm(range(1, self.config.max_streak + 1), desc="Calculating"):
            prob = self.calculate_probability(streak_len)
            if prob >= self.config.min_probability:
                results.append((streak_len, prob))
        return results
