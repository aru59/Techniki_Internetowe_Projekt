<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$db = new SQLite3('users.db');
if (isset($_SESSION['logged_in'])) {
    $stmt = $db->prepare('SELECT velocity, height, gravity FROM users WHERE username = :username');
    $stmt->bindValue(':username', $_SESSION['username']);
    $result = $stmt->execute();
    $user = $result->fetchArray();
    $velocity = $user['velocity'];
    $height = $user['height'];
    $gravity = isset($user['gravity']) ? $user['gravity'] : 9.81;
} else {
    $gravity = 9.81;
}
if (isset($_GET['logout'])) {
    unset($_SESSION['logged_in']);
    header('Location: index.php');
    exit;
}
if (isset($_SESSION['message'])) {
    echo '<script>alert("' . $_SESSION['message'] . '");</script>';
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rzut Poziomy</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header>
        <h1>Rzut Poziomy</h1>
    </header>
    <main>
    <div class="form-container">
    <nav>
        <?php
        if (isset($_SESSION['logged_in'])): ?>
            <button onclick="window.location.href='parameters.php'">Zapisane Parametry</button>
            <button onclick="window.location.href='index.php?logout=true'">Wyloguj się</button>
        <?php else: ?>
            <form id="login" action="login.php" method="post">
                <input type="text" id="usernamelogin" name="username" placeholder="Username"><br>
                <input type="password" id="passwordlogin" name="password" placeholder="Password"><br>
                <input type="submit" name="login" value="Zaloguj się">
            </form>
            <form id="register" action="login.php" method="post">
                <input type="text" id="usernameregister" name="username" placeholder="Username"><br>
                <input type="password" id="passwordregister" name="password" placeholder="Password"><br>
                <input type="submit" name="register" value="Zarejestruj się">
            </form>
        <?php endif; ?>
    </nav>
    <form id="parameters" action="save_parameters.php" method="post">
        <label for="velocity">Początkowa prędkość(m/s):</label><br>
        <input type="number" id="velocity" name="velocity" step="any" value="<?php echo isset($velocity) ? $velocity : ''; ?>" required><br>
        <label for="height">Początkowa wysokość(m):</label><br>
        <input type="number" id="height" name="height" step="any" value="<?php echo isset($height) ? $height : ''; ?>" required><br>
        <label for="gravity">Przyspieszenie grawitacyjne(m/s<sup>2</sup>):</label><br>
        <input type="number" id="gravity" name="gravity" step="any" value="<?php echo isset($gravity) ? $gravity : ''; ?>" required><br>
        <input type="submit" value="Start" name="animate">
        <?php if (isset($_SESSION['logged_in'])): ?>
        <button type="button" id="loadParameters">Wczytaj Zapisane Parametry</button>
        <button type="button" id="saveParameters">Zapisz parametry użytkownika</button>
      <?php endif; ?>
    </form>
        
      </div>
    
    <article class="explanation">
        <h2>Wyjaśnienie rzutu poziomego</h2>
        <p><strong>Opis ruchu:</strong> W rzucie poziomym mamy do czynienia z lotem ciała wyrzuconego na pewnej wysokości H0 nad poziomem zerowym. Ciału jest nadawana pozioma prędkość początkowa o wartości v0. Dzięki takiemu nadaniu prędkości przesuwa się ono cały czas w poziomie.</p>
        <p>Jednocześnie jednak siła grawitacji zmienia pionowe położenie ciała. W efekcie w pionie będzie ono opadać ruchem jednostajnie przyspieszonym.</p>
        <p><strong>Dzięki złożeniu tych dwóch ruchów </strong></p> 
        <p>- poziomego: jednostajnego </p> 
        <p>- pionowego: jednostajnie przyspieszonego</p> 
        <p>ciało porusza się łukiem (po paraboli, jeśli nie uwzględniamy oporu powietrza), by po pewnym czasie opaść na ziemię.</p>
        <p><strong>Warunki początkowe:</strong></p>
        <p>- Wysokość początkowa H0 - ciało rzucamy z pewnej wysokości</p>
        <p>- Prędkość początkowa: Vo - prędkość początkowa jest skierowana poziomo.</p>
        <p>- Przyspieszenie ma wartość g: przyspieszenie w tym ruchu jest stałe i cały czas jest  skierowane pionowo w dół.</p>
      </article>
        <div class="formulas-container">
            <div class="formula">    
                <h3><strong>Wzory dla ruchu poziomego bez oporu powietrza:</strong></h3>
                <p><strong>Wartość prędkości poziomej: </strong>V<sub>pozioma</sub> = Vo = const</p>
                <p><strong>Wartość prędkości pionowej: </strong>V<sub>pionowa</sub> = -gt</p>
                <p><strong>Wartość prędkości całkowitej: </strong>V= &radic;<span style="text-decoration: overline">Vo * Vo + 2 * g * H0</span></p>

                <p><strong>Zasięg poziomy: </strong>R = v<sub>0</sub> * t = V<sub>0</sub> &radic;<span style="text-decoration: overline">2 * H0 / g</span></p>
                <p><strong>Czas do momentu upadku: </strong>t = &radic;<span style="text-decoration: overline">2 * H0 / g</span></p>
                <p><strong>Prędkość w chwili uderzenia o ziemię: </strong>V<sub>s</sub> = &radic;<span style="text-decoration: overline">Vo * Vo + 2 * g * H0</span></p>
                <p><strong>Wysokość, gdzie znajduje się ciało po czasie t: </strong>h = H0 - (g/2) * t<sup>2</sup></p>
                <p><strong>Równanie toru rzutu poziomego </strong>y = H0 - (g/2) * (x<sup>2</sup>)/Vo<sup>2</sup></p>
            </div>
        </div>
          <div class="centered">
            <img src="assets/rzut.png" alt="Rzut poziomy">
            <p>W przypadku gdy nie musimy uwzględniać oporu powietrza, torem ruchu ciała jest parabola, a ruch ciała rozkłada się na ruch w poziomie i ruch w pionie. </p>

          </div>
      
          <div class="centered">
            <img src="assets/Rzutpoziomy.gif" alt="Rzut poziomy">
            <p>Animacja rzutu poziomego ze strzałkami oznaczającymi wektory prędkości</p>
          </div>
    </main>
    <div class="canvas-container">
      <canvas id="canvas" width="800" height="400"></canvas>
    </div>
    <script>
      var isLoggedIn = <?php echo isset($_SESSION['logged_in']) ? 'true' : 'false'; ?>;
    </script>
    <script src="rzutPoziomy.js"></script>
<footer>
<p>Paweł Dyjak 2025</p>
</footer>
</body>
</html>