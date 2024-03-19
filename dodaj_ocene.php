<?php
// Funkcja do sprawdzania poprawności danych wejściowych
function validateInput($data) {
    $data = trim($data); // Usunięcie białych znaków z początku i końca
    $data = stripslashes($data); // Usunięcie znaków ucieczki
    $data = htmlspecialchars($data); // Zamiana znaków specjalnych na encje HTML
    return $data;
}

// Sprawdzenie, czy formularz został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdzenie, czy wszystkie wymagane pola są wypełnione
    if (isset($_POST["imie"]) && isset($_POST["nazwisko"]) && isset($_POST["ocena"])) {
        // Pobranie i walidacja danych z formularza
        $imie = validateInput($_POST["imie"]);
        $nazwisko = validateInput($_POST["nazwisko"]);
        $ocena = intval($_POST["ocena"]); // Konwersja na liczbę całkowitą

        // Sprawdzenie, czy ocena mieści się w zakresie 1-6
        if ($ocena < 1 || $ocena > 6) {
            echo "Ocena musi być liczbą całkowitą z zakresu 1-6.";
            exit(); // Przerwanie działania skryptu
        }

        // Połączenie z bazą danych
        $servername = "localhost";
        $username = "root"; // Twoja nazwa użytkownika MySQL
        $password = ""; // Twoje hasło MySQL
        $dbname = "dziennik";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Sprawdzenie połączenia
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Przygotowanie zapytania SQL z użyciem zapytania przygotowanego
        $sql = "INSERT INTO oceny (imie, nazwisko, ocena) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $imie, $nazwisko, $ocena);

        // Wykonanie zapytania
        if ($stmt->execute()) {
            echo "Ocena została dodana pomyślnie.";
            echo "<br><a href='add-form.php'>Dodaj kolejną ocenę</a>"; // Dodanie linku do formularza
        } else {
            echo "Błąd: " . $sql . "<br>" . $conn->error;
        }

        // Zamknięcie połączenia
        $stmt->close();
        $conn->close();
    } else {
        echo "Wszystkie pola muszą być wypełnione.";
    }
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
