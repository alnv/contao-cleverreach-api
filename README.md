# Contao Cleverreach API

## Dokumentation

1. Empfängerliste muss bei Cleverreach bereits eingerichtet sein (bitte beachte hierzu die Cleverreach Doku). Die Listen-ID findest du unter Empfänger -> Reiter Einstellungen -> Listen ID.
2. Cliet ID & Secret erstellen: In Mein Account -> Reiter Extras -> REST API -> OAuth anlegen. Ausfüllen und als Redirect-URL die eintragen, die der Nutzer sieht, wenn er sich erfolgreich zum Newsletter angemeldet hat (bitte beachte hierzu die Cleverreach Doku)
3. Die Mails zur Aktivierung, Abmeldung, etc. werden über Cleverreach versendet. Im Menüpunkt Formulare kann man ein neues Formular hinzufügen, es der passenden Empfängerliste zuweisen und im Reiter „Inhalt“ die verschiedenen Mails optisch und inhaltlich überarbeiten (bitte beachte hierzu die Cleverreach Doku)
3. Wechsel zu Contao: Die OAuth Daten kannst Du in deine system/config/localconfig.php eintragen.

    ``` php
    <?php
    $GLOBALS['TL_CONFIG']['cleverreachClientId'] = '';
    $GLOBALS['TL_CONFIG']['cleverreachClientSecret'] = '';
    $GLOBALS['TL_CONFIG']['cleverreachAuthUrl'] = 'https://rest.cleverreach.com/oauth/authorize.php';
    $GLOBALS['TL_CONFIG']['cleverreachTokenUrl'] = 'https://rest.cleverreach.com/oauth/token.php';
    ```

5. Formulargenerator -> Ein neues Formular zur Newsletter-Anmeldung anlegen, bestehend aus
    1. Textfeld für E-Mail-Adresse
    2. Absendefeld
    3. Bei 1 Empfängerliste: verstecktes Feld mit dem Feldnamen newsletter anlegen, der Standard-Wert ist dann die ID
    4. Bei mehreren Empfängerlisten: Auswahlliste/Checkboxen/Radio Buttons mit dem Feldnamen newsletter anlegen, der Wert ist dann jeweils die ID der Liste im Cleverreach. Dieses Feld muss ein Pflichtfeld sein. 
    5. optional: tags = Komma getrennte Liste (als verstecktes Feld)
    6. optional: Attribute = wird für die Segmente benötigt. Der Contao-Feldname muss mit dem Cleverreach-Feldnamen übereinstimmen
    7. optional sind natürlich weitere Felder möglich, je nach individuellem Anspruch
6. Setze in den Einstellungen des soeben erstellten Formulars den Haken bei „Cleverreach API verwenden“ und wähle die Mail aus, die in Cleverreach erstellt wurde
7. Prüfe, ob der Mailversand funktioniert, ggf. muss er über SMTP erfolgen (daten in parameters.yml eintragen und danach den Symfony-Cache löschen)
9. Wenn die Schnittstelle funktioniert, steht im System-Log: Cleverreach API: You have new subscriber. Den Mailversand musst du unabhängig davon testen

*Danke an [Kim Wormer](https://www.heartcodiert.de) für die Doku.*