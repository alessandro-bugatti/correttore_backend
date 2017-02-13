# README #

Questo parte è il backend del sistema di correzione automatico, che può essere usato per gestire delle prove di programmazione o delle gare.

### Come installare

La presente guida mostra un possibile percorso di installazione del backend (PHP+MySQL) e del frontend (AngularJS) per avere a disposizione un sistema funzionante in locale (può sembrare un processo lungo, ma seguendo le istruzioni in 30 minuti si è "up and running").

#### Creazione di una macchina virtuale
La macchina virtuale sarà dove risiederà il sistema, si è scelto Ubuntu Server 16.04 da installare su VirtualBox, qualsiasi sistema Linux moderno dovrebbe essere altrettanto adeguato, le istruzioni che seguiranno comunque sono state testate solo su questa versione.

Partendo dalla ISO per l'installazione, bisogna selezionare, quando richiesto, l'opzione per configurare un server LAMP (necessario) e un server OpenSSH (opzionale, ma comodo).

Una volta creata la macchina virtuale è necessario installare anche alcuni strumenti che servono alla correzione dei task e poi si potrà procedere con l'installazione del backend e del frontend.   

### Installazione dei tool di correzione

#### Correttore programmi in C/C++
Il correttore nasce con lo scopo di permettere la correzione automatica di codici scritti in C/C++, quindi questo è l'unico modulo necessario, in futuro ne verranno sviluppati altri.
Serve quindi un ambiente di compilazione, con Ubuntu è sufficiente scrivere il seguente comando:

```bash
sudo apt-get install build-essential
```

Inoltre va compilato il programma che effettivamente si occuperà della correzione, eseguendo il codice sottoposto dall'utente su vari casi di test. Per fare questo bisogno compilare il sorgente *driver.c* presente nella cartella *bin* con il seguente comando

```bash
gcc bin/driver.c -o bin/driver
```

### Installazione del backend
Il backend è stato creato utilizzando Silex, un microframework PHP, e RedbeanPHP come ORM per la connessione al database. La strada più comoda è quella di clonare tramite ```git``` il repository del backend. Prima di far questo può essere comodo spostarsi nella cartella *root* di Apache, che in questa distribuzione è */var/www/html* con il seguente comando:

```bash
cd /var/www/html
```
e successivamente digitare il comando

```bash
sudo git clone https://alessandro_bugatti@bitbucket.org/correttore2/correttoreapi.git
```

A questo punto è stata creata la cartella *correttoreapi* con all'interno l'applicazione, devono però essere installate tutte le librerie. Per far questo bisogna installare come prima cosa ```composer```, il gestore delle dipendenze standard di PHP, tramite il seguente comando:

```bash
sudo apt-get install composer
```

Inoltre è necessario installare i programmi ```zip``` e ```unzip``` che di default non sono installati e che sono necessari per il corretto funzionamento di ```composer```. e la libreria ```mb_string``` di PHP, anch'essa non installata di default in PHP 7:

```bash
sudo apt-get install zip unzip
sudo apt-get install php7.0-mbstring
```

Ci si può ora spostare nella cartella *correttoreapi* e scaricare le dipendenze:

```bash
cd correttoreapi
sudo composer update
```

Siccome il sistema, per alcune operazioni, ha necessità di scrivere in alcune directory, è necessario cambiare i permessi di questa cartella con il seguente comando:

```bash
sudo chown -R www-data:www-data *
```

A questo punto bisogna modificare il file di configurazione di Apache in modo da permettere la lettura del file .htaccess. Si apra quindi il file /etc/apache2/apache2.conf con un editor, ad esempio ```nano```, e cercare le righe seguenti dove, come indicato, nelle directory / e /var/www va settato **AllowOverride All** al posto di **AllowOverride None**

```bash
sudo nano /etc/apache2/apache2.conf
```

```
<Directory />
        Options FollowSymLinks
        AllowOverride All    # questa
        Require all denied
</Directory>

<Directory /usr/share>
        AllowOverride None
        Require all granted
</Directory>

<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All    # e questa
        Require all granted
</Directory>
```
Inoltre è necessario abilitare ```mod_rewrite``` per permettere a Silex di interpretare correttamente le *route*

```bash
sudo a2enmod rewrite
```

Poi bisogna riavviare Apache in questo modo, per rendere le modifiche effettive:

```bash
sudo service apache2 restart
```

A questo punto bisogna cambiare i parametri del database e anche crearlo.
Come prima cosa conviene creare un utente non root per l'accesso al database, e lo si può fare prima entrando nel client a linea di comando (ovviamente bisogna sapere la password di root creata al momento dell'installazione)

```bash
mysql -u root -p
```

e poi nella shell di MySQL eseguire i seguenti comandi

