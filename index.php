<!--
Dear Students,

I hope this message finds you well. Today, I want to stress the importance of understanding and taking care of space complexity and time complexity in your code. These two aspects are critical in developing efficient and scalable software. 

- Importance of Space and Time Complexity

Time Complexity: measures the amount of time an algorithm takes to complete as a function of the size of its input. Efficient time complexity ensures that your programs run faster, which is crucial, especially when dealing with large datasets or real-time applications.

Space Complexity: measures the amount of memory an algorithm uses relative to the input size. By optimizing space complexity, you can make sure that your programs do not consume excessive memory, which can be a limiting factor in resource-constrained environments.

- Using the PHP Complexity Analyzer Tool

To help you better understand these concepts, we have developed a PHP Complexity Analyzer tool. Here’s how it works:

1. Code Input and Parsing: The tool accepts PHP code input from the user and uses PHP's tokenizer to analyze the code structure. It identifies functions, loops, and variable usage.

2. Complexity Calculation: The tool calculates the time and space complexity by examining the parsed tokens. It looks for loops to determine the time complexity (e.g., O(n), O(n^2)) and array usage for space complexity.

3. Visualization: The tool visualizes the complexities using graphical representations. It employs Chart.js to create bar charts that clearly illustrate the time and space complexity of the analyzed code.

This tool provides a hands-on way to see how different coding practices impact the performance and efficiency of your programs. By regularly using this tool, you will become more adept at writing optimized code.

- Importance of Learning Algorithms

Understanding algorithms is fundamental to mastering space and time complexity. Algorithms are the backbone of efficient coding practices. They provide structured solutions to common problems and are designed with complexity considerations in mind.

I strongly advise you to invest time in learning algorithms thoroughly. Study various types of algorithms such as sorting, searching, dynamic programming, and graph algorithms. Analyze their complexity and understand how to implement them efficiently.

In conclusion, always be mindful of the space and time complexity of your code. Use the PHP Complexity Analyzer tool to gain insights and continually improve your coding practices. A solid grasp of algorithms will significantly enhance your ability to write efficient and scalable code.

Best regards,

Majdi M S Awad

-->
<!DOCTYPE html>
<html>
<head>
    <title>Complexity Analyzer</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; padding: 20px; }
        textarea { width: 100%; height: 200px; }
        .results { margin-top: 20px; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>PHP Complexity Analyzer</h1>
        <form method="post" action="">
            <label for="code">Enter your PHP code:</label>
            <textarea name="code" id="code"></textarea><br>
            <input type="submit" name="analyze" value="Analyze">
        </form>
        <?php
        if (isset($_POST['analyze'])) {
            $code = $_POST['code'];
            analyzeComplexity($code);
        }

        function analyzeComplexity($code) {
            $tokens = token_get_all($code);
            $timeComplexity = 'O(1)';
            $spaceComplexity = 'O(1)';

            $nestingLevels = [];
            $functions = [];
            $currentFunction = null;
            $nestingLevel = 0;

            foreach ($tokens as $token) {
                if (is_array($token)) {
                    switch ($token[0]) {
                        case T_FUNCTION:
                            $currentFunction = ['name' => '', 'time' => 'O(1)', 'space' => 'O(1)'];
                            break;
                        case T_STRING:
                            if ($currentFunction && !$currentFunction['name']) {
                                $currentFunction['name'] = $token[1];
                                $functions[$currentFunction['name']] = &$currentFunction;
                            }
                            break;
                        case T_FOR:
                        case T_FOREACH:
                        case T_WHILE:
                            $nestingLevels[] = ++$nestingLevel;
                            if ($currentFunction) {
                                $currentFunction['time'] = 'O(n)';
                            } else {
                                $timeComplexity = 'O(n)';
                            }
                            break;
                        case T_VARIABLE:
                            if ($currentFunction) {
                                $currentFunction['space'] = 'O(n)';
                            } else {
                                $spaceComplexity = 'O(n)';
                            }
                            break;
                    }
                } else {
                    if ($token === '{') {
                        $nestingLevel++;
                    } elseif ($token === '}') {
                        $nestingLevel--;
                        if (!empty($nestingLevels) && end($nestingLevels) == $nestingLevel) {
                            array_pop($nestingLevels);
                        }
                    }
                }
            }

            if (!empty($nestingLevels) && max($nestingLevels) > 1) {
                $timeComplexity = 'O(n^' . max($nestingLevels) . ')';
            }

            echo '<div class="results">';
            echo '<h2>Results:</h2>';
            echo '<p>Time Complexity: ' . $timeComplexity . '</p>';
            echo '<p>Space Complexity: ' . $spaceComplexity . '</p>';
            visualizeComplexity($timeComplexity, $spaceComplexity);
            echo '</div>';
        }

        function visualizeComplexity($time, $space) {
            echo '<div id="chart-container" style="width: 50%; margin: auto;">';
            echo '<canvas id="complexityChart"></canvas>';
            echo '</div>';
            echo '<script>
            var ctx = document.getElementById("complexityChart").getContext("2d");
            var chart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["Time Complexity", "Space Complexity"],
                    datasets: [{
                        label: "Complexity",
                        data: [' . convertToNumerical($time) . ', ' . convertToNumerical($space) . '],
                        backgroundColor: ["rgba(75, 192, 192, 0.2)", "rgba(153, 102, 255, 0.2)"],
                        borderColor: ["rgba(75, 192, 192, 1)", "rgba(153, 102, 255, 1)"],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            </script>';
        }

        function convertToNumerical($complexity) {
            switch ($complexity) {
                case 'O(1)':
                    return 1;
                case 'O(n)':
                    return 2;
                case 'O(n^2)':
                    return 3;
                default:
                    preg_match('/O\(n\^(\d+)\)/', $complexity, $matches);
                    return $matches ? $matches[1] : 0;
            }
        }
        ?>
    </div>
</body>
</html>
