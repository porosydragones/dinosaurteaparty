<?php
 /**
  *------
  * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
  * dinosaurteaparty implementation : © <Your name here> <Your email address here>
  * 
  * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
  * See http://en.boardgamearena.com/#!doc/Studio for more information.
  * -----
  * 
  * dinosaurteaparty.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );


class dinosaurteaparty extends Table
{
	function __construct( )
	{
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();
        
        self::initGameStateLabels( array( 
                  "current_target_player_id" => 10,
                  "game_play_variant" => 100
            //    "my_first_global_variable" => 10,
            //    "my_second_global_variable" => 11,
            //      ...
            //    "my_first_game_variant" => 100,
            //    "my_second_game_variant" => 101,
            //      ...
        ) );        
	}
	
    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "dinosaurteaparty";
    }	       

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the game
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";
        }
        $sql .= implode( $values, ',' );
        self::DbQuery( $sql );
        self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();
        
        /************ Start the game initialization *****/

        // Init global values with their initial values
        self::setGameStateInitialValue( 'current_target_player_id', 0 );
        
        // Init game statistics
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        //self::initStat( 'table', 'table_teststat1', 0 );    // Init a table statistics
        //self::initStat( 'player', 'player_teststat1', 0 );  // Init a player statistics (for all players)

        // TODO: setup the initial game situation here

        // random 3 quirks
        self::initDinosaurQuirks();
        // assign each player a different dinosaur random
        self::initPlayersDinosaur($players);

        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    // Give a quirk (1,2,3) to 3 different dinosaurs, randomly
    private function initDinosaurQuirks() {
        // select randomly 3 dinosaur id (there are 20)
        $range = range(1, 20); 
        shuffle($range);
        $n = 3;
        $result = array_slice($range, 0 , $n);
        // update dinosaurs to add the 3 quirks
        $updatequirk1 = "UPDATE `dinosaur` SET `dinosaur_quirk` = '1' WHERE `dinosaur`.`dinosaur_id` = ".$result[0];
        $updatequirk2 = "UPDATE `dinosaur` SET `dinosaur_quirk` = '2' WHERE `dinosaur`.`dinosaur_id` = ".$result[1];
        $updatequirk3 = "UPDATE `dinosaur` SET `dinosaur_quirk` = '3' WHERE `dinosaur`.`dinosaur_id` = ".$result[2];  
        self::DbQuery( $updatequirk1 );     
        self::DbQuery( $updatequirk2 );  
        self::DbQuery( $updatequirk3 );  
    } 

    // Give a random dinosaur to each player
    private function initPlayersDinosaur($players) {

        foreach( $players as $player_id => $player )
        {
            self::givePlayerDinosaurAndPersist($player_id);
        }
    }

    // Give a not-used random dinosaur to a player (for set-up and later in game)
    private function givePlayerDinosaurAndPersist($player_id) {
        //inactive current player dinosaur
        $updatesql = "UPDATE `dinosaur` SET `dinosaur_active` = '0' WHERE `dinosaur`.`dinosaur_player_id` = ".$player_id;
        self::DbQuery( $updatesql );  

        $selectsql = "SELECT dinosaur_id FROM dinosaur WHERE dinosaur_active = 1 AND dinosaur_player_id IS NULL";
        //assign a free dinosaur to player, first select free dinosaurs
        $free_dinosaur_id = self::getObjectListFromDB($selectsql,true);
        $random_dinosaur_id=array_rand($free_dinosaur_id,1);
        $updatesql = "UPDATE `dinosaur` SET `dinosaur_player_id` = ".$player_id." WHERE `dinosaur`.`dinosaur_id` = ".$random_dinosaur_id;
        self::DbQuery( $updatesql );  
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas()
    {
        $result = array();
    
        $current_player_id = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_score score FROM player ";
        $result['players'] = self::getCollectionFromDb( $sql );
  
        // TODO: Gather all information about current game situation (visible by player $current_player_id).
        // get my dinosaur
        $my_dinosaur = self::getDinosaurById($current_player_id);
        $result['my_dinosaur'] = $my_dinosaur;
        // get inactive dinosaurs        
        $inactive_dinosaurs = self::getInactiveDinosaurs();
        $result['inactive_dinosaurs'] = $inactive_dinosaurs;   
        // get current traits in all players        
        $player_traits = self::getAllPlayerTraits();    
        $result['player_traits'] = $player_traits;   
        return $result;
    }

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression()
    {
        // TODO: compute and return the game progression

        return 0;
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    /*
        In this space, you can put any utility methods useful for your game logic
    */
    private function getDinosaurById($player_id) {
        $sql = "SELECT dinosaur_id id, dinosaur_name name, dinosaur_quirk quirk, dinosaur_player_id player_id, 
                       dinosaur_quirk3lastanswer quirk3lastanswer,
                       dinosaur_active active
                FROM dinosaur where dinosaur_active = 1 AND dinosaur_player_id = ".$player_id;

        $dinosaur = self::getObjectFromDB( $sql );
        self::dump( "dinosaur", $dinosaur );
        return $dinosaur;
    }

    private function getInactiveDinosaurs() {
        $sql = "SELECT dinosaur_id id, dinosaur_name name, dinosaur_quirk quirk, dinosaur_player_id player_id, 
                       dinosaur_quirk3lastanswer quirk3lastanswer,
                       dinosaur_active active
                FROM dinosaur where dinosaur_active = 0 ";

        $inactive_dinosaurs = self::getObjectListFromDB( $sql );
        self::dump( "inactive_dinosaurs", $inactive_dinosaurs );
        return $inactive_dinosaurs;        
    }

    private function getAllPlayerTraits() {
        $sql = "SELECT `player_trait_player_id`,`player_trait_trait_id`,`player_trait_correct` FROM `player_trait`";
        $player_traits = self::getObjectListFromDB( $sql );
        return $player_traits;           
    }

    private function checkDinosaurTrait($dinosaur_id, $trait_id) {
        $sql = "SELECT dinosaur_id dinosaur_id, trait_id trait_id 
                FROM dinosaur_trait where dinosaur_id = ".$dinosaur_id
                ." AND trait_id=".$trait_id;
        $result = self::getObjectFromDB( $sql );
        self::dump( "checkDinosaurTrait.result", $result );   
        $dinosaurHasTrait = ! empty($result);
        self::dump( "dinosaurHasTrait", $dinosaurHasTrait );
        return ($dinosaurHasTrait);                     
    }

    private function updateDinosaurQuirk3Answer($dinosaur_id, $quirk3lastanswer) {
        $quirk3lastanswer_int = (int) $quirk3lastanswer;
        $sql = "UPDATE dinosaur SET dinosaur_quirk3lastanswer=".$quirk3lastanswer_int." WHERE dinosaur_id=".$dinosaur_id;
        self::DbQuery( $sql );
    }

    private function persistPlayerTrait($player_id, $trait_id,$correct_trait) {
        self::dump( "persistPlayerTrait.player_id", $player_id ); 
        self::dump( "persistPlayerTrait.trait_id", $trait_id ); 
        self::dump( "persistPlayerTrait.correct_trait", $correct_trait ); 
        // boolean to int
        $correct_trait_int = (int) $correct_trait;
        self::dump( "persistPlayerTrait.correct_trait_int", $correct_trait_int );         
        $updatesql = "INSERT INTO player_trait (`player_trait_player_id`, `player_trait_trait_id`, `player_trait_correct`) 
                VALUES ('".$player_id."', '".$trait_id."', '".$correct_trait_int."');";
        self::DbQuery( $updatesql ); 
    }

    private function cleanPlayerTrait( $player_id ) {
        $sql = "DELETE FROM player_trait WHERE player_trait_player_id=".$player_id;
        self::DbQuery( $sql ); 
    }

    // Ask a player for trait, return TRUE if yes, return FALSE if incorrect
    private function askPlayerForTrait($player_id, $trait_id) {
        //look in database if player dinosaur has trait, check quirks

        //get dinosaur of player
        $dinosaur = self::getDinosaurById( $player_id );
        
        //check trait
        $dinosaurHasTrait = self::checkDinosaurTrait($dinosaur["id"],$trait_id);
        self::dump( "askPlayerForTrait.dinosaurHasTrait", $dinosaurHasTrait );
        // player answer is dinosaur has trait unless quirk
        $player_answer = $dinosaurHasTrait;
        // check quirk to change answer if necessary
        self::dump( "askPlayerForTrait.check if dinosaur has quirk", $dinosaur["quirk"] );
        if(! empty($dinosaur["quirk"])) {
            // quirk 1 always says no
            if ($dinosaur["quirk"] == 1){
                $player_answer = 0;
                self::dump( "askPlayerForTrait.has quirk 1, answer no:", $player_answer );
            // quirk 1 always lies, so invert dinosaurHasTrait
            } else if ($dinosaur["quirk"] == 2) {
                $player_answer = ! $dinosaurHasTrait;
                self::dump( "askPlayerForTrait.has quirk 2, insert trait:", $player_answer );
            } else if ($dinosaur["quirk"] == 3) {
                // first answer is random between true or false and then switche
                // if there is no previous answer
                self::dump( "askPlayerForTrait.has quirk 3, check quirk3lastanswer:", $dinosaur["quirk3lastanswer"] );
                if( is_null($dinosaur["quirk3lastanswer"])) {
                    $random_true_false = rand(0,1) == 1;
                    self::dump( "askPlayerForTrait.has quirk and no previous quirk3 answer, generate random and give as answer:", $random_true_false );
                    $player_answer = $random_true_false;
                } else { //next answers invert previous
                    $player_answer = !$dinosaur["quirk3lastanswer"];
                    self::dump( "askPlayerForTrait.has quirk and previous quirk3 answer, invert:", $player_answer );
                }
                // update dinosaur quirk3lastanswer
                self::updateDinosaurQuirk3Answer($dinosaur["id"],$player_answer);
            }
        }
        //persist trait
        // if answer is yes, always persist
        // if answer is no, persist only in normal mode (not in clever mode)        
        if( $player_answer ){
            self::trace( "yes answer, PERSIST" );
            self::persistPlayerTrait($player_id,$trait_id,$player_answer);
        } else {
            $game_play_variant = self::getGameStateValue('game_play_variant');
            self::dump( "game_play_variant", $game_play_variant );
            if( $game_play_variant == 1 ) {
                self::trace( "no answer and NORMAL mode, PERSIST" );
                self::persistPlayerTrait($player_id,$trait_id,$player_answer);
            } else {
                self::trace( "no answer and CLEVER mode, DO NOT PERSIST" );
            }
        }

        return $player_answer;
    }

    private function checkGuessPlayerDinosaur($player_id, $dinosaur_id) { 
        // check if player has dinosaur
        $sql = "SELECT dinosaur_id id FROM dinosaur WHERE dinosaur_active = 1 AND dinosaur_player_id =".$player_id." AND dinosaur_id=".$dinosaur_id;
        //get dinosaur of player 
        $dinosaur_id = self::getObjectFromDB( $sql );
        $correctGuess = ! empty($dinosaur_id);
        return ($correctGuess);
    }

    private function addPointToPlayer($player_id) {
        $sql = "UPDATE player SET player_score = player_score +1 WHERE player_id=".$player_id;
        self::DbQuery( $sql ); 
    }

    private function getPlayerScore($player_id) {
        $selectsql = "SELECT player_score score FROM player WHERE player_id =".$player_id;
        //assign a free dinosaur to player, first select free dinosaurs
        $player = self::getObjectFromDB($selectsql,true);
        return $player['score'];
    }

    private function nextTurnNextPlayer() {
        self::trace( "going to NEXT player" );
        $this->gamestate->nextState( 'nextPlayer' );
    }

    private function playAgainSamePlayer() {
        self::trace( "play again SAME player" );
        $player_id = self::getActivePlayerId();
        self::giveExtraTime( $player_id);
        $this->gamestate->nextstate( 'playAgain' );
    }

    private function goSuccessGuessSamePlayer() {
        self::trace( "success guess SAME player" );
        $player_id = self::getActivePlayerId();
        self::giveExtraTime( $player_id);
        $this->gamestate->nextstate( 'correctGuess' );        
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
//////////// 

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in dinosaurteaparty.action.php)
    */

    /*
    
    Example:

    function playCard( $card_id )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'playCard' ); 
        
        $player_id = self::getActivePlayerId();
        
        // Add your game logic to play a card there 
        ...
        
        // Notify all players about the card played
        self::notifyAllPlayers( "cardPlayed", clienttranslate( '${player_name} plays ${card_name}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'card_name' => $card_name,
            'card_id' => $card_id
        ) );
          
    }
    
    */ 
 
    function askTrait( $trait_id, $target_player_id ) {
        self::trace( "askTrait" );

        self::checkAction( 'askTrait' ); 

        self::dump( "askTrait.trait_id:", $trait_id ); 
        self::dump( "askTrait.target_player_id:", $target_player_id ); 

        $player_id = self::getActivePlayerId();
 
        self::dump( "askTrait.player_id:", $player_id );         

        // Add your game logic to ask trait to player
        $correctAsk = $this->askPlayerForTrait($target_player_id, $trait_id);

        if($correctAsk) {
            self::trace( "correctAsk, congrats!" );
            self::playAgainSamePlayer();
        } else {
            self::trace( "incorrectAsk, sorry" );
            self::nextTurnNextPlayer();
        }

        
        // Notify all players about the card played
        self::notifyAllPlayers( "traitAsked", clienttranslate( '${player_name} ask ${target_player_id} for trait ${trait_id}: ${correctAsk}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'trait_id' => $trait_id,
            'target_player_id' => $target_player_id,
            'correctAsk' => $correctAsk
        ) );  
        
      
    }

    function guessDinosaur( $dinosaur_id, $target_player_id ) {
        self::checkAction( 'guessDinosaur' ); 
        $player_id = self::getActivePlayerId();        

        self::dump( "guessDinosaur.dinosaur_id:", $dinosaur_id ); 
        self::dump( "guessDinosaur.target_player_id:", $target_player_id ); 
        self::dump( "guessDinosaur.player_id:", $player_id );         

        // Add your game logic to play a card there
        $correctGuess = $this->checkGuessPlayerDinosaur($target_player_id, $dinosaur_id);
        if($correctGuess) {
            //save current target_player_id in global
            self::setGameStateInitialValue( 'current_target_player_id', $target_player_id );
            self::dump( "set current_target_player_id", $target_player_id ); 
            // go to state correct guess (there add a point and check end game)
            self::trace( "correctGuess, congrats!" );
            self::goSuccessGuessSamePlayer();
        } else {
            //go to next player
            self::trace( "sorry, not correct" );
            self::nextTurnNextPlayer();
        }
        
        // Notify all players about the card played
        self::notifyAllPlayers( "dinosaurTryGuessed", clienttranslate( '${player_name} ask ${target_player_id} for dinosaur ${dinosaur_id}: ${correctGuess}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'dinosaur_id' => $dinosaur_id,
            'target_player_id' => $target_player_id,
            'correctGuess' => $correctGuess
        ) );
    }


    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    /*
    
    Example for game state "MyGameState":
    
    function argMyGameState()
    {
        // Get some values from the current game situation in database...
    
        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }    
    */


    function argPlayerTurn() {
        return ""; 
    }

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */
    
    /*
    
    Example for game state "MyGameState":

    function stMyGameState()
    {
        // Do some stuff ...
        
        // (very often) go to another gamestate
        $this->gamestate->nextState( 'some_gamestate_transition' );
    }    
    */

    function stNextPlayer() {
        // Activate next player
        $player_id = self::activeNextPlayer();
        self::giveExtraTime( $player_id );
        $this->gamestate->nextState( 'nextTurn' );        
    }

    /**
     * If there is a guess add active a point
     * 
     * If the player has 3, prepare end game
     * If not, next turn same player
     */
    function stCorrectGuess() {
        $target_player_id = self::getGameStateValue('current_target_player_id');
        self::dump( "target_player_id", $target_player_id );    

        // clean player_traits of the target player
        self::cleanPlayerTrait($target_player_id );
        // assign new dinosaur to target player
        self::givePlayerDinosaurAndPersist( $target_player_id );

        // add a point to the active player
        $player_id = self::getActivePlayerId(); 
        self::dump( "player_id", $player_id );  
        self::addPointToPlayer($player_id);

        // get active player score
        $player_score = self::getPlayerScore( $player_id );
        self::dump( "player_score", $player_score );

        // if the player less than 3 points, play again
        if($player_score < 3 ) {
            $this->gamestate->nextstate("playAgain"); 
        } else { //if the player has 3 points, end game
            $this->gamestate->nextstate("prepareEndGame"); 
        }
    }

    function stPrepareEndGame() {
        self::trace( "stPrepareEndGame" );
        $this->gamestate->nextstate("endGame");
    }

//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
    */
 
    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
    
///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */
    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }    
}
