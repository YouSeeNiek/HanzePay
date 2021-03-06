<?php


@require ("./functions/functions.php");

$voornaamErr = $achternaamErr = $emailErr = $wachtwoordErr = $studentnummerErr = $geslachtErr = "";

$emptyMsg = "Dit veld is verplicht";
$specialCharMsg = "Dit veld mag geen speciale tekens bevatten";

if (isset($_POST['registreren'])) {
    // Van de data uit het form op.
    $voornaam = $_POST['voornaam'];
    $achternaam = $_POST['achternaam'];
    $email = $_POST['email'];
    $wachtwoord = $_POST['wachtwoord'];
    $studentnummer = $_POST['studentnummer'];
    // Alleen het geslacht opvangen als er een geslecteerd is.
    if (!empty($_POST['geslacht'])) {
        $geslacht = $_POST['geslacht'];
    }

    // Zet error bericht als er:
    // Geen voornaam is mee gegeven.
    // Een spatie in de naam zit.
    // Er speciale leestekens in de naam zitten.
    if (empty($voornaam)) {
        $voornaamErr = $emptyMsg;
    } elseif (preg_match('/\s/', $voornaam)) {
        $voornaamErr = "Je voornaam mag geen spatie bevatten";
    } elseif (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬]/', $voornaam)) {
        $voornaamErr = $specialCharMsg;
    } elseif (preg_match('#[0-9]#', $voornaam)) {
        $voornaamErr = "Mag geen cijfers gebruiken";
    }

    // Zet error bericht als er:
    // Geen geslacht is geselecteerd.
    if (empty($geslacht)) {
        $geslachtErr = "Er moet een geslacht worden geselecteerd";
    }

    // Zet error bericht als er:
    // Geen voornaam is mee gegeven.
    // Er speciale leestekens in de naam zitten.
    if (empty($achternaam)) {
        $achternaamErr = $emptyMsg;
    } elseif (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $achternaam)) {
        $achternaamErr = $specialCharMsg;
    } elseif (preg_match('#[0-9]#', $achternaam)) {
        $achternaamErr = "Mag geen cijfers bevatten";
    }

    // Zet error bericht als er:
    // Geen email is mee gegeven.
    if (empty($email)) {
        $emailErr = $emptyMsg;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Dit is geen correct email adres";
    } elseif (preg_match('/\s/', $email)) {
        $emailErr = "Je email mag geen spatie bevatten";
    }

    // Zet error bericht als er:
    // Geen wachtwoord is mee gegeven.
    // Minder dan 8 karakters in het wachtwoord zitten.
    if (empty($wachtwoord)) {
        $wachtwoordErr = $emptyMsg;
    } elseif (strlen($wachtwoord) < 8) {
        $wachtwoordErr = "Het wacht woord moet 8 of meer karakters bevatten";
    } elseif (preg_match('/\s/', $wachtwoord)) {
        $wachtwoordErr = "Je email mag geen spatie bevatten";
    }

    // Zet error bericht als er:
    // Geen studentnummer is mee gegeven.
    // Geen geldig studentnummer is mee gegeven.
    if ((!empty($studentnummer)) && (strlen($studentnummer) < 6 || strlen($studentnummer) > 6) && is_numeric($studentnummer)) {
        $studentnummerErr = "Dit is geen geldig studentnummer";
    } elseif (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $studentnummer)) {
        $studentnummerErr = $specialCharMsg;
    }

    // Als er geen error messages zijn voeg dan de user toe aan de databases
    if (empty($voornaamErr) && empty($achternaamErr) && empty($emailErr) && empty($wachtwoordErr) && empty($studentnummerErr) && empty($geslachtErr)) {

        // Als de gebruiker niet bestaat de user toevoegen.
        if (user_exists($email)) {
            if (studentnummer_registerd($studentnummer)) {
                $naam = $voornaam;
                $naam .= " " . $achternaam;

                // Roep functie aan die user aanmaakt
                registreerNieuweUser($naam, $email, md5($wachtwoord), $studentnummer, $geslacht);
            } else {
                $studentnummerErr = "Er is al een gebruiker met dit student nummer";
            }
        } else {

            // Geef error dat er al een gebruiker met dit mail adres is.
            $emailErr = "Dit email adres is al geregistreert";
        }

    }
}

