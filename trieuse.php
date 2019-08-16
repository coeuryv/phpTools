<?php

/*
 * script made by Yves Coeurnelle
 * inspired by code design https://codedesign.fr/tutorial/creer-script-php-tri-photo/
 * original tutorial written by ARNAUD LEMERCIER twitter: @arnolem
 */


/*
 *  WARNING: use for learning purpose only
 *  this script should be secured because it doesn't handle errors , wrong path and so and.
 */


clear();        // lets clean the screen
echo 'Organisateur de photos/videos - version 1.1' . PHP_EOL;

// read command line arguments assuming the order 
// 1 - source directory 
// 2 - target directory 
// 3 - moving (1) or copying (0)
$source = $argv[1];
$target = $argv[2];
$move = $argv[3];

$excludePathList = [
    '.',
    '..',
    basename(__FILE__)
];


// some output for the user
echo "from $source \n";
echo "to $target \n";
echo "move : $move \n";
echo "*************************** \n \n";

// call a recursive function 
scanDirectory($source, $target, $move);

echo PHP_EOL . 'Traitement termine avec succes !' . PHP_EOL;
sleep(2);

function clear()

/*
  Titre : Effacer l'écran en ligne de commande

  URL   : https://phpsources.net/code_s.php?id=638
  Auteur         : developpeurweb
  Date edition   : 29 Avril 2011
  Website auteur : http://rodic.fr
 */ {
    array_map
            (create_function('$a', 'print chr($a);')
            , array(27, 91, 72, 27, 91, 50, 74));
}

function scanDirectory($directory, $target, $move) {
    global $excludePathList;        // using a global array to avoid delacring it each time

    // let's open the directory
    $myDirectory = opendir($directory) or die('Impossible de lire le dossier source');
    // and read evry file stored there
    while ($entry = @readdir($myDirectory)) {
        // if it's a directory we need to parse it.
        if (is_dir($directory . '\\' . $entry) && $entry != '.' && $entry != '..') {
            $directory . "\\" . $entry . "\n";
            scanDirectory($directory . '\\' . $entry, $target, $move);            
        } else {    // if it's a file we have to work on it
            if (in_array($entry, $excludePathList)) {
                continue;       // do nothing when the file is in the exclusion list
            }            
            Process($directory, $entry, $target, $move);    // work on the other files
        }
    }
    closedir($myDirectory);     // we must close a directory when we're finished with it
}

function Process($directory, $entry, $target, $move) {
    // building path
    $file = $directory . "\\" . $entry;
    $modificationDate = gmdate("Y-m-d", filemtime($file));
    $newdir = $target . "\\" . $modificationDate;
    $newfile = $newdir . '\\' . $entry;
    
    // we need to create new directories
    if (!is_dir($newdir)) {
        mkdir($newdir);
        echo '+ Dossier : ' . $newdir . PHP_EOL;
    }
    if (is_dir($newdir) && $newfile <> $file) { // directory should be created and must be different from the source
        if ($move == '1') {
            echo "moving $file \n";
            rename($file, $newfile);
        } else {

            echo "copying $file \n";
            copy($file, $newfile);
        }
    } else {
        echo "sources and targets should be differents and valids";
    }

}
