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
Inoltre è necessario installare i programmi ```zip``` e ```unzip``` che di default non sono installati e che sono necessari per il corretto funzionamento di composer:

```bash
sudo apt-get install zip unzip
```

Ci si può ora spostare nella cartella *correttoreapi* e scaricare le dipendenze:

```bash
cd correttoreapi
sudo composer update
```

   



### Contribution guidelines ###

* Writing tests
* Code review
* Other guidelines

### Who do I talk to? ###

* Repo owner or admin
* Other community or team contact