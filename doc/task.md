## Tasks

### API per la gestione dei task (problemi)

### GET /tasks/{id}
#### Descrizione
> Recupera il task con l'id passato
#### Vincoli
> Può essere chiamata solo **da decidere chi può recuperare**
#### Input 
> L'ID del task che si vuole recuperare
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Output JSON
> Ritorna i dati del task
##### Esempio

```json
    {
        "id":"1",
        "title":"Somma due interi",
        "short_title":"somma",
        "is_public":"1",
        "level":"1",
        "test_cases":"10",
        "category_id":"1"
    }
```

#### HTTP code
> **200** se viene recuperato il task

> **401** se l'utente non è autorizzato

> **404** altri errori


### GET /tasks
#### Descrizione
> Recupera l'elenco di tutti i task
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin o docente
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Output JSON
> Ritorna ID e titolo di tutti i task, come array
##### Esempio

```json
    [
        {"id":"1","title":"Somma due interi"},
        {"id":"2","title":"Stampa due interi"}
    ]
```

#### HTTP code
> **200** se viene recuperato l'elenco

> **401** se l'utente non è autorizzato

> **403** altri errori



### POST /tasks
#### Descrizione
> Crea un nuovo task
#### Vincoli
> Può essere chiamata solo da un utente di tipo docente
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Input multipart/form-data
> In input vengono forniti tutti i dati del task, più tre file: il testo pdf, un file zip contenente i materiali che sono stati usati per la creazione del task (sorgenti, esempi, immagini) e un file zip contenente le soluzioni. 
Al momento tutti i campi sono obbligatori, però l'unico controllo è fatto sulla presenza dei tre file.
Gli esempi sono fatti con cURL e con una form HTML
##### Esempio cURL

```bash
    curl -v -H "X-Authorization-Token: 3427a80af08fdf717529d631339a090635acf72079712c12a8a0f2498c5f87da" \
    -F title="Somma tre numeri" -F short_title="somma3" -F is_public="0" -F level="1" -F test_cases="10" \
    -F category_id="1" -F description=@prova.pdf -F solution=@prova.pdf -F material=@prova.pdf \
    https://auth-silex-test-alessandro-bugatti.c9users.io/v1/tasks
```

#### Esempio HTML

```html
    <form action="https://auth-silex-test-alessandro-bugatti.c9users.io/tasks" enctype="multipart/form-data" method="post" >
        <input type="text" name="title"><br>
        <input type="text" name="short_title"><br>
        <input type="text" name="is_public"><br>
        <input type="text" name="level"><br>
        <input type="text" name="test_cases"><br>
        <select name="category_id">
          <option value="id1">String1</option>
          <option value="id2">String2</option>
          <option value="id3">String3</option>
        </select><br>
        <input type="file" name="description"><br>
        <input type="file" name="solution"><br>
        <input type="file" name="material"><br>
        <input type="submit">
    </form>
```

#### Output JSON
> Ritorna i dati del task
##### Esempio
    {
        "id":"7",
        "title":"Somma tre numeri",
        "short_title":"somma3",
        "is_public":"0",
        "level":"1",
        "test_cases":"10",
        "category_id":"1",
        "user_id":"1"
    }
#### HTTP code
> **201** se il task viene creato

> **409** se il task non può essere creato

### DELETE /tasks/{id}
#### Descrizione
> Cancella il task con l'id passato
#### Vincoli
> Può essere chiamata solo da un utente di tipo docente e il task deve essere di sua proprietà
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### HTTP code
> **204** se il task viene cancellato

> **404** in caso di errore

<div class="alert alert-info">

#### ATTENZIONE
> L'operazione è distruttiva, oltre a rimuovere i dati dal database elimina anche i file dal filesystem.
Da discutere: i file sorgenti di soluzioni vengono eliminati in cascata anche loro?

</div>
