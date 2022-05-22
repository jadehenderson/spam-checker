<?php

function main() {

$username = 'root';
$password = 'root';
$dbname = 'predict_spam';
$servername = 'localhost';
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username,$password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . 
        $conn->connect_error); 
   }


function doesHaveLinks($email) {
 $lowercase = strtolower($email);
 $http = "http";
 $www = "www";
    if(str_contains($lowercase, $http) || str_contains($lowercase, $www)) {
        return "true";
    } else {
        return "false"; 
    }
}

function doesHaveSpammyWords($email) {

    $lowercase = strtolower($email);
    $spamwords = ['msg', 'opt', 'claim', 'win', 'free', 'reply', 'txt', 'cash', 'prize', 'subscribe']; 
    foreach ($spamwords as $spam) {
        if (str_contains($lowercase, $spam)) {
            return "true";
        }
    } 
    return "false";
}

function lengthOfText($email) {
    return strlen($email);
}


// creating csv file
$filename = 'features.csv';
$headers = ['doesHaveLinks', 'doesHaveSpammyWords', 'lengthOfText', 'classLabel'];
$f = fopen($filename, 'w');

// write the csv headers 
fputcsv($f, $headers);

if ($f === false) {
    die('Error opening the file ' . $filename);
}

// create array to store all data rows
$data = [];

// get all rows
$sql = "SELECT * FROM spam";
$result = $conn->query($sql);

// loop through each email to create features 
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // initialize blank array to push row values onto 
        $rowresult = [];
        $links = doesHaveLinks($row["v2"]);
        $spamwords = doesHaveSpammyWords($row["v2"]);
        $length = lengthOfText($row["v2"]);
        // get spam/ham label
        $label = $row["v1"];
        // push the feature values onto the single row array
        $new = array_push($rowresult, $links, $spamwords, $length, $label);
        // push row onto data array 
        $featureresults = array_push($data, $rowresult);
    }
}

// write each data row to csv file 
foreach ($data as $row) {
    fputcsv($f, $row);
}
fclose($f);
}

main();

?>