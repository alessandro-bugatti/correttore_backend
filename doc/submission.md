## Submission

### API per la sottoposizioni

### POST /public/submissions/{id}
#### Descrizione
> Invia il file relativo al task individuato da id che verrà valutato per l'assegnazione del punteggio. Se l'utente che invia il file è uno studente registrato, viene memorizzata la sottoposizione nel db e il file viene salvato nella propria cartella personale. Nel caso di più sottoposizioni viene memorizzata l'ultima ricevuta con un punteggio maggiore o uguale alla migliore delle proprie sottoposizioni (al momento quindi non conserva traccia delle sottoposizioni precedenti). 
#### Vincoli
> Nessuno
#### Input
> L'id del task passato nell'URL  e il file passato come multipart/form-data
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

### POST /submissions/tests/{test_id}/tasks/{task_id}
#### Descrizione
> Invia il file relativo al task individuato da task_id, contenuto all'interno del test individuato da test_id, che verrà valutato per l'assegnazione del punteggio. Viene memorizzata la sottoposizione nel db e il file viene salvato nella propria cartella personale. Nel caso di più sottoposizioni viene memorizzata l'ultima ricevuta con un punteggio maggiore o uguale alla migliore delle proprie sottoposizioni (al momento quindi non conserva traccia delle sottoposizioni precedenti). Lo stesso task, se compare in test diversi, viene trattato come un task diverso, la ragione è che permette di fare classifiche basate sui test, senza che appaiano studenti che hanno risolto lo stesso task ma in un test diverso.
#### Vincoli
> Solo gli studenti possono sottoporre. 
#### Input
> Gli id del test e del task passati come parametri nell'URL e il file passato come multipart/form-data
#### Output JSON
> Ritorna un vettore con le linee della correzione e il punteggio assegnato
##### Esempio

```json
    {
        "lines":
            [
                "Executing on file n. 0\t[user time:  0.000s] Success! (1.0000)",
                "Executing on file n. 1\t[user time:  0.000s] Output file is not correct",
                "Executing on file n. 2\t[user time:  0.000s] Output file is not correct",
            ],
        "score":"1.000000"
    }
```

#### HTTP code
> **200** se la correzione va a buon fine

> **400** Manca il file della sottomissione, errore 'There is not the submitted file'

> **401** L'utente non è uno studente, errore 'Only students can submit solutions'

> **403** Il task non è pubblico, errore 'Task is not public'

> **404** Il task non esiste, errore 'Task does not exist'

> **404** Il task non fa parte del test, errore 'This task is not in the current test'

> **500** se qualcosa va storto, in genere per problemi di permessi sul file system, con un messaggio d'errore

