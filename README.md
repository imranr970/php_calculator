# Simple Php Math Expression Calculator

A lightweight calculator that evaluates mathematical expressions with **operator precedence** and **parentheses**, using the Shunting-Yard algorithm.

## Features
- ✅ Evaluates complex expressions like `3*(4+2)-5`
- ✅ Supports operators: `+`, `-`, `*`, `/`
- ✅ Handles parentheses `()` with PEMDAS precedence
- ❌ No scientific functions (log, sin, etc.)

## Algorithm Overview
1. **Tokenization**  
   Splits input into numbers/operators:

   "3(4+2)" → ["3", "", "(", "4", "+", "2", ")"]

2. **Shunting-Yard**  
Converts to postfix notation (RPN):
["3", "4", "2", "+", "*"]


3. **RPN Evaluation**  
Computes the result using a stack:

4 2 + → 6
3 6 * → 18


## Usage
```bash
php calculator.php "3+2*4"
# Output: 11

Example Workflow

Input:  "3*(4+2)-5"
Tokens: ["3", "*", "(", "4", "+", "2", ")", "-", "5"]
RPN:    ["3", "4", "2", "+", "*", "5", "-"]
Result: 13

Why This Approach?
Shunting-Yard: Classic algorithm (Dijkstra, 1961) for reliable parsing

RPN: Eliminates ambiguity in evaluation order

Stack-Based: O(n) time complexity


### Key Formatting Notes:
1. Code blocks wrapped in ``` for syntax highlighting
2. Arrows (`→`) preserved for visual flow
3. Emojis render natively on GitHub
4. Algorithm steps use bold headers for scannability

Want me to add an installation section or contribution guidelines? 😊
