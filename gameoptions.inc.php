<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * dinosaurteaparty implementation : © Maria Munoz <spukyta@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * gameoptions.inc.php
 *
 * dinosaurteaparty game options description
 * 
 * In this file, you can define your game options (= game variants).
 *   
 * Note: If your game has no variant, you don't have to modify this file.
 *
 * Note²: All options defined in this file should have a corresponding "game state labels"
 *        with the same ID (see "initGameStateLabels" in dinosaurteaparty.game.php)
 *
 * !! It is not a good idea to modify this file when a game is running !!
 *
 */

$game_options = array(


    100 => array(
        'name' => totranslate('Game variant'),
        'values' => array(

                    // A simple value for this option:
                    1 => array( 
                        'name' => totranslate('Normal')
                         ),

                    // A simple value for this option.
                    // If this value is chosen, the value of "tmdisplay" is displayed in the game lobby
                    2 => array( 
                        'name' => totranslate('Clever play'), 
                        'description' => totranslate('When the answer to an inquiry is NO, NO token is put down.'),  
                        'tmdisplay' => totranslate('Clever play') 
                        )

                )
    )

);


