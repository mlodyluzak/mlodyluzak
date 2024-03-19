<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formularz dodawania oceny</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Domyślnie jasnoszare tło */
            margin: 0;
            padding: 0;
            transition: background-color 0.3s ease; /* Dodane przejście */
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #000; /* Domyślnie czarne tło kontenera */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease; /* Dodane przejście */
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            color: #fff; /* Domyślnie biały kolor etykiet */
            transition: color 0.3s ease; /* Dodane przejście */
        }
        input[type="text"],
        input[type="number"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f4f4f4; /* Domyślnie jasnoszary kolor tła pól tekstowych */
            transition: background-color 0.3s ease; /* Dodane przejście */
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4caf50; /* Domyślnie zielony kolor tła przycisku */
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Dodane przejście */
        }
        input[type="submit"]:hover {
            background-color: #f44336; /* Zmiana koloru na czerwony po najechaniu */
        }
        .slider-container {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 200px;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.3s ease; /* Dodane przejście */
            display: flex;
            align-items: center;
            justify-content: space-between; /* Umieszczenie ikony i napisu na końcach */
        }
        .slider {
            -webkit-appearance: none;
            appearance: none;
            width: calc(100% - 30px); /* Dostosowanie szerokości do umieszczenia ikony i napisu */
            height: 10px;
            border-radius: 5px;
            background: linear-gradient(to right, #f00, #ff7f7f); /* Domyślnie czerwony do jasnoczerwonego */
            outline: none;
            opacity: 0.7;
            transition: opacity 0.3s ease, background-color 0.3s ease; /* Dodane przejście */
        }
        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Dodane przejście */
        }
        .slider:hover {
            opacity: 1; /* Pokazanie suwaka po najechaniu myszką */
        }
        .red-outline {
            text-shadow: -1px -1px 0 #f00, 1px -1px 0 #f00, -1px 1px 0 #f00, 1px 1px 0 #f00; /* Dodana czerwona obwódka */
        }
        h2 {
            color: #fff; /* Kolor biały dla napisu "Formularz dodawania oceny" */
            margin-top: 0; /* Usunięcie marginesu górnego */
            transition: color 0.3s ease; /* Dodane przejście */
        }
        .icon {
            font-size: 24px;
        }
        .icon.sun {
            color: #ff0; /* Kolor żółty dla ikony słońca */
        }
        .icon.moon {
            color: #777; /* Kolor szary dla ikony księżyca */
        }
    </style>
</head>
<body>
<div class="container">
        <h2>Formularz dodawania oceny</h2>
        <form action="dodaj_ocene.php" method="post">
            <label for="imie">Imię:</label>
            <input type="text" id="imie" name="imie"  pattern="[A-Za-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ]+" title="Imię może zawierać tylko litery"  required>
            
            <label for="nazwisko">Nazwisko:</label>
            <input type="text" id="nazwisko" name="nazwisko" pattern="[A-Za-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ]+" title="Nazwisko może zawierać tylko litery" required>
            
            <label for="ocena">Ocena:</label>
            <input type="text" id="ocena" name="ocena" pattern="^[1-6]$" title="Ocena musi być z przedziału od 1 do 6 " required>
            
            <input type="submit" value="Dodaj ocenę" id="dodajOcene">
        </form>
    </div>

    <div class="slider-container">
        <span class="icon sun">&#9728;</span> <!-- Ikona słońca przy czerwonym końcu paska -->
        <input type="range" id="kolorTla" class="slider" min="0" max="100" value="0"> <!-- Odwrócona wartość suwaka -->
        <span class="icon moon">&#9790;</span> <!-- Ikona księżyca przy czarnym końcu paska -->
    </div>

    <script>
        const rangeInput = document.getElementById('kolorTla');
        const colorLabel = document.getElementById('colorLabel');
        const formTitle = document.querySelector('h2');
        const labels = document.querySelectorAll('label');

        rangeInput.addEventListener('input', function() {
            const value = rangeInput.value;
            const inverseValue = 100 - value;

            // Zmiana koloru tła strony w zależności od wartości suwaka
            const inverseColor = `rgb(${inverseValue * 2.55}, ${inverseValue * 2.55}, ${inverseValue * 2.55})`;
            document.body.style.backgroundColor = inverseColor;

            // Zmiana koloru tła kontenera formularza na przeciwny
            const containerColor = `rgb(${value * 2.55}, ${value * 2.55}, ${value * 2.55})`;
            document.querySelector('.container').style.backgroundColor = containerColor;

            // Zmiana koloru napisu "Formularz dodawania oceny" na jasny przy ciemnym lub czarnym tle
            if (value <= 50) {
                formTitle.style.color = '#fff'; // Kolor biały dla ciemnych lub czarnych tłów
                labels.forEach(label => label.style.color = '#fff'); // Kolor biały dla etykiet w formularzu
            } else {
                formTitle.style.color = '#000'; // Kolor czarny dla jasnych tłów
                labels.forEach(label => label.style.color = '#000'); // Kolor czarny dla etykiet w formularzu
            }

            // Zmiana koloru suwaka na niebieski lub czerwony
            const sliderColor = `rgb(${value * 2.55}, 0, ${inverseValue * 2.55})`;
            rangeInput.style.background = `linear-gradient(to right, #f00, ${sliderColor}, #00f)`;
        });
    </script>
</body>
</html>
