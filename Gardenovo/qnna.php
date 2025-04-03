<?php
require_once 'QnA.php';

$qna = new QnA();
$questionsAndAnswers = $qna->fetchQnA();
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QnA</title>
</head>
<body>
    <h1>Otázky a odpovede</h1>
    <ul>
        <?php foreach ($questionsAndAnswers as $qa): ?>
            <li><strong><?php echo htmlspecialchars($qa['otazka']); ?>:</strong> <?php echo htmlspecialchars($qa['odpoved']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>