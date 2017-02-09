# README #

Questo parte è il backend del sistema di correzione automatico, che può essere usato per gstire delle prove di programmazione o delle gare.

### Come installare

La presente guida mostra un possibile percorso di installazione del backend (PHP+MySQL) e del frontend (AngularJS) per avere a disposizione un sistema funzionante in locale.

#### Creazione di una macchina virtuale
La macchina virtuale sarà dove risiederà il sistema, si è scelto Ubuntu Server 16.04 da installare su VirtualBox, qualsiasi sistema Linux moderno dovrebbe essere altrettanto adeguato, le istruzioni che seguiranno comunque sono state testate solo su questa versione.

Partendo dalla ISO per l'installazione, bisogna selezionare, quando richiesto, l'opzione per configurare un server LAMP (necessario) e un server OpenSSH (opzionale, ma comodo).

Una volta creata la macchina virtuale si può procedere con l'installazione del backend e del frontend.   

### Installazione del backend
Il backend è stato creato utilizzando Silex, un microframework PHP e RedbeanPHP come ORM per la connessione al database. La strada più comoda è quella di clonare tramite ```git``` il repository del backend. Prima di far questo può essere comodo spostarsi nella cartella *root* di Apache, che in questa distribuzione è */var/www/html* con il seguente comando:

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
Inoltre è necessario installare i programmi ```zip``` e ```unzip``` che di default non sono installati e che sono necessari per il corretto funzionamento di composer e la libreria mb_string di PHP, anch'essa non installata di default in PHP 7:

```bash
sudo apt-get install zip unzip
sudo apt-get install php7.0-mbstring
```

Ci si può ora spostare nella cartella *correttoreapi* e scaricare le dipendenze:

```bash
cd correttoreapi
sudo composer update
```

A questo punto, per avere una configurazione funzionante e semplice da poter usare in locale, conviene spostare tutto il contenuto della cartella *correttoreapi* nella cartella superiore, la root di Apache.
Se ci si trova nella cartella *correttoreapi* si possono eseguire i seguenti comandi

```bash
cd ..
sudo mv correttoreapi/* .
sudo mv correttoreapi/.* .
```

La terza riga serve per spostare i file nascosti (.htaccess e quelli di git) nella stessa cartella degli altri.

A questo punto bisogna modificare il file di configurazione di Apache in modo da permettere la lettura del file .htaccess. Si apra quindi il file /etc/apache2/apache2.conf con un editor, ad esempio nano, e cercare le righe seguenti dove, come indicato, nelle directory / e /var/www va settato **AllowOverride All** al posto di **AllowOverride None**

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

Poi bisogna riavviare Apache in questo modo

```bash
sudo service apache2 restart
```

A questo punto se si usa un browser dal proprio sistema operativo host e in VirtualBox si è settato il NAT per la macchina virtuale appena creata, ad esempio esponendo la porta 80 della macchina virtuale sulla 8080 della "rete" della macchina fisica, inserendo l'indirizzo **http://localhost:8080/v1/public/categories** si dovrebbe vedere nel browser questa scritta

> Could not connect to database (c9).

Non è ancora a posto ma si è sulla buona strada. Il problema è che bisogna cambiare i parametri del database e anche crearlo.
Come prima cosa conviene creare un utente non root per l'accesso al database, e lo si può prima entrando nel client a linea di comando (ovviamente bisogna sapere la password di root creata al momento dell'installazione)

```bash
mysql -u root -p
```

e poi nella shell di MySQL eseguire i seguenti comandi

```sql
mysql> CREATE DATABASE correttore;
mysql> GRANT ALL ON correttore.* to correttore@locahost IDENTIFIED BY 'correttore';
```
dove la prima riga crea il database e la seconda l'utente *correttore* con password *correttore* che avrà i permessi per accedere al database (inutile dire che questo è solo un esempio e la password deve essere sicura).

Bisogna adesso modificare i file di configurazione per inserire questi nuovi dati: il primo che va modificato è il file che si trova nella cartella *init* e si chiama **conf.php**. Come al solito si apre nano

```bash
nano init/conf.php
```
 e si cambia la riga dei parametri di configurazione nel seguente modo

```php
R::setup( 'mysql:host=127.0.0.1;dbname=correttore',
        'correttore', 'correttore' );
```

Per verificare che tutto funzioni si può ora creare il database con le tabelle qualche dato eseguendo lo script **init.sh** che crea le tabelle e un po' di dati di prova.

```bash
sudo ./init.sh
```

Se a questo punto si vuole controllare nel db si dovrebbero trovare le cartelle create con dei dati di prova.

Come ultimo passaggio per il backend bisogna cambiare un altro file di configurazione con gli stessi parametri: si tratta del file conf.php che si trova nella cartella *correttoreAPI*. Sempre con nano si modificano le seguenti righe

```php
//Database configuration for RedBean
$app['redbean.database'] = 'mysql:host=127.0.0.1;dbname=correttore';
$app['redbean.username'] = 'correttore';
$app['redbean.password'] = 'correttore';
```

Fatto questo si può tornare al browser e inserendo l'indirizzo *http://localhost:8080/v1/public/categories* dovrebbe apparire una scritta come questa

> [{"id":"1","description":"Sequenza","type":"Programmazione"},{"id":"2","description":"Selezione","type":"Programmazione"},{"id":"3","description":"Input\/output","type":"Programmazione"}]   

e con il backend siamo a posto

### Installazione del frontend
Per il frontend la configurazione è molto più semplice. Innanzitutto, sempre rimanendo nella cartella /var/www/html, si clona con ```git``` il seguente repository

```bash
sudo git clone https://alessandro_bugatti@bitbucket.org/correttore2/correttore2.bitbucket.org.git
```

A questo punto conviene rinominare la cartella *correttore2.bitbucket.org* in *c2* per comodità

```bash
sudo mv correttore2.bitbucket.org/ c2
```

Come ultimo passaggio



### Contribution guidelines ###

* Writing tests
* Code review
* Other guidelines

### Who do I talk to? ###

* Repo owner or admin
* Other community or team contact