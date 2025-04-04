<?php

class QnA{
    private $conn;
    public function __construct() {
        $this->connect();
    }
    private function connect() {
        $config = DATABASE;

        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        );
        try {
            $this->conn = new PDO('mysql:host=' . $config['HOST'] . ';dbname=' .
                $config['DBNAME'] . ';port=' . $config['PORT'], $config['USER_NAME'],
                $config['PASSWORD'], $options);
        } catch (PDOException $e) {
            die("Chyba pripojenia: " . $e->getMessage());
        }
    }
    public function insertQnA(){
        try {
            // Načítanie JSON súboru
            $data = json_decode(file_get_contents
            (__ROOT__.'/data/datas.json'), true);
            $otazky = $data["otazky"];
            $odpovede = $data["odpovede"];
            // Vloženie otázok a odpovedí v rámci transakcie
            $this->conn->beginTransaction();
    
            $sql = "INSERT INTO qna (otazka, odpoved) VALUES (:otazka, :odpoved)";
            $statement = $this->conn->prepare($sql);
    
            for ($i = 0; $i < count($otazky); $i++) {
                $statement->bindParam(':otazka', $otazky[$i]);
                $statement->bindParam(':odpoved', $odpovede[$i]);
                $statement->execute();
            }
            $this->conn->commit();
            echo "Dáta boli vložené";
        } catch (Exception $e) {
            // Zobrazenie chybového hlásenia
            echo "Chyba pri vkladaní dát do databázy: " . $e->getMessage();
            $this->conn->rollback(); // Vrátenie späť zmien v prípade chyby
        } finally {
            // Uzatvorenie spojenia
            $this->conn = null;
        }
    }
}
?>

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
