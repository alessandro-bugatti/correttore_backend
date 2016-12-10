## Problem

### API per la gestione dei problemi.
> I problemi sono sostanzialmente i task visti dalla parte di chi deve risolverli: dove un task può essere creato, modificato e rimosso da un insegnante, un problema viene visto solo "in lettura" e serve come interfaccia per poter somministrare i problemi agli studenti. Anche i dati ritornati spesso sono solo un sottoinsieme di tutti i dati di un task.

### GET /public/problems
#### Descrizione
> Recupera id e titolo di tutti i task pubblici
#### Vincoli
> Può essere chiamata da qualsiasi utente, anche anonimo
#### Output JSON
> Ritorna id e titolo dei task pubblici
##### Esempio

```json
    [
        {   
            "id":"1",
            "title":"Somma due interi"
            
        },
        {
            "id":"2",
            "title":"Stampa due interi"
        }
    ]
```

#### HTTP code
> **200** se vengono recuperati i problemi


### GET /problems/{id}.pdf
#### Descrizione
> Recupera il file pdf di un problema privato identificato da id.
#### Vincoli
> Può essere chiamata dagli studenti
#### Output
> Ritorna il pdf se esiste e se appartiene a un test attivo
#### HTTP code
> **200** se viene recuperato il file pdf

> **401** se l'utente non è autorizzato

> **404** se il file non viene trovato o se è inserito in un test che non è attivo