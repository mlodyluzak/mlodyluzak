<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista osób i ich ocen</title>
    <style>
        body {
            background-color: #7FFF7F; /* Słabszy zielony */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            cursor: pointer;
            position: relative;
        }
        .sort-icon {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
        }
        .colored {
            background-color: #27598f; /* Kolor niebieski dla tła */
            color: black; /* Czarny kolor tekstu */
        }
        tr:nth-child(even) {
            background-color: #844eac; /* Kolor rekordów */
        }
        .delete-button {
            background-color: #795dc7; /* Kolor dla pola checkbox */
            color: white; /* Biały kolor tekstu */
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Dodane przejście */
        }
        .delete-button:hover {
            background-color: #225581; 
        }
    </style>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <center><label for="srednia">Wybierz średnią ocen:</label></center>
    <center><select name="srednia" id="srednia"></center>
        <option value="all">Wszystkie</option>
        <option value="1"> 1</option>
        <option value="2"> 2</option>
        <option value="3"> 3</option>
        <option value="4"> 4</option>
        <option value="5"> 5</option>
        <option value="6"> 6</option>
    </select>
    <input type="submit" value="szukaj">
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <center><label for="imie">Wyszukaj po imieniu:</label></center>
    <center><input type="text" name="imie"></center>
    <center><input type="submit" value="Szukaj"></center>
</form>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dziennik";

// Tworzenie połączenia z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obsługa usuwania rekordów
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteRecords'])) {
    $recordsToDelete = $_POST['deleteRecords'];
    foreach ($recordsToDelete as $recordId) {
        $sqlDelete = "DELETE FROM oceny WHERE id=$recordId";
        $conn->query($sqlDelete);
    }
}

// Zapytanie SQL
$sql = "SELECT id, imie, nazwisko, GROUP_CONCAT(ocena SEPARATOR ', ') AS oceny, AVG(ocena) AS srednia FROM oceny GROUP BY id, imie, nazwisko";
$sql = "SELECT id, imie, nazwisko, GROUP_CONCAT(ocena SEPARATOR ', ') AS oceny, AVG(ocena) AS srednia FROM oceny";

// Dodanie warunku wyszukiwania po imieniu
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['imie'])) {
    $imie = $_POST['imie'];
    $sql .= " WHERE imie LIKE '%$imie%'";


}

// Sprawdzenie, czy użytkownik wybrał określoną średnią ocen
if(isset($_POST['srednia']) && $_POST['srednia'] != 'all') {
    $sredniaValue = $_POST['srednia'];
    $sql .= " GROUP BY id, imie, nazwisko HAVING ROUND(AVG(ocena), 1) = $sredniaValue";
} else {
    $sql .= " GROUP BY id, imie, nazwisko";
}

// Obsługa sortowania
if(isset($_GET['sort']) && isset($_GET['order'])) {
    $sort = $_GET['sort'];
    $order = $_GET['order'];
    $sql .= " ORDER BY $sort $order";
}





$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<form id='deleteForm' method='post'>
            <table>
                <tr>
                    <th onclick='sortTable(0)' class='colored'>Imię </th>
                    <th onclick='sortTable(1)' class='colored'>Nazwisko </th>
                    <th class='colored'>Oceny</th>
                    <th onclick='sortTable(4)' class='colored'>Średnia</th>
                    
                </tr>";
    // Wyświetlanie danych dla każdego wiersza
    while ($row = $result->fetch_assoc()) {
        $srednia = $row["srednia"];
        $sredniaColor = '#FF0000'; 

        // Warunki zmieniające kolor w zależności od wartości średniej oceny
        if ($srednia >= 1.0 && $srednia < 2.0) {
            $sredniaColor = '#FF0000'; // Kolor czerwony dla średniej oceny 1.0 - 2.0
        } elseif ($srednia >= 2.0 && $srednia < 3.0) {
            $sredniaColor = '#5D1DC3'; // Kolor fioletowy dla średniej oceny 2.0 - 3.0
        } elseif ($srednia >= 3.0 && $srednia < 4.0) {
            $sredniaColor = '#DE6813'; // Kolor pomarańczowy dla średniej oceny 3.0 - 4.0
        } elseif ($srednia >= 4.0 && $srednia < 5.0) {
            $sredniaColor = '#2165ED'; // Kolor niebieski dla średniej oceny 4.0 - 5.0
        } elseif ($srednia >= 5.0 && $srednia <= 5.9) {
            $sredniaColor = '#035E1D'; // Kolor zielony dla średniej oceny 5.0 - 5.9
        } elseif ($srednia == 6.0) {
            $sredniaColor = '#FFF701'; // Kolor jasnożółty dla średniej oceny 6.0
        }

        echo "<tr class='record-row'>
                <td class='colored'>" . $row["imie"] . "</td>
                <td class='colored'>" . $row["nazwisko"] . "</td>
                <td class='colored' style='background-color: $sredniaColor'>" . $row["oceny"] . "</td>
                <td class='colored' style='background-color: $sredniaColor'>" . number_format($row["srednia"], 1) . "</td>
                <td><button type='button' class='delete-button' onclick='deleteRecord(" . $row["id"] . ")'>Usuń</button></td>
              </tr>";
    }
    echo "</table></form>";
} else {
    echo "Na liście nie ma takiego ucznia";
}

$conn->close();
?>

<script>
    function deleteRecord(recordId) {
        if (confirm("Czy na pewno chcesz usunąć ten rekord?")) {
            const form = document.getElementById('deleteForm');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'deleteRecords[]';
            input.value = recordId;
            form.appendChild(input);
            form.submit();
        }
    }
</script>
</body>
</html>
