# Technical Context: Trading Tools

## Development Environment

1. Language & Version

   - Python 3.6+
   - Type hints for better code clarity
   - Modern Python features (dataclasses, f-strings)

2. Virtual Environment
   - Uses venv for isolation
   - Activated via activate_environment.bat
   - Clean dependency management

## Dependencies

1. Core Libraries

   ```
   numpy>=1.24.0    # Efficient numerical operations
   tqdm>=4.65.0     # Progress bar functionality
   ```

2. Standard Library Usage
   - argparse: Command line argument parsing
   - typing: Type hints and annotations
   - functools: Function decorators
   - sys: System-specific parameters

## Development Tools

1. Version Control

   - Git for source control
   - GitHub for repository hosting
   - Main branch for stable releases

2. Code Organization
   - Modular package structure
   - Clear separation of concerns
   - Consistent file naming

## Technical Constraints

1. Performance

   - Efficient memory usage for large calculations
   - Vectorized operations where possible
   - Progress indication for long operations

2. Compatibility
   - Cross-platform support (Windows/Linux/Mac)
   - No external system dependencies
   - Minimal installation requirements

## Tool Usage Patterns

1. Installation

   ```bash
   python -m venv venv
   .\venv\Scripts\activate  # Windows
   pip install -r requirements.txt
   ```

2. Running

   ```bash
   python src/main.py [arguments]
   ```

3. Common Usage

   ```bash
   # Basic usage
   python src/main.py -n 100 -w 0.5 -d 10000 -r 100

   # With all options
   python src/main.py --num-games 100 --win-probability 0.5 --deposit-amount 10000 --risk-per-trade 100 --min-probability 0.05 --max-streak 20
   ```

## Error Handling

1. Input Validation

   - Type checking
   - Range validation
   - Meaningful error messages

2. Runtime Errors
   - Graceful error handling
   - Clear error reporting
   - User-friendly messages

## Future Considerations

1. Potential Enhancements

   - GUI interface
   - Additional probability models
   - Extended risk metrics

2. Scalability
   - Current design supports future extensions
   - Modular architecture for new features
   - Clear extension points
