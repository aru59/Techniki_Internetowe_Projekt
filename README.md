Struktura plików:
- `index.php` - Strona główna aplikacji. Zawiera formularz logowania, rejestracji oraz sekcję do wprowadzania parametrów rzutu poziomego.
- `login.php` - Obsługuje rejestrację i logowanie użytkownika przy użyciu SQLite.
- `save_parameters.php` - Obsługuje zapisywanie parametrów użytkownika w bazie danych.
- `load_parameters.php` - Umożliwia wczytanie zapisanych parametrów z bazy danych.
- `parameters.php` - Strona wyświetlająca zapisane parametry użytkownika.
- `rzutPoziomy.js` - Główna logika animacji rzutu poziomego i obsługa interfejsu użytkownika.
- `styles.css` - Stylizacja projektu, w tym responsywny układ i estetyczne formularze.
- `users.db` - Baza danych SQLite do przechowywania informacji o użytkownikach i ich parametrach.
- `assets/` - Katalog zawierający grafikę używaną w projekcie:
  - `rzut.png` - Ilustracja teoretyczna rzutu poziomego.
  - `Rzutpoziomy.gif` - Animacja rzutu poziomego.


## Technologie

**HTML5**:
Struktura strony z użyciem semantycznych elementów oraz renderowanie formularzy i animacji w <canvas>.
**CSS3**:
Stylizacja strony,
**JavaScript**:
Obsługa animacji rzutu poziomego, dynamiczna manipulacja DOM oraz interakcja użytkownika z formularzami.
**PHP**:
Obsługa logiki serwera: logowanie, rejestracja, zapis i odczyt parametrów.
**SQLite**:
Przechowywanie danych użytkownika.
**AJAX**:
Dynamiczna komunikacja między klientem a serwerem.
