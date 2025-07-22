# System Patterns: Trading Tools

## Architecture Overview

The system follows a modular, layered architecture:

```
trading-tools/
├── src/                      # Source code root
│   ├── core/                # Core functionality
│   │   └── probability/     # Probability calculation module
│   └── main.py             # Entry point
```

## Design Patterns

1. Command Pattern (CLI)

   - Encapsulates command-line arguments
   - Separates CLI handling from business logic
   - Uses argparse for structured argument parsing

2. Data Class Pattern (Configuration)

   - Immutable configuration objects
   - Built-in validation
   - Type hints for clarity

3. Strategy Pattern (Calculation)
   - Encapsulates algorithm implementation
   - Allows for future alternative calculation methods
   - Clean separation of concerns

## Key Technical Decisions

1. Dynamic Programming Implementation

   - Iterative approach over recursion
   - NumPy for efficient array operations
   - Handles large numbers of trades efficiently

2. Error Handling

   - Validation at configuration level
   - Clear error messages
   - Graceful error recovery

3. Progress Tracking
   - TQDM for progress bars
   - Debug output for verification
   - Clear result presentation

## Component Relationships

1. Configuration Flow

   ```
   CLI Arguments -> ProbabilityConfig -> StreakCalculator
   ```

2. Calculation Flow

   ```
   StreakCalculator -> Dynamic Programming -> Results
   ```

3. Output Flow
   ```
   Results -> Financial Impact -> User Display
   ```

## Critical Implementation Paths

1. Probability Calculation

   - Base case initialization
   - State transitions
   - Probability accumulation

2. Risk Assessment

   - Streak length calculation
   - Financial impact calculation
   - Risk percentage evaluation

3. User Interface
   - Argument parsing
   - Result formatting
   - Warning generation

## Memory Management

1. NumPy Arrays

   - Efficient memory usage
   - Vectorized operations
   - Automatic cleanup

2. Cache Management
   - No caching needed (iterative approach)
   - Minimal memory footprint
   - Scalable to large inputs
