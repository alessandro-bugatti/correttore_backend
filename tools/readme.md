# Strumenti di amministrazione sotto forma di script PHP

Poichè allo stato attuale l'interfaccia web non permette alcune funzionalità che nella pratica sono importanti,
sono stati sviluppati alcuni script PHP che vanno a risolvere le esigenze più comuni. Ovviamente per poter essere 
utilizzati questi script necessitano di avere un accesso sul server, tipicamente tramite SSH, e di essere chiamati 
attraverso l'interprete PHP.

# Elenco degli script
1. [Creazione di utenti batch con ruolo e gruppo deciso alla creazione](#create_users_batch_role)
2. [Creazione di utenti a partire da un file di nomi](#create_users_batch_with_names)
3. [Creazione di utenti a partire da un file di nomi che vengono aggiunti a un gruppo esistente](#create_users_with_names_in_group)
4. [Cambiare la password di amministratore](#change_admin_password)
5. [Crea e aggiunge uno studente a un gruppo già esistente](#create_and_add_student_to_group)

## create_users_batch_role.php <a name="create_users_batch_role"></a>
Scopo di questo script è quello di creare una serie di utenti, appartenenti a un certo gruppo, che possono poi essere
distribuiti a un insieme di utenti di cui non si hanno e/o non interessano i nomi reali (ad esempio per fare delle prove o
per gruppi temporanei). Inoltre viene richiesto il loro ruolo,
che può essere uno tra *admin*, *teacher*, *student*.
Questo script unifica i due script scritti in precedenza, cioè ```create_user_batch.php``` e ```create_teacher_batch.php```, che 
quindi sono stati rimossi.
Lo script deve essere chiamato nel seguente modo (l'uso di *sudo* dipende dai permessi delle cartelle dell'installazione,
usandolo non si sbaglia)

```
sudo php create_users_batch_role.php
```
A questo punto verranno poste queste tre domande

> Inserisci il nome del gruppo:

> Quanti utenti vuoi creare?:

> Che ruolo vuoi che abbiano? (admin, teacher, student):

La risposta alla prima domanda determinerà il nome del gruppo e di conseguenza il nome degli utenti secondo questa
struttura:

> *nome_gruppo*_user*n* con *n* che va da 1 al numero di utenti scelti nella seconda domanda

L'esecuzione di questo script creerà due file, ***nome_gruppo*_creazione.php** e ***nome_gruppo*_password.txt**.

Il primo file deve essere eseguito per generare gli utenti nel sistema, il secondo contiene le password in chiaro.
Per eseguire il primo file basta digitare il comando:

```
php nome_gruppo_creazione.php
```

## create_users_batch_with_names.php<a name="create_users_batch_with_names"></a>
Permette di generare una serie di *studenti* a partire da un file di testo che ne contenga i nomi e può venire comodo 
all'inizio dell'anno per generare tutti gli studenti di una classe senza inserirli uno per uno dall'interfaccia
web (cosa che comunque può essere sempre fatta). Il file deve avere un nome uguale a quello del gruppo che si 
vuole creare: se ad esempio si vuole creare il gruppo *3AI_2019* allora il file si dovrà chiamare *3AI_2019.txt* e
per ogni riga dovrà contenere il cognome e il nome di uno studente separati da un punto, come nel seguente esempio:

> fertullo.antonio
>
> grafenio.anna
>
> curmani.federico

Anche in questo caso verranno generati due file, il file dello script per la creazione effettiva degli studenti e il 
file delle password in chiaro, che dovranno essere distribuite agli studenti.

## create_users_with_names_in_group.php<a name="create_users_with_names_in_group"></a>
Esattamente uguale al precedente, l'unica differenza è che in questo caso il gruppo deve esistere già e gli utenti vengono aggiunti a quel gruppo

## change_admin_password.php <a name = "change_admin_password"></a>
Questo file ha il solo scopo di permettere di cambiare la password dell'amministratore del sistema di correzione, che su un 
sistema appena installato e inizializzato ha come username *admin* e password *pippo*. Siccome non esiste
una parte dell'interfaccia web che permetta di farlo, questo è l'unico modo per cambiarla, cosa necessaria per evitare
che un qualsiasi utente possa loggarsi come amministratore e fare pasticci.

Lo script genera due file come nei casi precedenti, uno per il cambio effettivo della password (*new_admin_password.php*) 
e l'altro con la password in chiaro (nuova_password_admin.txt).

Per utilizzarlo si chiama lo script come in precedenza

```
sudo php change_admin_password.php
```
A questo punto verranno poste queste due domande

> Inserisci la nuova password:

> Reinserisci la password?

Rispondendo con la nuova password, quella vecchia verrà successivamente sovrascritta (per il contesto nel quale è pensato
non c'è necessità che venga chiesta la vecchia password, perchè se un utente riesce a eseguire questo
script è già parecchio dentro nel sistema e può fare un può quello che vuole).
Con i due file successivamente generati si potrà quindi modificare la password di admin e tenerne traccia. Una
volta avuto l'accesso di amministratore da quello si possono creare nuovi *docenti* e quelli potranno generare 
nuovi *studenti*.

## create_and_add_student_to_group.php <a name = "create_and_add_student_to_group"></a>
Scopo di questo script è quello di aggiungere uno studente a un gruppo (una classe), dopo che il gruppo è già stato creato con uno degli script 
precedenti. Può essere utile quando uno studente si aggiunge nel corso dell'anno.
Va usato un questo modo

```
php create_and_add_student_to_group.php
```
A questo punto verranno poste queste due domande

> Inserisci il nome dell'utente:

> Inserisci il cognome dell'utente:

> Inserisci il nome del gruppo (deve essere un gruppo esistente): "

Dopo aver inserito questi tre dati verrà creato il nuovo utente
appartenente al gruppo indicato e verrà anche la password casuale generata
dallo script.

