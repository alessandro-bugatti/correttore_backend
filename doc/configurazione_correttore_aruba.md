# Come installare il correttore sul Cloud VPS di Aruba

## Creazione del server
Nel pannello di controllo Cloud di Aruba scegliere la taglia Small, che attualmente costa 2,79 euro al mese, poi procedere con le impostazioni di default, nel mio caso ho scelto come sistema operativo Ubuntu 18.04 LTS. Dopo che è stato creato si può accedere all'indirizzo fornito tramite SSH, nel processo di creazione verrà chiesto di impostare una password per l'accesso **root**, che è l'utente creato di default.

## Accesso al server tramite SSH 
Per accedere al server si può usare un qualsiasi client SSH, sotto Windows si può usare **putty**, sotto Linux il client a linea di comando **ssh**. Si consiglia di usare una password robusto nella scelta precedente, perchè, essendo un server su Internet, i bot della rete continuano a provare ad accedere utilizzando le password più comuni (tipo *root*, *toor*, ecc.), nella mia esperienza almeno un attacco al minuto.

## Configurazione del server LAMP
Esistono divrsi modi per configurare il server, che nella versione originale non ha nessun particolare applicativo se non quelli standard di gestione del sistema, per farlo diventare un server LAMPP. Nella mia esperienza il modo più semplice è quello di usare l'applicativo **tasksel**, già presente sul server. Eseguire quindi il comando:
```bash
sudo tasksel
```
e selezionare, tra le varie opzioni proposte, quella indicata come *LAMP server*. Una volta scelto OK il programma si occuperà di installare i pacchetti necessari per avere un server LAMP (quindi Apache2, MySQL e PHP) funzionante. Per verificarne il corretto funzionamento inserire nella barra degli indirizzi l'indirizzo IP del proprio server e dovrebbe apparire la pagina di default di Apache2.
Se poi si volesse accendere o spegnere il server Web si ricorda che i comandi sono:
```bash
sudo service apache2 start
sudo service apache2 stop
```

In questa distribuzione la *root* del server Web si trova nella cartella */var/ww/html*.

## Abilitare il modulo *rewrite* di Apache2
La piattaforma di correzione ha bisogno che sia abilitato il modulo *rewrite* di Apache2 per fare funzionare correttamente il routing della richieste.
Il comando da eseguire per abilitarlo è il seguente:
```bash
sudo a2enmod rewrite
```
Successivamente sarà necessario far ripartire Apache2 per attivarlo
```bash
sudo service apache2 restart
```

## Abilitare il funzionamento del file *.htaccess*
Di default Apache2 non leggerà i file *.htaccess* che sono necessari all'applicativo per funzionare. Per abilitarli è necessario editare il file di configurazione di Apache2, */etc/apache2/apache2.conf*, ad esempio usando *nano* in questo modo
```bash
sudo nano /etc/apache2/apache2.conf
```
e modificando le righe dove appare
```bash
	AllowOverride None
```
con
```bash
	AllowOverride All
```
e poi riavviare Apache2 per renderle effettive
```bash
sudo service apache2 restart
```


## Installazione  del correttore

La procedura più semplice è quella di copiare tramite *sftp*, tutti i file presenti nel correttore originale, zippati per comodità nella [release](https://github.com/alessandro-bugatti/correttore_backend/releases) all'interno del file *correttore.zip*. Sotto Windows si può usare il client grafico **Filezilla**, mentre in Linux si può usare **sftp**. I file dovranno essere copiati nella cartella *root* di Apache2, il file *index.html* di default già presente può essere rinominato, spostato o cancellato.
Dopo aver copiato i file dovrebbero essere presenti il file *index.php*, il file *correttore.sql* e le due cartelle *c2* e *correttoreapi*.
A questo punto bisogna installare il database. Come primo passaggio è necessario creare il database e l'utente che lo dovrà utilizzare: per entrare in MySQL da riga di comando è sufficiente digitare:
```bash
mysql -u root -p
```
Alla richiesta della password non inserire nulla, perchè nell'installazione di default l'utente *root* viene autenticato in base al PID del sistema operativo dell'utente attuale (vuol dire che se un utente malevolo prende possesso del vostro sistema impersonando *root*, avrà accesso anche al database).
Una volta avuto accesso a MySQL per creare il database basta scrivere:
```sql
create database correttore;
```
e per creare l'utente che userà l'applicazione per accedere al database, che di default è 'correttore' con password 'correttore', si dovrà digitare l'istruzione:
```sql
CREATE USER 'correttore'@'localhost' IDENTIFIED BY 'correttore';
GRANT ALL PRIVILEGES ON correttore.* TO 'correttore'@'localhost';
FLUSH PRIVILEGES;
```
Se poi si volessero cambiare questi parametri, bisogna ricordarsi di cambiarli anche all'interno del file di configurazione dell'applicazione, che è *correttoreapi/correttoreAPI/conf.php*
L'ultimo passo è quello di inserire il database con alcuni dati, utilizzando il comando:
```bash
mysql -u correttore -p -Dcorrettore < correttore.sql
```
dove *correttore.sql* è il file che contiene le informazioni di base legate all'installazione di default, già presente nel file *correttore.zip* scaricato precedentemente.
Adesso è necessario installare due librerie che solitamente non sono installate nella distribuzione standard: una libreria di PHP, *mb_string*, che è necessaria per alcune funzionalità del server e il programma *unzip*, che permette di decomprimere alcuni file durante la fase di correzione dei test.
I comandi da lanciare sono:
```bash
sudo apt install php7.2-mbstring
sudo apt install unzip
```
L'ultimo passaggio è quello di rendere scrivibile la cartella *temp*, che viene utilizzata per effettuare i test, e la cartella *users* dall'utente *www-data*, che è quello che esegue il web server Apache2.
La cosa più semplice da fare è di cambiare il proprietario di quelle cartelle da *root* a *www-data* con il seguente comando:
```bash
sudo chown www-data:www-data temp
sudo chown www-data:www-data users
```
comando che deve essere lanciato posizionandosi all'interno della cartella *correttoreapi*.



## Test del server
Per il test del server è sufficiente andare con il proprio browser all'indirizzo del server e nella schermata di login inserire una di queste password di default

|User|Password|Ruolo|
|---|---|---|
|alex|pippo|teacher|
|admin|pippo|admin|
|alekos|pippo|student|

## Messa in sicurezza del server MySQL
Siccome nella configurazione di MySQL installata di default alcune impostazioni sono pensate per l'utilizzo in fase di sviluppo, per renderlo più sicuro in produzione esiste uno script che permette automaticamente di renderlo un po' più sicuro.
Basta digitare il comando
```bash
sudo /usr/bin/mysql_secure_installation
```
e seguire le istruzioni indicate, per inserire una password di *root* robusta e rispondere sempre *Yes* a tutte le altre opzioni.
Attenzione: il fatto di avere inserito una password di root non impedirà di continuare ad autenticarsi da shell senza usare la password, se si vuole 'abilitare' l'utilizzo di quella password bisogna cambiare metodo di autenticazione ([guardare qui per chi volesse approfondire](https://dev.mysql.com/doc/mysql-secure-deployment-guide/5.7/en/secure-deployment-configure-authentication.html))



