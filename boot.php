<?php

$cwd = __DIR__ ;
$pfad = "/";

echo "\033[1;32mOidaOS v0.1 bootet...\033[0m\n";
sleep(1);
echo "Willkommen beim Simulierten \033[1mOidaOS\033[0m! Gib \033[33mexit\033[0m ein zum Beenden.\n";
echo "willst du alle commmands sehen ? schreib : \033[33mhilfe\033[0m\n";

while (true) {
    echo "\n\033[34m$pfad> \033[0m";
    $eingabe = trim(fgets(STDIN));

    if ($eingabe === "exit") {
        echo "Bis zum nächsten mal, Oida!\n";
        break;
    }

    $teile = explode(" ",$eingabe);
    $befehl = strtolower($teile[0]);
    $argument = $teile[1]?? "";

    switch ($befehl) {
        case "ordner?":
            $ordner = realpath($cwd . $pfad);
            if (is_dir($ordner)) {
                $eintraege = scandir($ordner);
                foreach ($eintraege as $eintrag) {
                    if ($eintrag === "." || $eintrag === "..") continue;
                    echo $eintrag . "\n";
                }
            } else {
                echo "is leer haha\n";
            }
            break;

        case "cd":
            if($argument === "..")  {
                $pfad = dirname($pfad);
                if ($pfad === "\\" || $pfad === ".") $pfad = "/";
                break;
            }

            $ziel = realpath($cwd . $pfad . "/" . $argument);

            if ($ziel && str_starts_with($ziel, $cwd) ) {
                $pfad = str_replace($cwd, "", $ziel);
            } else {
                echo "Des Verzeichnis gibt's ned.\n";
            }
            break;

        case "lösch":
            passthru("/usr/bin/clear");
            break;

        case "start":
            $programmPfad = realpath($cwd . $pfad . "/" . $argument);
            if ($programmPfad && str_ends_with($programmPfad, ".oida")) {
                echo "Starte $argument...\n";
                $oidaInterpreterPfad = __DIR__ . "/oida.php";
                $befehl = "php " . $oidaInterpreterPfad . " " . $programmPfad;
                passthru($befehl);
            } else {
                echo "Konnte Programm nicht starten (existiert's?).\n";
            }
            break;

        case "hilfe":
            echo "commands:\n";
            echo 'welche ordern gibt es in deinem aktuellen verzeichnis: ordner? ' . "\n";
            echo 'rein in einen Ordner: "cd ordnerName"' . "\n";
            echo 'einen ordner hoch : "cd .."' . "\n";
            echo 'leert das cli : "lösch"' . "\n";
            echo 'oida file executen: "start oidafile.oida" '. "\n";
            break;

        default:
            echo "Unbekannter Befehl: $befehl\n";
    }
}
