{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- dinosaurteaparty implementation : © Maria Munoz <spukyta@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    dinosaurteaparty_dinosaurteaparty.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->
 
  
 
<div id="game_play_area" >  

    <span id="lines_wrap"> 
    <!-- <div id="table_cards_line_0" class="placeholder trait trait1">TRAIT.</div> -->       
    </span>

    <div id="dinosaur_cards"></div>
    

</div> 

<div id="your_hand" class="whiteblock">
<h3>Your hand</h3>
    <div id="my_dinosaur"></div>
</div>

<script type="text/javascript">

// Javascript HTML templates


var jstpl_dinosaur_item = '<div id="table_cards_line_${DINOSAUR_ID}" class="placeholder dinosaur dinosaur${DINOSAUR_ID}" data-dinosaurid="${DINOSAUR_ID}"></div>';

var jstpl_trait_item_normal = '<div id="player_${TRAIT_PLAYER_ID}_trait_${TRAIT_ID}" title="${TRAIT_TITLE}" class="placeholder clickableitem trait trait${TRAIT_ID} trait_normal" data-traitid="${TRAIT_ID}" data-traitplayerid="${TRAIT_PLAYER_ID}"></div>';
var jstpl_trait_item_correct = '<div id="player_${TRAIT_PLAYER_ID}_trait_${TRAIT_ID}" title="${TRAIT_TITLE}" class="placeholder trait trait${TRAIT_ID} trait${TRAIT_ID}_correct" data-traitid="${TRAIT_ID}" data-traitplayerid="${TRAIT_PLAYER_ID}"></div>';
var jstpl_trait_item_incorrect = '<div id="player_${TRAIT_PLAYER_ID}_trait_${TRAIT_ID}" title="${TRAIT_TITLE}" class="placeholder trait trait${TRAIT_ID} trait${TRAIT_ID}_incorrect" data-traitid="${TRAIT_ID}" data-traitplayerid="${TRAIT_PLAYER_ID}"></div>';



var jstpl_guess_item = '<div id="player_guess" title="${GUESS_TITLE}" class="placeholder clickableitem guess_dinosaur" data-playerid="${TRAIT_PLAYER_ID}"><span class="guess_text">${GUESS_TEXT}</span></div>';

var jstpl_my_dinosaur_item = '<div id="my_dino_${MY_DINOSAUR_ID}" class="placeholder mydinosaur mydinosaur${MY_DINOSAUR_ID}" data-dinosaurid="${MY_DINOSAUR_ID}"></div>';

</script>  

{OVERALL_GAME_FOOTER}
