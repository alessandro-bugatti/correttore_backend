## Submission

### API per la sottoposizioni

### POST /public/submissions/{id}
#### Descrizione
> Invia il file relativo al task individuato da id che verrà valutato per l'assegnazione del punteggio. Se l'utente che invia il file è uno studente registrato, viene memorizzata la sottoposizione nel db e il file viene salvato nella propria cartella personale. Nel caso di più sottoposizioni viene memorizzata l'ultima ricevuta con un punteggio maggiore o uguale alla migliore delle proprie sottoposizioni (al momento quindi non conserva traccia delle sottoposizioni precedenti). 
#### Vincoli
> Nessuno
#### Input
> L'id del task passato come parametro GET  e il file passato come multipart/form-data
#### Output JSON
> Ritorna un vettore con le linee della correzione e il punteggio assegnato
##### Esempio

```json
    {
        "lines":
            [
                "Executing on file n. 0\t[user time:  0.000s] Output file is not correct",
                "Executing on file n. 1\t[user time:  0.000s] Output file is not correct",
                "Executing on file n. 2\t[user time:  0.000s] Output file is not correct",
            ],
        "score":"0.000000"
    }
```

#### HTTP code
> **200** se la correzione va a buon fine

> **400** Manca il file della sottomissione, errore 'There is not the submitted file'

> **403** Il task non è pubblico, errore 'Task is not public'

> **404** Il task non esiste, errore 'Task does not exist'

> **500** se qualcosa va storto, in genere per problemi di permessi sul file system, con un messaggio d'errore
