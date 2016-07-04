# Correttore 2 API
In questo documento sono documentate tutte le API finora realizzate
Tutte le API sono prefissate nella route della versione sotto forma di
> /vn/

con n numero delle versione

## Auth
API per l'autenticazione e l'autorizzazione

### POST /public/login
#### Descrizione
> Ritorna i dati di un utente se username e password sono corrette
#### Input JSON
> In input vengono forniti username e password
##### Esempio
    {
        "username":"alex",
        "password":"pippo"
    }
#### Output JSON
> Ritorna i dati dell'utente se esiste, compreso il token per identificare l'utente durante una sessione
##### Esempio
    {
        "token":"112e8d4797a6659d62a23566a380612f4690982fcfbcada9e8d725d04c77097b",
        "username":"alex",
        "role":"teacher"
    }
#### HTTP code
> **200** se la richiesta può essere soddisfatta

> **403** se l'utente non esiste


### GET /public/info
#### Descrizione
> Ritorna i dati di un utente se l'utente è già autenticato. L'autenticazione avviene passando in ogni richiesta
il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Output JSON
> Ritorna i dati dell'utente se esiste, compreso il token per identificare l'utente durante una sessione
##### Esempio
    {
        "token":"112e8d4797a6659d62a23566a380612f4690982fcfbcada9e8d725d04c77097b",
        "username":"alex",
        "role":"teacher"
    }
    
#### HTTP code
> **200** se la richiesta può essere soddisfatta

> **403** se l'utente non esiste o comunque per un qualsiasi tipo di errore


### GET /public/logout
#### Descrizione
> Distrugge la sessione di un utente autenticato. L'autenticazione avviene passando in ogni richiesta
il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### HTTP code
> **200** se la richiesta può essere soddisfatta

> **403** se l'utente non esiste o comunque per un qualsiasi tipo di errore

## Teachers
API per la gestione dei docenti

### GET /teachers
#### Descrizione
> Recupera l'elenco di tutti i docenti
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Output JSON
> Ritorna i dati di tutti i docenti, come array
##### Esempio
    [
        {"id":"1","name":"Alessandro","surname":"Bugatti","username":"alex"},
        {"id":"11","name":"Cristina","surname":"Trevisani","username":"cristella"},
        {"id":"12","name":"Cristina","surname":"Trevisani","username":"cristellina"}
    ]
#### HTTP code
> **200** se viene recuperato l'elenco

> **401** se l'utente non è autorizzato

> **403** altri errori

### GET /teachers/{id}
#### Descrizione
> Recupera il docente con l'id passato
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Output JSON
> Ritorna i dati del docente
##### Esempio
    {
        "id":"1",
        "name":"Alessandro",
        "surname":"Bugatti",
        "username":"alex",
    }
#### HTTP code
> **200** se viene recuperato l'elenco

> **401** se l'utente non è autorizzato

> **404** altri errori


### POST /teachers
#### Descrizione
> Crea un nuovo utente
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Input JSON
> In input vengono forniti tutti i dati dell'utente
##### Esempio
    {
    "username":"cristina",
    "name":"Cristina",
    "surname":"Trevisani",
    "password":"pippo",
    "role":"teacher"
    }
#### Output JSON
> Ritorna i dati dell'utente? (Da discutere)
##### Esempio
    {
        "name":"Cristina",
        "surname":"Trevisani",
        "username":"cristina"
    }
#### HTTP code
> **201** se l'utente viene creato

> **409** se l'utente non può essere creato


### PUT /teachers/{id}
#### Descrizione
> Modifica l'utente con l'id passato
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Input JSON
> In input vengono forniti tutti i dati dell'utente
##### Esempio
    {
    "username":"cristina",
    "name":"Cristina",
    "surname":"Trevisani",
    "password":"pippo",
    "role":"teacher"
    }
#### Output JSON
> Ritorna i dati dell'utente? (Da discutere)
##### Esempio
    {
        "name":"Cristina",
        "surname":"Trevisani",
        "username":"cristina"
    }
#### HTTP code
> **200** se l'utente viene modificato con successo

> **403** se l'utente che si vuole modificare non è un insegnante

> **409** se l'utente non può essere modificato

### DELETE /teachers/{id}
#### Descrizione
> Cancella l'utente con l'id passato
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
##### Esempio
    {
        "name":"Cristina",
        "surname":"Trevisani",
        "username":"cristina"
    }
#### HTTP code
> **204** se l'utente viene cancellato

> **404** in caso di errore

