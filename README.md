# Correttore 
Questo correttore ha lo scopo di creare un ambiente web dove permettere ai propri studenti di affrontare dei problemi tipicamente algoritmici e sottoporre le proprie soluzioni, nella forma di codice C/C++, ricevendo una valutazione su quanti casi di test vengono risolti correttamente, sullo stile delle Olimpiadi di Informatica o di altre gare online (Google Code Jam, Codeforces, ecc.).

La piattaforma è stata sviluppata nel 2016, separando il backend, scritto da me in PHP utilizzando le librerie [Silex](https://silex.symfony.com/) e [Redbean](https://redbeanphp.com/index.php), dal frontend, scritto da [Alekos Filini](https://bg.linkedin.com/in/alekos-filini-7237ab11b) utilizzando Angular. Da allora è stata utilizzata per le mie classi funzionando ragionevolmente bene per gli scopi per cui era stata pensata.
Ci sarebbero ancora delle funzionalità da implementare per renderla di più facile utilizzo, ma allo stato attuale la cosa procede in maniera sporadica, solo in risposta a delle mie esigenze per la parte del backend e senza mai aggiornare il frontend, poichè Alekos nel frattempo si è diplomato e io non ho conoscenze sufficienti per mettere mano al suo codice.

Questo repository contiene il backend, mentre il frontend (ma la versione già compilata, quindi non pensata per essere modificata) si trova nel repository *correttore_frontend*, sempre sul mio account GitHub.

Esistono due documenti che spiegano come installare il correttore [su una macchina virtuale VirtualBox](doc/configurazione_virtual_box.md) oppure su [un server virtuale del Cloud Aruba VPS](doc/configurazione_correttore_aruba.md), non è garantito che siano corretti, perchè nessuno li ha mai testati a parte me che li ho scritti, quindi se qualcuno volesse provare e poi farmi sapere mi farebbe un favore.

Per provare la macchina senza installare nulla si può andare al mio sito [imparando.net](https://www.imparando.net) dove c'è un link a un sito in cui è già installato il correttore, per avere degli account basta spedirmi una mail all'indirizzo alessandro.bugatti@gmail.com.
Chiunque volesse collaborare al progetto mi faccia sapere, ben lieto di poter avere del supporto.

## Come usare il correttore
Non essendo del tutto completo alcune delle funzionalità presenti nei menù del frontend non possono essere utilizzate, quindi questa breve guida indica cosa può essere fatto e cosa no.

### Login

La pagina iniziale del correttore presenta una schermata di login, che permette di entrare con tre diversi tipi di profilo:

- **Ospite**: non c'è bisogno di inserire nessuna password, si possono svolgere solo gli esercizi pubblici, nulla viene memorizzato
- **Studente**: con un account di tipo studente viene mostrata un'interfaccia che permette ancora di vedere e svolgere gli esercizi pubblici e in più anche le verifiche, che sono insiemi di esercizi, che vengono valutate e i cui risultati sono collegati allo studente che li ha svolti
- **Docente**: con un account di tipo docente è possibile creare nuovi esercizi, nuove verifiche e creare nuovi studenti di tipo studente.

### Profilo ospite
Sono presenti 4 voci di menù:

- **Home**: non fa nulla, porta di nuovo alla pagina di login
- **Esercizi**: presenta la lista degli esercizi pubblici, scegliendone uno si viene portati alla pagina con il testo del problema e in fianco è presente un *tab* per la sottoposizione del codice. Sottoponendo il codice sorgente viene inviato al server, che lo compila e mostra il risultato sui vari casi di test, fornendo il numero di casi risolti rispetti al numero di casi totali. Il risultato non viene memorizzato.
- **Disconnetti**: riporta alla pagina di login
- **Informazioni**: informazioni di vario tipo, attualmente scherzose

### Profilo studente
Rispetto al menù precedente è presente in più la voce **Verifiche**, tutte le altre si comportano come in precedenza.

La **verifica** altro non è che un insieme di esercizi, eventualmente anche uno solo, che però memorizza il risultato ottenuto. Ogni singolo problema ha un peso che può essere impostato dall'insegnante, di default ogni esercizio vale 1/N del valore totale della verifica, che è sempre 100. Quindi se sono presenti due esercizi, ognuno varrà al massimo 50 punti. Ogni volta che si sottopone un esercizio il punteggio viene memorizzato soltanto se è migliore di quanto ottenuto in precedenza, ogni soluzione può essere sottoposta quante volte si desidera.

### Profilo docente
Le voci di menù presenti sono:

- **Home**: come in precedenza
- **Studenti**: mostra l'elenco degli studenti, permette di aggiungerne di nuovi indicando il nome, il cognome, il nome utente e la password, e di cancellare quelli esistenti. **Attenzione**: attualmente l'elenco degli studenti è condiviso tra tutti i docenti.
- **Gruppi**: non ancora implementato, permette solo di creare i gruppi, ma non di aggiungere gli studenti
- **Verifiche**: permette di creare una nuova verifica, indicandone il nome e l'elenco degli esercizi che comprende. Ogni verifica può essere attiva o no, se non è attiva non può essere vista dagli studenti. Per ogni verifica rimane presente l'elenco dei punteggi presi da ogni studente, che può essere monitorato durante la verifica oppure scaricato in seguito come file CSV.
- **Problemi**: permette di creare nuovi esercizi, inserendo il titolo, il sottotitolo, il livello, la categoria e il numero di test case. Bisogna inoltre inviare tre file, mediante i tre appositi riquadri: il file PDF con il testo del problema, il file contenente i casi di test con le rispettive soluzioni, sotto forma di file ZIP, e un file dei materiali, che non è altro che un file ZIP contenente tutto quello che si ritiene utile per poter in seguito modificare il problema o comunque avere accesso a tutti i materiali (testo originale, sorgenti della soluzione e della generazione dei casi di test, immagini...). Oltre che creare è ovviamente possibile in seguito modificare un problema, ad esempio mandando un PDF diverso se si è accorti di un errore, oppure eliminare un problema.
- **Esercizi**, - **Disconnetti**, **Informazioni**: come in precedenza

