<style>
    table, td, tr, input {
        padding: .2rem;
    }
    table {
        width: 100%;
        table-layout: fixed;
        text-align: center;
    }
    table td:hover {
        cursor: pointer;
        background: #eee;
    }
    input {
        width: 100%;
    }
    div {
        width: 20rem;
    }
</style>

<div>
    <h1>Calculator</h1>
    <form action="calculator.php" method="post" id="form">
        <input type="text" name="calculations">
    </form>
    <table border="2">
        <tr>
            <td>7</td>
            <td>8</td>
            <td>9</td>
        </tr>
        <tr>
            <td>4</td>
            <td>5</td>
            <td>6</td>
        </tr>
        <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
        </tr>
        <tr>
            <td colspan="2">0</td>
            <td>/</td>
        </tr>
        <tr>
            <td>+</td>
            <td>-</td>
            <td>*</td>
        </tr>
        <tr>
            <td colspan="3">Submit</td>
        </tr>
    </table>
</div>

<script>
    document.querySelector('table').addEventListener('click', function (e) {
        const buttonValue = e.target.innerText
        if(buttonValue.toLowerCase() === 'submit') {
            console.log('Do submit')
            document.querySelector('#form').submit()
            return
        }
        const input = document.querySelector(['input[name="calculations"]'])
        input.value = input.value.toString() + buttonValue.toString()
        console.log(input, ' is input')
    })
</script>

<?php
$calculations = $_POST['calculations'] ?? '';
$tokens = tokenize($calculations);
$output = convertToPostFix($tokens);
$result = processPostFix($output);

echo $calculations != '' ? "<h1>Your result is: {$result}</h1>" : '';

function tokenize(string $expression): array
{
    if(is_null($expression)) return [];

    $tokens = [];
    $i = 0;
    $length = strlen($expression);
    while($i < $length) 
    {
        if(ctype_space($expression[$i])) //ignore empty spaces
        {
            $i++;
            continue;
        }
        //for - sign it should be only checked in first iteration, it should not reach array out of bound, expression = - and next is digit
        // i.e -1, -22, -2344, ... as there is another while loop for appending all numbers before next operator
       
        if(
            ctype_digit($expression[$i]) 
            || 
            (
                $i == 0 
                && $i + 1 < $length 
                && $expression[$i] == '-' 
                && (ctype_digit($expression[$i + 1]) || $expression[$i + 1] == '(')
            )
        ) 
        {
            $number = $expression[$i];
            $i++;
            //now we are looking at the next digit
            while($i < $length && (ctype_digit($expression[$i]) || $expression[$i] == '.')) 
            {
                $number .= $expression[$i];
                $i++;
                continue;
            }

            $tokens[] = $number;
            continue;
        }

        if(in_array($expression[$i], ['(', ')', '+', '-', '*', '/'], true)) 
        {
            $tokens[] = $expression[$i];
            $i++;
            continue;
        }
        echo "<p style='color: red'>Invalid character '{$expression[$i]}' in expression</p>";
        die;

    }

    return $tokens;

}

function convertToPostFix(array $tokens): array 
{
    $output = [];
    $operators = [];
    $precedence = ['/' => 2, '*' => 2, '+' => 1, '-' => 1];
    //tokens = [-896, +, 23]
    foreach($tokens as $token) 
    {
        if(is_numeric($token)) 
        {
            $output []= $token;
        }

        else if($token == '(') 
        {
            $operators []= $token;
        }

        elseif ($token === ')') {
            while (end($operators) !== '(') {
                $output[] = array_pop($operators);
            }
            array_pop($operators); // Remove the '('
        } 
        
        else if(in_array($token, array_keys($precedence), true)) 
        {
            
            while(!empty($operators) &&  end($operators) !== '(' && $precedence[end($operators)] >= $precedence[$token]) 
            {
                $output []= array_pop($operators);
            }
            $operators []= $token;

        }

    }
    
    while(!empty($operators)) 
    {
        $output []= array_pop($operators);
    }

    return $output;
}

function processPostFix($postfix) 
{
    $stack = [];

    foreach($postfix as $token) 
    {
        if(is_numeric($token)) $stack []= $token;
        else 
        {
            $b = array_pop($stack); //first in b
            $a = array_pop($stack); //second in a
            if($token == '+') $result = $a + $b;
            if($token == '-') $result = $a - $b;
            if($token == '*') $result = $a * $b;
            if($token == '/') 
            {
                if($b == 0) 
                {
                    echo "<p style='color: red'>Invalid division by 0</p>";
                    die;
                }
                $result = $a / $b;
            }
            $stack[] = $result;
        }
    }

    return array_pop($stack);

}

