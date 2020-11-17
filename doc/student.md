## Students
API per la gestione degli studenti

### GET /students
#### Descrizione
##### Nota
16/11/2019: per permettere di gestire sullo stesso server più
docenti è stato modificato l'elenco degli studenti ritornati
da questa chiamata, in quanto adesso vengono ritornati solo gli studenti
che appartengono ai gruppi creati dal docente, mentre prima 
venivano ritornati tutti. Sebbene questo funzioni, siccome
l'interfaccia Angular non ha ancora una gestione dei gruppi,
l'associazione docente-gruppo e gruppo - studente deve essere 
fatta a mano sul database oppure attraverso gli script della
cartella tools.
> Recupera l'elenco di tutti gli studenti che appartengono
> ai gruppi del docente che ha fatto la chiamata
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin o di tipo docente
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Output JSON
> Ritorna i dati di tutti gli studenti, come array
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

### GET /students/{id}
#### Descrizione
> Recupera lo studente con l'id passato
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin o di tipo docente
#### Input  
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Output JSON
> Ritorna i dati dello studente
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


### POST /students
#### Descrizione
> Crea un nuovo studente
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin o di tipo docente
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
    "role":"student"
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


### PUT /students/{id}
#### Descrizione
> Modifica lo studente con l'id passato
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin o di tipo docente
> ***Attenzione***: Al momento non si controlla se lo studente è del docente che sta cercando di modificarlo, quindi qualsiasi docente può modificare qualsiasi studente.
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### Input JSON
> In input vengono forniti tutti i dati dello studente
##### Esempio
    {
    "username":"cristina",
    "name":"Cristina",
    "surname":"Trevisani",
    "password":"pippo",
    "role":"teacher"
    }
> ***Attenzione***: al momento compare il campo username, ma qualunque sia il valore non viene utilizzato perchè si considera lo username non modificabile. Il campo password può non esserci o essere vuoto, nel qual caso la password rimane invariata, se invece contiene un valore viene cambiata la password 
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

> **403** se l'utente che si vuole modificare non è uno studente

> **409** se l'utente non può essere modificato

### DELETE /students/{id}
#### Descrizione
> Cancella l'utente con l'id passato
#### Vincoli
> Può essere chiamata solo da un utente di tipo admin o di tipo docente
> ***Attenzione***: Al momento non si controlla se lo studente è del docente che sta cercando di cancellarlo, quindi qualsiasi docente può cancellare qualsiasi studente.
#### Input 
> Il token ricevuto all'atto del login, che viene passato nell'header HTTP **x-authorization-token**
#### HTTP code
> **204** se l'utente viene cancellato

> **404** in caso di errore
