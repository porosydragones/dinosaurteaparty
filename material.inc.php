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
 * material.inc.php
 *
 * dinosaurteaparty game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */



$this->trait_names = array(
  1 =>  clienttranslate('drinking'),
  2 =>  clienttranslate('eating'),
  3 =>  clienttranslate('having teeth'),
  4 =>  clienttranslate('having spikes'),
  5 =>  clienttranslate('having tail'),
  6 =>  clienttranslate('having a hat'),
  7 =>  clienttranslate('wearing glasses'),
  8 =>  clienttranslate('wearing jewelry'),
  9 =>  clienttranslate('wearing a flower'),
  10 =>  clienttranslate('being with a pet'),
  11 =>  clienttranslate('being spotted'),
  12 =>  clienttranslate('being striped'),
  13 =>  clienttranslate('being in a green room'),
  14 =>  clienttranslate('being in a purple room'),
  15 =>  clienttranslate('being in a orange room')
);

$this->trait_answers = array (
  true => clienttranslate('"Yes, you are correct!"'),
  false => clienttranslate('"No, I am sorry."')
);

$this->guess_answers = array (
  true => clienttranslate('"Yes, you are correct!"'),
  false => clienttranslate('"No, I am sorry."')
);

