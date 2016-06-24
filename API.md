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
