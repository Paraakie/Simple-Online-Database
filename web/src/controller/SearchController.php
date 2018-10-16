<?php
/**
 * Created by PhpStorm.
 * @author Sven Gerhards
 * Date: 10/10/2018
 * Time: 5:56 PM
 */

namespace jis\a2\controller;

use jis\a2\view\View;

class SearchController extends Controller
{
    /**
     * Display the Search page
     */
    public function search(){

        $view = new View('search');
        echo $view->render();
    }

    /**
     * Return the matching Items
     */
    public function returnMatches(){
        // Array with names
        $a[] = "Anna";
        $a[] = "Brittany";
        $a[] = "Cinderella";
        $a[] = "Diana";
        $a[] = "Eva";
        $a[] = "Fiona";
        $a[] = "Gunda";
        $a[] = "Hege";
        $a[] = "Inga";
        $a[] = "Johanna";
        $a[] = "Kitty";
        $a[] = "Linda";
        $a[] = "Nina";
        $a[] = "Ophelia";
        $a[] = "Petunia";
        $a[] = "Amanda";
        $a[] = "Raquel";
        $a[] = "Cindy";
        $a[] = "Doris";
        $a[] = "Eve";
        $a[] = "Evita";
        $a[] = "Sunniva";
        $a[] = "Tove";
        $a[] = "Unni";
        $a[] = "Violet";
        $a[] = "Liza";
        $a[] = "Elizabeth";
        $a[] = "Ellen";
        $a[] = "Wenche";
        $a[] = "Vicky";

        // get the q parameter from URL
        $q = $_REQUEST["q"];

        $hint = "";

        // lookup all hints from array if $q is different from ""
        if ($q !== "") {
            $q = strtolower($q);
            $len=strlen($q);
            foreach($a as $name) {
                if (stristr($q, substr($name, 0, $len))) {
                    if ($hint === "") {
                        $hint = $name;
                    } else {
                        $hint .= ", $name";
                    }
                }
            }
        }

        // Output "no suggestion" if no hint was found or output correct values
        echo $hint === "" ? "no suggestion" : $hint;
    }
}
