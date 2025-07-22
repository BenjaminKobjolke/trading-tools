# Trading Tools

A collection of tools for trading analysis and probability calculations.

## Probability Calculator

The probability calculator helps determine the likelihood of losing streaks in trading scenarios and analyzes the financial risk. It uses dynamic programming to efficiently calculate probabilities for different streak lengths.

### Installation

1. Clone the repository:

```bash
git clone https://github.com/BenjaminKobjolke/trading-tools.git
cd trading-tools
```

2. Create and activate a virtual environment (recommended):

```bash
python -m venv venv
.\venv\Scripts\activate  # Windows
source venv/bin/activate  # Linux/Mac
```

3. Install dependencies:

```bash
pip install -r requirements.txt
```

### Usage

The probability calculator can be run using named arguments:

```bash
python src/main.py --num-games 100 --win-probability 0.5 --deposit-amount 10000 --risk-per-trade 10
```

#### Arguments

- `--num-games`, `-n` (required): Number of games to simulate
- `--win-probability`, `-w` (required): Probability of winning a single game (between 0 and 1)
- `--deposit-amount`, `-d` (required): Initial deposit amount in currency units (e.g., 10000 for 10,000 euros)
- `--risk-per-trade`, `-r` (required): Risk amount per trade in currency units (e.g., 10 for 10 euros)
- `--min-probability`, `-m` (optional): Minimum probability threshold to show results (default: 0.05)
- `--max-streak`, `-s` (optional): Maximum streak length to calculate (default: 20)

#### Example

```bash
python src/main.py -n 100 -w 0.5 -d 10000 -r 10 -m 0.05 -s 20
```

This will:

1. Calculate probabilities for losing streaks in 100 games with a 50% win rate
2. Start with a deposit of 10,000 currency units
3. Risk 10 currency units per trade
4. Show streaks with at least 5% probability
5. Calculate up to a maximum of 20 consecutive losses

### Output

The program outputs each streak length, its probability, and financial impact:

```
5 losses in a row (62.50% probability):
- Total risk: 50.00 currency units
- Remaining balance: 9950.00 currency units
- Risk percentage: 0.5% of deposit

10 losses in a row (31.25% probability):
- Total risk: 100.00 currency units
- Remaining balance: 9900.00 currency units
- Risk percentage: 1.0% of deposit
```

The program will warn you if any probable streak would risk a significant portion (≥20%) of your deposit.

### Performance Note

The calculator uses NumPy for efficient calculations and can handle large numbers of games (e.g., 1000+ games) without running into recursion limits.

## Project Structure

```
trading-tools/
├── src/
│   ├── __init__.py
│   ├── main.py
│   └── core/
│       ├── __init__.py
│       └── probability/
│           ├── __init__.py
│           ├── models.py      # Configuration data class
│           ├── calculator.py  # Core calculation logic
│           └── cli.py        # Command line interface
├── README.md
└── requirements.txt
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
