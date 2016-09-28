## Tasks

### API per la gestione delle categoria dei problemi

### GET /public/categories
#### Descrizione
> Recupera l'elenco delle categorie dei problemi
#### Vincoli
> La chiamata è pubblica
#### Output JSON
> Ritorna l'elenco delle categorie
##### Esempio

```json
    [
        {"id":"1","description":"Sequenza","type":"Programmazione"},
        {"id":"2","description":"Selezione","type":"Programmazione"},
        {"id":"3","description":"Input\/output","type":"Programmazione"}
    ]
```

#### HTTP code
> **200** se viene recuperata la lista


### GET /categories/{id}
#### Descrizione
> Recupera la categoria individuata dall'id
#### Vincoli
> La chiamata può essere fatta solo da un utente docente o un amministratore (da verificare)
#### Output JSON
> Ritorna la categoria richiesta
##### Esempio

```json
    {
        "id":"1",
        "description":"Sequenza",
        "type":"Programmazione"
    }
```

#### HTTP code
> **200** se viene recuperata la categoria

> **400** se la categoria non esiste


### POST /categories
#### Descrizione
> Crea una nuova categoria
#### Vincoli
> Può essere chiamata solo da un utente di tipo docente
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Input application/json
> In input viene fornita la descrizione del task

##### Esempio cURL

```bash
    curl -v -X POST -H "X-Authorization-Token: 3427a80af08fdf717529d631339a090635acf72079712c12a8a0f2498c5f87da" \
    -H "Content-Type: application/json" -d '{"description":"test category"}' \
    https://auth-silex-test-alessandro-bugatti.c9users.io/v1/categories
```

#### Output JSON
> Ritorna i dati della categoria 
##### Esempio
    
```json
    {
        "id":"4",
        "description":"test category"
    }
```

#### HTTP code
> **200** se la categoria viene creata

> **409** se la categoria esiste già

> **401** se l'utente non è un docente

