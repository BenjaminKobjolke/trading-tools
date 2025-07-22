#!/usr/bin/env python3
"""Main entry point for the trading tools application."""

import sys
from core.probability import CommandLineInterface

def main():
    """Main entry point."""
    try:
        config = CommandLineInterface.parse_arguments()
        CommandLineInterface.run(config)
    except ValueError as e:
        print(f"Error: {e}", file=sys.stderr)
        sys.exit(1)
    except KeyboardInterrupt:
        print("\nOperation cancelled by user", file=sys.stderr)
        sys.exit(1)

if __name__ == "__main__":
    main()
