from dataclasses import dataclass
from typing import Optional

@dataclass
class ProbabilityConfig:
    """Configuration class to hold probability calculation parameters."""
    num_games: int
    win_probability: float
    deposit_amount: float
    risk_per_trade: float
    min_probability: float = 0.05
    max_streak: int = 20

    def __post_init__(self):
        """Validate configuration parameters."""
        if not 0 <= self.win_probability <= 1:
            raise ValueError("Win probability must be between 0 and 1")
        if not 0 <= self.min_probability <= 1:
            raise ValueError("Minimum probability must be between 0 and 1")
        if self.num_games <= 0:
            raise ValueError("Number of games must be positive")
        if self.max_streak <= 0:
            raise ValueError("Maximum streak must be positive")
        if self.deposit_amount <= 0:
            raise ValueError("Deposit amount must be positive")
        if self.risk_per_trade <= 0:
            raise ValueError("Risk per trade must be positive")
        if self.risk_per_trade > self.deposit_amount:
            raise ValueError("Risk per trade cannot be greater than deposit amount")
