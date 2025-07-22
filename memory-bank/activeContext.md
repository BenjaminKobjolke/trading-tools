# Active Context: Trading Tools

## Current Focus

1. Probability Calculator Improvements

   - Fixed probability calculation algorithm
   - Implemented iterative dynamic programming approach
   - Added financial risk assessment
   - Improved handling of large numbers of trades

2. Code Organization
   - Restructured into modular package
   - Separated concerns into distinct classes
   - Added proper Python package structure
   - Implemented named arguments support

## Recent Changes

1. Algorithm Updates

   - Switched from recursive to iterative approach
   - Fixed probability calculation logic
   - Added NumPy for efficient calculations
   - Improved memory usage

2. Risk Management

   - Added deposit amount tracking
   - Implemented per-trade risk calculation
   - Added risk percentage analysis
   - Implemented warning system for high risks

3. Documentation
   - Created comprehensive README
   - Added usage examples
   - Documented command-line arguments
   - Initialized memory bank

## Active Decisions

1. Technical Choices

   - Using NumPy for calculations
   - Iterative DP over recursion
   - Command-line interface
   - Modular architecture

2. Risk Assessment
   - 20% deposit risk threshold for warnings
   - Clear financial impact display
   - Probability threshold defaults to 5%
   - Maximum streak defaults to 20

## Project Insights

1. Probability Calculation

   - Correct probabilities for streak lengths
   - Example: 5 losses (50% win rate) ≈ 3.125%
   - Example: 10 losses (50% win rate) ≈ 0.0977%
   - Example: 20 losses (50% win rate) ≈ 0.0001%

2. User Experience
   - Named arguments improve usability
   - Progress bar helps with long calculations
   - Clear financial impact presentation
   - Helpful warning messages

## Next Steps

1. Potential Improvements

   - Add visualization of probabilities
   - Support for different currency formats
   - Additional risk metrics
   - Configuration file support

2. Code Quality

   - Add unit tests
   - Add integration tests
   - Improve error messages
   - Add logging system

3. Documentation
   - Add API documentation
   - Create user guide
   - Add more usage examples
   - Document common scenarios
