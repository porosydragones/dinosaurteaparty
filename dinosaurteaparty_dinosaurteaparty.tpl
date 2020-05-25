{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- dinosaurteaparty implementation : © <Your name here> <Your email address here>
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
 
  
 
<div id="game_play_area" class="whiteblock">

    <span id="lines_wrap"> 
    <!-- <div id="table_cards_line_0" class="placeholder trait trait1">TRAIT</div> -->       
    </span>

    <div id="dinosaur_cards"></div>
    

</div> 

<script type="text/javascript">

// Javascript HTML templates

/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';

*/

var jstpl_dinosaur_item = '<div id="table_cards_line_${DINOSAUR_ID}" class="placeholder dinosaur dinosaur${DINOSAUR_ID}" data-dinosaurid="${DINOSAUR_ID}">DINOSAUR ${DINOSAUR_ID}</div>';

var jstpl_trait_item = '<div id="player_trait_${TRAIT_ID}" class="placeholder clickableitem trait trait${TRAIT_ID}" data-traitid="${TRAIT_ID}" data-traitplayerid="${TRAIT_PLAYER_ID}">TRAIT ${TRAIT_ID}</div>';

var jstpl_guess_item = '<div id="player_guess" class="placeholder clickableitem guess_dinosaur" data-playerid="${TRAIT_PLAYER_ID}">GUESS</div>';

</script>  

{OVERALL_GAME_FOOTER}
