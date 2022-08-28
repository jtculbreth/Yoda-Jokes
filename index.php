<!DOCTYPE html>

<html>

<head>
    <style>
        .container{
            display:flex;
            width:100%;
        }    
        .joke-wrapper{
            margin:auto;
            padding-top:15%;
        }
    </style>

</head>

<body>
</body>
<div class="container">
    <div class="joke-wrapper">
    <?php
        //get joke
        $joke_url = 'https://v2.jokeapi.dev/joke/Any?blacklistFlags=nsfw,religious,political,racist,sexist,explicit';
        $jokeRequest = curl_init($joke_url);
        curl_setopt($jokeRequest, CURLOPT_RETURNTRANSFER, true);
        $jokeResponse = curl_exec($jokeRequest);
        curl_close($jokeRequest);

        $jokeData = json_decode($jokeResponse, true);
        $joke = "";
        $setup = "";
        $delivery = "";

        if($jokeData["type"] == "twopart"){
            $setup = $jokeData["setup"];
            $delivery = $jokeData["delivery"];
        }
        elseif($jokeData["type"] == "single"){
            $joke = $jokeData["joke"];
        }

        $to_be_translated = $joke.$setup." ".$delivery;

        //translate to yoda
       
        $yoda_url = "https://api.funtranslations.com/translate/yoda.json?text=".urlencode($to_be_translated);
        $yodaRequest = curl_init($yoda_url);
        curl_setopt($yodaRequest, CURLOPT_RETURNTRANSFER, true);
        $yodaResponse = curl_exec($yodaRequest);
        curl_close($yodaRequest);

        $yodaData = json_decode($yodaResponse, true);
        
        $yoda_contents = $yodaData["contents"];
        $yoda_joke = $yoda_contents["translated"];

        //add spaces after punctuation marks

        //convert string to array
        $yoda_joke_string = str_split($yoda_joke);
        
        //add a space to each string element that is a punctuation mark
        foreach($yoda_joke_string as &$char){
            if($char == "." || $char == "," || $char == "!" || $char == "?"){
                $char = $char." ";
            }
        }
        //convert back to a string
        $yoda_joke = implode("", $yoda_joke_string);

        echo "<b>Original joke:</b> ".$to_be_translated."<br/>";
        echo "<b>Yoda's version:</b> ".$yoda_joke;
        ?>
    </div>
</div>
</html>