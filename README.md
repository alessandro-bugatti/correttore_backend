# Correttore 
Questo correttore ha lo scopo di creare un ambiente web dove permettere ai propri studenti di affrontare dei problemi tipicamente algoritmici e sottoporre le proprie soluzioni, nella forma di codice C/C++, ricevendo una valutazione su quanti casi di test vengono risolti correttamente, sullo stile delle Olimpiadi di Informatica o di altre gare online (Google Code Jam, Codeforces, ecc.).

La piattaforma è stata sviluppata nel 2016, separando il backend, scritto da me in PHP utilizzando le librerie [Silex](https://silex.symfony.com/) e [Redbean](https://redbeanphp.com/index.php), dal frontend, scritto da [Alekos Filini](https://bg.linkedin.com/in/alekos-filini-7237ab11b) utilizzando Angular. Da allora è stata utilizzata per le mie classi funzionando ragionevolmente bene per gli scopi per cui era stata pensata.
Ci sarebbero ancora delle funzionalità da implementare per renderla di più facile utilizzo, ma allo stato attuale la cosa procede in maniera sporadica, solo in risposta a delle mie esigenze per la parte del backend e senza mai aggiornare il frontend, poichè Alekos nel frattempo si è diplomato e io non ho conoscenze sufficienti per mettere mano al suo codice.

Questo repository contiene il backend, mentre il frontend (ma la versione già compilata, quindi non pensata per essere modificata) si trova nel repository *correttore_frontend*, sempre sul mio account GitHub.

Esistono due documenti che spiegano come installare il correttore [su una macchina virtuale VirtualBox](doc/configurazione_virtual_box.md) oppure su [un server virtuale del Cloud Aruba VPS](doc/configurazione_correttore_aruba.md), non è garantito che siano corretti, perchè nessuno li ha mai testati a parte me che li ho scritti, quindi se qualcuno volesse provare e poi farmi sapere mi farebbe un favore.

Per provare la macchina senza installare nulla si può andare al mio sito [imparando.net](https://www.imparando.net) dove c'è un link a un sito in cui è già installato il correttore, per avere degli account basta spedirmi una mail all'indirizzo alessandro.bugatti@gmail.com.
Chiunque volesse collaborare al progetto mi faccia sapere, ben lieto di poter avere del supporto.