```sql
mysql> CREATE DATABASE correttore;
mysql> GRANT ALL ON correttore.* to 'correttore'@'locahost' IDENTIFIED BY 'correttore';
```

dove la prima riga crea il database e la seconda l'utente *correttore* con password *correttore* che avrà i permessi per accedere al database (inutile dire che questo è solo un esempio e la password deve essere sicura).

Bisogna poi modificare i file di configurazione per inserire questi nuovi dati: il primo che va modificato è il file che si trova nella cartella *init* e si chiama **conf.php**. Come al solito si apre nano

```bash
nano init/conf.php
```
 e si cambia la riga dei parametri di configurazione nel seguente modo

```php
R::setup( 'mysql:host=127.0.0.1;dbname=correttore',
        'correttore', 'correttore' );
```

Per verificare che tutto funzioni si può ora creare il database con le tabelle e qualche dato eseguendo lo script **init.sh**:
```bash
sudo ./init.sh
```

Se a questo punto si vuole controllare nel db si dovrebbero trovare le tabelle create con dei dati di prova.

Come ultimo passaggio per il backend bisogna cambiare un altro file di configurazione con gli stessi parametri più quello che indica il percorso dove è stata inserita l'applicazione: si tratta del file **conf.php** che si trova nella cartella *correttoreAPI*. Sempre con nano si modificano le seguenti righe

```php
//Database configuration for RedBean
$app['redbean.database'] = 'mysql:host=127.0.0.1;dbname=correttore';
$app['redbean.username'] = 'correttore';
$app['redbean.password'] = 'correttore';

//Enable the application to be installed in a subfolder
//inside Apache root
$app['subdir'] = 'correttoreapi';
```
A questo punto si può provare se il backend funziona:
1. settare una regola nel NAT di VirtualBox il NAT, ad esempio esponendo la porta 80 della macchina virtuale sulla 8080 della "rete" della macchina fisica
2. aprire un browser dal proprio sistema operativo host, inserendo l'indirizzo **http://localhost:8080/v1/public/categories**. Si dovrebbe vedere nel browser questa scritta

> [{"id":"1","description":"Sequenza","type":"Programmazione"},{"id":"2","description":"Selezione","type":"Programmazione"},{"id":"3","description":"Input\/output","type":"Programmazione"}]   

Se si vede questo il backend è correttamente installato e funzionante.

### Installazione del frontend
Per il frontend la configurazione è molto più semplice. Innanzitutto, sempre rimanendo nella cartella /var/www/html, si clona con ```git``` il seguente repository

```bash
sudo git clone https://alessandro_bugatti@bitbucket.org/correttore2/correttore2.bitbucket.org.git
```

A questo punto conviene rinominare la cartella *correttore2.bitbucket.org* in *c2*, per comodità

```bash
sudo mv correttore2.bitbucket.org/ c2
```

Come ultimo passaggio bisogna aprire il file **script.6e2e97c6.js** (il numero centrale potrebbe anche essere diverso, non è importante) che si trova nella cartella *script* e cercare la stringa **https://auth-silex-test-alessandro-bugatti.c9users.io** e sostituirla con **http://localhost:8080/correttoreapi**

Se tutto è stato fatto correttamente, andando con il browser all'indirizzo **http://localhost:8080/c2** si dovrebbe vedere la pagina di login dell'applicazione, a cui si può accedere con le seguenti credenziali di default:

|User|Password|Ruolo|
|---|---|---|
|alex|pippo|teacher|
|admin|pippo|admin|
|alekos|pippo|student|

### Ultimi settaggi per utilizzo in una rete locale
Siccome probabilmente lo scopo è quello di poterlo utilizzare in una rete locale, sono necessari ancora due passaggi:
1. Se si vuole esporre il proprio computer su una rete locale come server, è necessario cambiare l'indirizzo inserito nella pagina **script.6e2e97c6.js**, usando l'IP assegnato alla propria macchina nella rete, e la stessa cosa deve essere fatta aggiungendo una nuova regola del NAT in VirtualBox (o sostituendo quella relativa a localhost).
2. Per rendere il processo di utilizzo ancora più intuitivo conviene creare un file **index.php** nella root di Apache (/var/www/html), con il seguente contenuto:

```php
<?php
header("Location: http://192.168.6.134/c2");
?>
```


In questo esempio l'IP della macchina che farà da server é **192.168.6.134** e,grazie a questo file, chi vuole utilizzare il sistema dovrà solo inserire nella barra degli indirizzi del browser questo IP e gli verrà mostrata la pagina del correttore.

### Contribution guidelines ###

* Writing tests
* Code review
* Other guidelines

### Who do I talk to? ###

* Repo owner or admin
* Other community or team contact
