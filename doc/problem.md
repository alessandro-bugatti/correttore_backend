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
