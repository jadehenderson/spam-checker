<?php

function main() {

function make_prediction($haslinks, $hasspam, $length) {
    // decision tree 
    if ($hasspam == "true" && $length > 100) {
        if ($length > 177) {
            return "ham";
        } else if ($length <= 177) {
            return "spam";
        }
    } else if ($hasspam == "true" && $length <= 100) {
        if ($haslinks == "true") {
            return "spam";
        } else {
            return "ham";
        }
    } else if ($hasspam == "false") {
        if ($haslinks == "true") {
            return "spam";
        } else {
            return "ham";
        }
    } 
}

// copy paste from compute features php
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

// get the email
$text = $_POST['text_message'];

// convert into features 
$links = doesHaveLinks($text);
$words = doesHaveSpammyWords($text);
$textlength = lengthOfText($text);

// make final prediction
echo make_prediction($links, $words, $textlength);

}

main();

?>