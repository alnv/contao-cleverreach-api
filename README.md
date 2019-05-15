# Contao Cleverreach API

## Authorization
Die OAuth Daten kannst du in deine localconfig.php eintragen.

``` php
<?php
$GLOBALS['TL_CONFIG']['cleverreachClientId'] = '';
$GLOBALS['TL_CONFIG']['cleverreachClientSecret'] = '';
$GLOBALS['TL_CONFIG']['cleverreachAuthUrl'] = 'https://rest.cleverreach.com/oauth/authorize.php';
$GLOBALS['TL_CONFIG']['cleverreachTokenUrl'] = 'https://rest.cleverreach.com/oauth/token.php';
```

## Formulargenerator
Das Formular für die Newsletter Anmeldung kannst Du mit dem Formulargenerator erstellen. Aktiviere dabei die *Cleverreach API verwenden* Checkbox. Optional kannst du auch eine Aktivierungsmail auswählen. 

## Zuordnung der Formularfelder
Damit die Zuordnung korrekt funktioniert, müssen die Formularfelder bestimmte Namen haben:
* email = E-Mail-Adresse (Pflichtfeld)
* newsletter = Gruppen IDs (Pflichtfeld)
* gender = Geschlecht (male/female)
* firstname = Vorname
* lastname = Nachname
