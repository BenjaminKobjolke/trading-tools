import argparse
from typing import List, Optional, Tuple

from .models import ProbabilityConfig
from .calculator import StreakCalculator

class CommandLineInterface:
    """Handle command line interface for probability calculations."""

    @staticmethod
    def parse_arguments(args: Optional[List[str]] = None) -> ProbabilityConfig:
        """Parse command line arguments.
        
        Args:
            args: Optional list of command line arguments
            
        Returns:
            ProbabilityConfig object with parsed arguments
        """
        parser = argparse.ArgumentParser(
            description="Calculate probability of losing streaks in trading"
        )
        
        parser.add_argument(
            "--num-games",
            "-n",
            type=int,
            required=True,
            help="Number of games to simulate"
        )
        
        parser.add_argument(
            "--win-probability",
            "-w",
            type=float,
            required=True,
            help="Probability of winning a single game"
        )

        parser.add_argument(
            "--deposit-amount",
            "-d",
            type=float,
            required=True,
            help="Initial deposit amount in currency units (e.g., 10000 for 10,000 euros)"
        )

        parser.add_argument(
            "--risk-per-trade",
            "-r",
            type=float,
            required=True,
            help="Risk amount per trade in currency units (e.g., 10 for 10 euros)"
        )
        
        parser.add_argument(
            "--min-probability",
            "-m",
            type=float,
            default=0.05,
            help="Minimum probability threshold to show results (default: 0.05)"
        )
        
        parser.add_argument(
            "--max-streak",
            "-s",
            type=int,
            default=20,
            help="Maximum streak length to calculate (default: 20)"
        )

        parsed_args = parser.parse_args(args)
        
        return ProbabilityConfig(
            num_games=parsed_args.num_games,
            win_probability=parsed_args.win_probability,
            deposit_amount=parsed_args.deposit_amount,
            risk_per_trade=parsed_args.risk_per_trade,
            min_probability=parsed_args.min_probability,
            max_streak=parsed_args.max_streak
        )

    @staticmethod
    def run(config: ProbabilityConfig) -> None:
        """Run the probability calculations and display results.
        
        Args:
            config: Configuration for probability calculations
        """
        calculator = StreakCalculator(config)
        results = calculator.calculate_streaks_with_progress()
        
        if not results:
            print("\nNo streaks found with probability >= {:.1f}%".format(
                config.min_probability * 100))
            print("Try lowering the minimum probability threshold with -m option")
            return

        print("\nResults for {} games with {:.1f}% win probability:".format(
            config.num_games, config.win_probability * 100))
        print("Initial deposit: {:.2f}".format(config.deposit_amount))
        print("Risk per trade: {:.2f}".format(config.risk_per_trade))
        print()
        
        for streak_len, prob in results:
            total_risk = streak_len * config.risk_per_trade
            remaining_balance = config.deposit_amount - total_risk
            risk_percentage = (total_risk / config.deposit_amount) * 100

            print(f"\n{streak_len} losses in a row ({prob*100:.2f}% probability):")
            print(f"- Total risk: {total_risk:.2f} currency units")
            print(f"- Remaining balance: {remaining_balance:.2f} currency units")
            print(f"- Risk percentage: {risk_percentage:.1f}% of deposit")

            if risk_percentage >= 20:
                print("WARNING: This streak risks a significant portion of your deposit!")
