## Groups
API per la gestione dei gruppi di studenti

### GET /groups
#### Descrizione
> Recupera l'elenco di tutti i gruppi nel caso la chiamata sia fatta da un amministratore, dei gruppi di un docente nel caso la chiamata sia fatta da un docente
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin o docente
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Output JSON
> Ritorna i dati dei gruppi, come array
##### Esempio
    [
        {"id":"1","description":"classe 3AI 2015-2016","user_id":"1"},
        {"id":"2","description":"classe 4AI 2015-2016","user_id":"1"}
    ]
#### HTTP code
> **200** se viene recuperato l'elenco

> **401** se l'utente non è autorizzato

> **403** altri errori

### POST /groups
#### Descrizione
> Crea un nuovo gruppo appartenente al docente che ha fatto la chiamata
#### Vincoli
> Può essere chiamata solo da un utente di tipo docente
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Input JSON
> In input viene fornita la descrizione del gruppo
##### Esempio
    {
        "description":"Classe 5AI 2016",
    }
#### Output JSON
> Ritorna i dati del gruppo
##### Esempio
    {
        "id":"4",
        "description":"Classe 5AI 2016",
        "user_id":"1"
    }
#### HTTP code
> **201** se il gruppo viene creato

> **409** se il gruppo non può essere creato perchè esiste già

> **401** Utente non autorizzato