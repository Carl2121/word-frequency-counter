<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triangle Area Calculator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.green.min.css">
</head>
<body>
<?php


// List of common stop words to exclude from word frequency analysis
$stopWords = [
    "the", "and", "in", "on", "at", "to", "of", "for", "with", "a", "is", "by", "it", "this", 
    "that", "an", "be", "or", "as", "are", "was", "were", "from", "but", "not", "so"
];

// Function to tokenize text into words, remove stop words, and return an array of valid words
function tokenizeText($text, $stopWords) {
    // Convert text to lowercase and remove punctuation
    $text = strtolower($text);
    $text = preg_replace("/[^\w\s]/", "", $text); // Keep only words and spaces
    
    // Split text into words using spaces and line breaks
    $words = preg_split("/\s+/", $text);

    // Remove stop words and empty strings
    $filteredWords = array_filter($words, function ($word) use ($stopWords) {
        return $word !== "" && !in_array($word, $stopWords);
    });

    return $filteredWords;
}

// Function to calculate word frequency
function calculateWordFrequency($words) {
    return array_count_values($words);
}

// Function to sort the word frequency array based on user's preference (ascending/descending)
function sortWordFrequency($wordFrequencies, $sortOrder) {
    if ($sortOrder === "asc") {
        asort($wordFrequencies); // Sort in ascending order
    } else {
        arsort($wordFrequencies); // Sort in descending order
    }
    return $wordFrequencies;
}

// Function to display the word frequencies limited by the user's input
function displayWordFrequency($wordFrequencies, $limit) {
    $counter = 0;
    echo "<table border='4'><tr><th>Word</th><th>Frequency</th></tr>";
    foreach ($wordFrequencies as $word => $frequency) {
        if ($counter >= $limit) {
            break;
        }
        echo "<tr><td>" . htmlspecialchars($word) . "</td><td>" . htmlspecialchars($frequency) . "</td></tr>";
        $counter++;
    }
    echo "</table>";
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form input
    $text = $_POST['text'] ?? '';
    $sortOrder = $_POST['sort'] ?? 'desc';
    $limit = isset($_POST['limit']) && is_numeric($_POST['limit']) ? intval($_POST['limit']) : 10;

    if (!empty($text)) {
        // Tokenize text and calculate word frequency
        $words = tokenizeText($text, $stopWords);
        $wordFrequencies = calculateWordFrequency($words);

        // Sort the word frequencies based on user preference
        $sortedWordFrequencies = sortWordFrequency($wordFrequencies, $sortOrder);

        // Display the word frequencies, limited to the number specified by the user
        displayWordFrequency($sortedWordFrequencies, $limit);
    } else {
        echo "Please provide valid input text.";
    }
} else {
    echo "Invalid request.";
}

?>
</body>
</html>