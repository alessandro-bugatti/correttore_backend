<?php

$test_id = readline("Inserisci l'id del test: ");

$html_header = <<<EOF
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/default.min.css">
    <script src="highlight.min.js"></script>
    <script>hljs.highlightAll();</script>
    <title>Compito in classe del %data%</title>
</head>
<body>
EOF;

include '../init/conf.php';

$sql =<<<EOF
SELECT test.creation_date
FROM test
WHERE test.id = :id_test
EOF;

$test = R::getAll($sql, ['id_test' => $test_id]);
$data_creazione = $test[0]['creation_date'];

$result_file = fopen('Compito_' . date( 'd_m_Y', strtotime($data_creazione)) . '.html','w');
$html_header = str_replace("%data%", date( 'd/m/Y', strtotime($data_creazione)), $html_header);
fwrite($result_file, $html_header);

$sql =<<<EOF
SELECT DISTINCT short_title, test.creation_date
FROM task, test, solution
WHERE task.id = solution.task_id
AND solution.test_id = test.id
AND test.id = :id_test
EOF;

$tasks = R::getAll($sql, ['id_test' => $test_id]);

$sql = "SELECT user.id AS ID, surname, name, username, \n"
    . "(SUM(score/task.test_cases*task_test.value)/\n"
    . "(SELECT SUM(value) FROM task_test WHERE test_id = :test_id))*100 AS result\n"
    . "FROM solution, user, task, task_test WHERE \n"
    . "solution.user_id = user.id AND\n"
    . "solution.task_id = task.id AND\n"
    . "task_test.task_id = task.id AND\n"
    . "task_test.test_id = :test_id AND\n"
    . "solution.test_id = :test_id\n"
    . "GROUP BY ID, name, surname, username\n"
    . "ORDER BY result DESC, AVG(submitted)";
$studenti = R::getAll( $sql, [':test_id' => $test_id]);

foreach ($studenti as $studente){
    fwrite($result_file, '<div style="page-break-after: always;">');
    fwrite($result_file, '<h2>Compito di ' . ucfirst($studente['surname']) . ' ' .
        ucfirst($studente['name']) . ' del ' .
        date( 'd/m/Y', strtotime($data_creazione)) .
        '</h2>' . "\n");
    fwrite($result_file, '<h3>Punteggio ' . number_format((float)$studente['result'], 2, '.', '')
        . '/100</h3>');
    foreach ($tasks as $task) {
        $sql =<<<EOF
select name, surname, score, test_cases 
from solution, task, user 
where solution.task_id = task.id 
and solution.user_id = user.id 
and short_title = :short_title 
and solution.user_id = :user_id;
EOF;

        $punteggio_task = R::getAll($sql, [
            'short_title' => $task['short_title'],
            'user_id' => $studente['ID']
        ]);
        fwrite($result_file, '<h3>Esercizio ' . $task['short_title'] . '</h3>');

        if (!empty($punteggio_task))
            fwrite($result_file, '<h4>Punteggio ' . $punteggio_task[0]['score']
                .'/' . $punteggio_task[0]['test_cases'] .'</h4>');
        $input_file = '../users/' .
            $studente['username'] . '/' . $task['short_title'] .
            '_test_' . $test_id . '.cpp';
        $rows = file($input_file);

        fwrite($result_file, '<pre><code class="language-cpp">');
        //Stampa il codice, se presente
        if ($rows) {
            foreach($rows as $row) {
                fwrite($result_file, htmlentities($row));
            }
        } else {
            fwrite($result_file, 'Soluzione non presente');
        }

        fwrite($result_file, '</code></pre>');

    }
    fwrite($result_file, '</div>');
}

fwrite($result_file, '</body></html>');
?>


