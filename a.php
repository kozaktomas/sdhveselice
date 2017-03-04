<?php


try {
    $dbh = new PDO('mysql:host=localhost;dbname=sdhveselice', 'root', 'dekl');
    $dbh->query("SET NAMES 'UTF8'");
    foreach ($dbh->query('SELECT * from articles') as $row) {
        $url = $row['url'];
        $title = $row['title'];
        $content = $row['text'];
        $preheader = "<p>" . mb_substr(strip_tags($content), 0, 250) . "</p>";
        $created = new DateTime($row['created']);

        $file =
            '{block title}' . "\n"
            . $title . "\n"
            . '{/block}' . "\n\n"

            . '{block image}{/block}'. "\n\n"

            . '{block preheader}' . "\n"
            . $preheader . "\n"
            . '{/block}'. "\n\n"

            . '{block content}' . "\n"
            . $content . "\n"
            . '{/block}';

        $filename = $created->format('Y-m-d') . "-" . $url . ".latte";
        echo $filename;

        file_put_contents(__DIR__ . "/app/articles/" . $filename, $file);
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}