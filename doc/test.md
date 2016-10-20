## Tests
API per la gestione dei test, dove un test è un insieme di task che può essere svolto solo da uno studente iscritto 

### GET /tests
#### Descrizione
> Recupera l'elenco di tutti i test: 
    * se l'utente è uno studente è l'elenco di tutti i task attivi 
    * se è un docente solo i propri task, attivi o no
#### Vincoli
> Può essere chiamata solo da un utente di tipo studente o docente
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Output JSON
> Ritorna i dati dei test, come array
##### Esempio studente
```json
    [
        {"id":"1","description":"test3","is_on":"1","creation_date":"2016-04-10","user_id":"1"}
    ]
```
##### Esempio docente
```json
    [
        {"id":"1","description":"Test di prova","is_on":"1","creation_date":"2016-04-10","user_id":"1"},
        {"id":"2","description":"test2 di prova","is_on":"0","creation_date":"2016-10-18","user_id":"1"}
    ]
```
#### HTTP code
> **200** se viene recuperato l'elenco

> **401** se l'utente non è autorizzato

> **403** altri errori

### GET /tests/{id}/tasks
#### Descrizione
> Recupera l'elenco dei task di cui è composto un test
#### Vincoli
> Può essere chiamata solo da un utente di tipo studente o docente
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Output JSON
> Ritorna i dati dei task, in particolare:
    * se l'utente è uno studente ritorna i dati del task solo se il test a cui appartengono è attivo
    * se è un docente ritorna i dati dei task solo se appartengono a un test creato dall'utente
##### Esempio
```json
    [
        {"id":"1","title":"Numeri di Fibonacci"},
        {"id":"2","title":"Mucche e vitelli"}
    ]
```
#### HTTP code
> **200** se viene recuperato l'elenco

> **401** se l'utente non è autorizzato

> **404** altri errori (test non trovato o che non può essere visualizzato dal particolare studente o docente)


### POST /tests
#### Descrizione
> Crea un nuovo test appartenente al docente che ha fatto la chiamata
#### Vincoli
> Può essere chiamata solo da un utente di tipo docente
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Input JSON
> In input vengono forniti i dati del test. Il valore della data di creazione viene preso dal tempo del server e il test alla creazione viene impostato come inattivo.
##### Esempio
```json
    {
        "description":"test di prova"
    }
```
#### Output JSON
> Ritorna i dati del gruppo
##### Esempio
```json
    {
        "id":"3",
        "description":"test di prova",
        "creation_date":"2016-10-19 13:04:47",
        "is_on":0,
        "user_id":"1"
    }
```
#### HTTP code
> **201** se il gruppo viene creato

> **409** se il gruppo non può essere creato perchè esiste già

> **401** Utente non autorizzato

### PUT /tests/{id}
#### Descrizione
> Modifica la descrizione o/e lo stato di un test (attivo/inattivo) appartenente al docente che ha fatto la chiamata
#### Vincoli
> Può essere chiamata solo da un utente di tipo docente e il test deve essere di sua proprietà
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Input JSON
> In input vengono forniti i dati del test 
##### Esempio
```json
    {   
        "description":"nuova descrizione", 
        "is_on":"1"
    }
```
#### Output JSON
> Ritorna i dati del test modificato
##### Esempio
```json
    {
        "id":"1",
        "description":"nuova descrizione",
        "is_on":"1",
        "creation_date":"2016-04-10",
        "user_id":"1"
    }
```
#### HTTP code
> **200** se il gruppo viene modificato

> **403** se la descrizione del test non può essere modificata perchè esiste già un altro test con la stessa descrizione o il test non appartiene al docente o il test non esiste. Ritorna una descrizione dell'errore
##### Esempio
    {
        "error":"test does not exist or description is duplicated"
    }

> **401** Utente non autorizzato, perchè non ha i permessi su questa azione o il test non gli appartiene

### DELETE /tests/{id}
#### Descrizione
> Cancella il test con l'id passato, se ci sono task nel test viene eliminata la loro associazione al test, ma non i task.
#### Vincoli
> Può essere chiamata solo da un utente di tipo docente e che ha creato il test
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### HTTP code

> **204** se il test viene cancellato

> **401** in caso di errore (test inesistente, non di proprietà dell'utente o utente non di tipo docente), con messaggio di errore
##### Esempio
```json
    {
        "error":"permission denied, user does not own this test or the test does not exist"
    }
```

### PUT /tests/{test_id}/task/{task_id}
#### Descrizione
> Inserisce un task all'interno di un test: il task viene identificato dal proprio task_id e il test dal proprio test_id
#### Vincoli
> Può essere chiamata solo da un utente di tipo docente e che possiede il test. La chiamata è idempotente, quindi se chiamata più volte non modifica il risultato.
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### HTTP code

> **204** se il task viene aggiunto al test

> **401** in caso di errore 
    * il test non appartiene al docente 
    * il test non esiste 
    * il task è pubblico e quindi non può fare parte di un test
    * il task non esiste
    * l'utente non è un docente
##### Esempio
```json
    {
        "error":"permission denied, user does not own this test or the test does not exist"
    }
```

### DELETE /tests/{test_id}/task/{task_id}
#### Descrizione
> Rimuove il task individuato da task_id dal test individuato da test_id
#### Vincoli
> Può essere chiamata solo da un utente di tipo docente e che possiede il test. La chiamata è idempotente, quindi se chiamata più volte non modifica il risultato.
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### HTTP code

> **204** se il task viene rimosso dal test

> **401** in caso di errore 
    * il test non appartiene al docente 
    * il test non esiste 
    * il task è pubblico e quindi non può fare parte di un test
    * il task non esiste
    * l'utente non è un docente
##### Esempio
```json
    {
        "error":"permission denied, user does not own this test or the test does not exist"
    }
```