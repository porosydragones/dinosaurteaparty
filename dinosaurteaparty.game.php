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
                  "progression_multiplier" => 20,                  
                  "game_play_variant" => 100
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
        // Progression multiplier per correct guess, 3 players 15%, 4 players 12%, 5 players 10%
        $num_players = count($players);
        if($num_players == 3) {
            self::setGameStateInitialValue( 'progression_multiplier', 15 );
        } else  if ($num_players == 4) {
            self::setGameStateInitialValue( 'progression_multiplier', 12 );
        } else {
            self::setGameStateInitialValue( 'progression_multiplier', 15 );
        }
        // Init game statistics
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        self::initStat( 'table', 'turns_number', 0);
        self::initStat( 'player', 'player_correct_trait', 0);        

        // setup the initial game situation here


        //random order in dinosaurs
        self::initDinosaursOrder();
        // random 3 quirks
        self::initDinosaurQuirks();
        // assign each player a different dinosaur random
        self::initPlayersDinosaur($players);

        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    // Randomize order of dinosaurs, so they appear in different order in the table each game
    private function initDinosaursOrder() {
        $range = range(1, 20); 
        shuffle($range);
        //self::dump( "range", $range );
        $i=1;
        foreach( $range as $range_value) {
            $updateorder = "UPDATE `dinosaur` SET `dinosaur_order` = '.$range_value.' WHERE `dinosaur`.`dinosaur_id` = ".$i;
            self::DbQuery( $updateorder );     
            $i++;
        }
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
    $random_first_dinos = self::uniqueRandomNumbersWithinRange(1,20,count($players));
        $d=0;
        foreach( $players as $player_id => $player )
        {
            $player_first_dino_id = $random_first_dinos[$d];
            $updatesql = "UPDATE `dinosaur` SET `dinosaur_player_id` = ".$player_id." WHERE `dinosaur`.`dinosaur_id` = ".$player_first_dino_id;
            self::DbQuery( $updatesql );  
            $d++;
        }
    }

    function uniqueRandomNumbersWithinRange($min, $max, $quantity) {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }

    // Give a not-used random dinosaur to a player
    private function givePlayerDinosaurAndPersist($player_id) {
        //inactive current player dinosaur
        $updatesql = "UPDATE `dinosaur` SET `dinosaur_active` = '0' WHERE `dinosaur`.`dinosaur_player_id` = ".$player_id;
        self::DbQuery( $updatesql );  

        $selectsql = "SELECT dinosaur_id FROM dinosaur WHERE dinosaur_active = '1' AND dinosaur_player_id IS NULL";
        //assign a free dinosaur to player, first select free dinosaurs
        $free_dinosaur_id = self::getObjectListFromDB($selectsql,true);
        $random_dinosaur_key=array_rand($free_dinosaur_id,1);
        $updatesql = "UPDATE `dinosaur` SET `dinosaur_player_id` = ".$player_id." WHERE `dinosaur`.`dinosaur_id` = ".$free_dinosaur_id[$random_dinosaur_key];
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
        $players_info = self::getCollectionFromDb( $sql );
        $result['players'] = $players_info;
  
        // TODO: Gather all information about current game situation (visible by player $current_player_id).
        // get dinosaurs order
        $result['dinosaur_order'] = self::getDinosaursOrder();
        // get inactive dinosaurs        
        $inactive_dinosaurs = self::getInactiveDinosaurs();
        $result['inactive_dinosaurs'] = $inactive_dinosaurs;   
        // get dinos traits
        $result['dinosaur_traits'] = self::getAllDinosTraits();

        // get current traits in all players        
        $player_traits = self::getAllPlayerTraits($players_info);    
        $result['player_traits'] = $player_traits;   
        // get my dinosaur
        $my_dinosaur = self::getDinosaurById($current_player_id);
        $result['my_dinosaur'] = $my_dinosaur;  
        
        $quirk_1_dinosaur_id = self::getDinosaurQuirk(1);
        $result['quirk_1_dinosaur_id'] = $quirk_1_dinosaur_id;          
        $quirk_2_dinosaur_id = self::getDinosaurQuirk(2);
        $result['quirk_2_dinosaur_id'] = $quirk_2_dinosaur_id;          
        $quirk_3_dinosaur_id = self::getDinosaurQuirk(3);
        $result['quirk_3_dinosaur_id'] = $quirk_3_dinosaur_id;  

        $result['trait_names_texts'] = $this->trait_names;


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
        $max_score = self::getMaxScoreOfPlayers();    
        // if a player has 3 points, return 100 (end of game)        
        if($max_score == 3 ) {
            return 100;
        } else {
            // progression is number of correct guess * progression_multiplier
            // number of correct guess is count of inactive dinosaurs
            $num_inactive_dinosaurs = count(self::getInactiveDinosaurs());    
            $progression_multiplier = self::getGameStateValue('progression_multiplier');       
            $progression =  $num_inactive_dinosaurs * $progression_multiplier;     

            return $progression;        
        }

    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    /*
        In this space, you can put any utility methods useful for your game logic
    */
    private function getDinosaurById($player_id) {
        $sql = "SELECT dinosaur_id id, dinosaur_name name, dinosaur_quirk quirk, dinosaur_player_id player_id, 
                       dinosaur_quirk3lastanswer quirk3lastanswer, dinosaur_order,
                       dinosaur_active active
                FROM dinosaur where dinosaur_active = 1 AND dinosaur_player_id = ".$player_id;

        $dinosaur = self::getObjectFromDB( $sql );      

        return $dinosaur;
    }

    private function getDinosaurQuirk ($quirk_id) {
        $sql = "SELECT dinosaur_id id
            FROM dinosaur WHERE dinosaur_quirk = ".$quirk_id;

        $quirk_dinosaur_id = self::getObjectListFromDB( $sql , true );
        return $quirk_dinosaur_id;         
    }

    private function getDinosaursOrder( ) {
        $sql = "SELECT dinosaur_id id
            FROM dinosaur order by dinosaur_order ";

        $dinosaur_order = self::getObjectListFromDB( $sql , true );
        return $dinosaur_order; 
    }

    private function getInactiveDinosaurs() {
        $sql = "SELECT dinosaur_id id
                FROM dinosaur where dinosaur_active = 0 ";

        $inactive_dinosaurs = self::getObjectListFromDB( $sql , true );


        $inactive_dinosaurs_int = [];
        for( $i=0; $i< count($inactive_dinosaurs); $i++) {
            $inactive_dinosaurs_int[$i] = (int) $inactive_dinosaurs[$i];
        }

        return $inactive_dinosaurs_int;        
    }

    private function getAllDinosTraits() {
        $dinos_all_trait_data = [];

        for($d=1 ; $d <= 20 ; $d++ ) {
            // get dino traits
            $dinos_all_trait_data[$d] = self::getDinosaurTraits($d);
        }

        return $dinos_all_trait_data;
    }

    private function getAllPlayerTraits($players_info) {

        $player_all_trait_data = [];
        foreach ($players_info as $player_value) {
           $player_trait_data = [];
            $player_id_traits =  self::getPlayerTraits( $player_value['id'] );           
            foreach ($player_id_traits as $player_trait_value) {
                $player_trait_data[$player_trait_value['player_trait_trait_id']] = (int)$player_trait_value['player_trait_correct'];
            }     
            $player_all_trait_data[$player_value['id']]  = $player_trait_data;
        }
        return  $player_all_trait_data;   
    }

    private function getPlayerTraits($playerid) {
        $sql = "SELECT `player_trait_player_id`,`player_trait_trait_id`,`player_trait_correct` FROM `player_trait` WHERE player_trait_player_id = ".$playerid;
        $player_traits = self::getObjectListFromDB( $sql );

        return $player_traits;     
    }

    private function getMaxScoreOfPlayers() {
        $sql = "select max(player_score) FROM player";
        $max_score = self::getUniqueValueFromDB($sql);
        return $max_score;
    }

    private function checkDinosaurTrait($dinosaur_id, $trait_id) {
        $sql = "SELECT dinosaur_id dinosaur_id, trait_id trait_id 
                FROM dinosaur_trait where dinosaur_id = ".$dinosaur_id
                ." AND trait_id=".$trait_id;
        $result = self::getObjectFromDB( $sql );

        $dinosaurHasTrait = ! empty($result);

        return ($dinosaurHasTrait);                     
    }

    private function getDinosaurTraits($dinosaur_id) {
        $sql = "SELECT trait_id 
            FROM dinosaur_trait where dinosaur_id = ".$dinosaur_id;
        $result = self::getObjectListFromDB( $sql, true);

        return ($result);    
    }

    private function updateDinosaurQuirk3Answer($dinosaur_id, $quirk3lastanswer) {
        $quirk3lastanswer_int = (int) $quirk3lastanswer;
        $sql = "UPDATE dinosaur SET dinosaur_quirk3lastanswer=".$quirk3lastanswer_int." WHERE dinosaur_id=".$dinosaur_id;
        self::DbQuery( $sql );
    }

    private function persistPlayerTrait($player_id, $trait_id,$correct_trait) {
        // boolean to int
        $correct_trait_int = (int) $correct_trait;       
        $updatesql = "INSERT INTO player_trait (`player_trait_player_id`, `player_trait_trait_id`, `player_trait_correct`) 
                VALUES ('".$player_id."', '".$trait_id."', '".$correct_trait_int."');";
        self::DbQuery( $updatesql ); 
    }

    private function cleanPlayerTrait( $player_id ) {
        $sql = "DELETE FROM player_trait WHERE player_trait_player_id=".$player_id;
        self::DbQuery( $sql ); 
    }

    private function addPlayerCorrectTraitStat($player_id) {
      self::incStat(1,'player_correct_trait',$player_id);      
    }

    private function addTurnStat() {
       self::incStat(1,'turns_number');
    }

    // Ask a player for trait, return TRUE if yes, return FALSE if incorrect
    private function askPlayerForTrait($player_id, $trait_id) {
        //look in database if player dinosaur has trait, check quirks

        //get dinosaur of player
        $dinosaur = self::getDinosaurById( $player_id );
        
        //check trait
        $dinosaurHasTrait = self::checkDinosaurTrait($dinosaur["id"],$trait_id);
        // player answer is dinosaur has trait unless quirk
        $player_answer = $dinosaurHasTrait;
        // check quirk to change answer if necessary
        if(! empty($dinosaur["quirk"])) {
            // quirk 1 always says no
            if ($dinosaur["quirk"] == 1){
                $player_answer = 0;
            // quirk 2 always lies, so invert dinosaurHasTrait
            } else if ($dinosaur["quirk"] == 2) {
                $player_answer = ! $dinosaurHasTrait;
            } else if ($dinosaur["quirk"] == 3) {
                // first answer is random between true or false and then switche
                // if there is no previous answer
                if( is_null($dinosaur["quirk3lastanswer"])) {
                    $random_true_false = rand(0,1) == 1;
                    $player_answer = $random_true_false;
                } else { //next answers invert previous
                    $player_answer = !$dinosaur["quirk3lastanswer"];
                }
                // update dinosaur quirk3lastanswer
                self::updateDinosaurQuirk3Answer($dinosaur["id"],$player_answer);
            }
        }
        //persist trait
        // if answer is yes, always persist
        // if answer is no, persist only in normal mode (not in clever mode)        
        if( self::isNormalVariantOrYesAnswer($player_answer) ){
            self::persistPlayerTrait($player_id,$trait_id,$player_answer);
        } 

        return $player_answer;
    }

    
    // in normal variant all answers are persisted and marked
    // in clever variant only yes answers are persisted and marked in traits tokens (always notified to log)
    // if (normal variant answer yes/no) OR (clever anwer yes): persist and mark answer
    private function isNormalVariantOrYesAnswer($player_answer) {
        $game_play_variant = self::getGameStateValue('game_play_variant');
        return (( $game_play_variant == 1 )  || (  $player_answer ));
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
        // go to text player
        $this->gamestate->nextState( 'nextPlayer' );
    }

    private function playAgainSamePlayer() {
        // play again same player
        $player_id = self::getActivePlayerId();
        self::giveExtraTime( $player_id);
        $this->gamestate->nextstate( 'playAgain' );
    }

    private function goSuccessGuessSamePlayer() {
        //success guess same player
        $player_id = self::getActivePlayerId();
        self::giveExtraTime( $player_id);
        $this->gamestate->nextstate( 'correctGuess' );        
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
//////////// 
 
    function askTrait( $trait_id, $target_player_id ) {
        // ask trait
        self::checkAction( 'askTrait' ); 

        $player_id = self::getActivePlayerId();       

        // Add your game logic to ask trait to player
        $correctAsk = $this->askPlayerForTrait($target_player_id, $trait_id);

        if($correctAsk) {
            //add correct trait to player stat
            self::addPlayerCorrectTraitStat($player_id);
            self::playAgainSamePlayer();
        } else {
            // incorrect ask
            self::nextTurnNextPlayer();
        }


        // Notify all players about the trait asked and they will mark the trait token as correct or incorrect
        // if answer is yes, always mark token
        // if answer is no, mark only in normal mode (not in clever mode)   
        self::notifyAllPlayers( "traitAsked", clienttranslate( '${player_name} asks ${player_name2} about ${trait_name}: ${answer}' ), array(
            'i18n' => array( 'trait_name', 'answer' ),
            'player_id' => $player_id,
            'player_name' => self::getUniqueValueFromDb( "SELECT player_name FROM player WHERE player_id = $player_id" ),
            'player_name2' => self::getUniqueValueFromDb( "SELECT player_name FROM player WHERE player_id = $target_player_id" ),            
            'trait_name' => $this->trait_names[$trait_id],
            'trait_id' =>$trait_id,            
            'target_player_id' => $target_player_id,
            'correctAsk' => $correctAsk,
            'answer' => $this->trait_answers[$correctAsk],
            'markTraitToken' => self::isNormalVariantOrYesAnswer($correctAsk)
        ) );
      
    }

    function guessDinosaur( $dinosaur_id, $target_player_id ) {
        self::checkAction( 'guessDinosaur' ); 
        $player_id = self::getActivePlayerId();
        $dinosaur_name = self::getUniqueValueFromDb( "SELECT dinosaur_name FROM dinosaur WHERE dinosaur_id = $dinosaur_id" );  
        $new_dinosaur_id = null;
        $new_dinosaur_name = '';             

        // Add your game logic to play a card there
        $correctGuess = $this->checkGuessPlayerDinosaur($target_player_id, $dinosaur_id);
         
        // Notify all players about the guess
        self::notifyAllPlayers( "dinosaurTryGuessed", clienttranslate( '${player_name} asks ${player_name2}: "Are you ${dinosaur_name}?" ${answer}' ), array(
            'i18n' => array(  'answer' ),
            'player_id' => $player_id,
            'player_name' => self::getUniqueValueFromDb( "SELECT player_name FROM player WHERE player_id = $player_id" ), 
            'player_score' => (self::getPlayerScore( $player_id ) + 1), // 1 more point because point is added after this
            'target_player_id' => $target_player_id,
            'player_name2' => self::getUniqueValueFromDb( "SELECT player_name FROM player WHERE player_id = $target_player_id" ),               
            'dinosaur_id' => $dinosaur_id,
            'dinosaur_name' => $dinosaur_name, 
            'correctGuess' => $correctGuess,
            'answer' => $this->guess_answers[$correctGuess]
        ) );


        if($correctGuess) {
            //save current target_player_id in global
            self::setGameStateValue( 'current_target_player_id', $target_player_id );
            // go to state correct guess (there add a point and check end game)

            // clean player_traits of the target player
            self::cleanPlayerTrait($target_player_id );
            // assign new dinosaur to target player
            self::givePlayerDinosaurAndPersist( $target_player_id );

            //get new dinosaur of target player
            $new_dinosaur = self::getDinosaurById( $target_player_id );
            $new_dinosaur_id = $new_dinosaur['id'];
            $new_dinosaur_name = $new_dinosaur['name']; 

           // Notify player about their new dinosaur
            self::notifyPlayer( $target_player_id, "newDinosaurAssigned", clienttranslate('Your new dinosaur is: ${new_dinosaur_name}'), array(
                'target_player_id' => $target_player_id,
                'old_dinosaur_id' => $dinosaur_id,
                'new_dinosaur_id' => $new_dinosaur_id,
                'new_dinosaur_name' => $new_dinosaur_name
            ));

            self::goSuccessGuessSamePlayer();
        } else {
            //go to next player, not correct
            self::nextTurnNextPlayer();
        }
    }


    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////



//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    function stNextPlayer() {
        // Activate next player
        $player_id = self::activeNextPlayer();
        self::giveExtraTime( $player_id );
        self::addTurnStat();
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

        // add a point to the active player
        $player_id = self::getActivePlayerId(); 
        self::addPointToPlayer($player_id);

        // get active player score
        $player_score = self::getPlayerScore( $player_id );

        // if the player less than 3 points, go to next player 
        if($player_score < 3 ) {
            $this->gamestate->nextstate("nextPlayer"); 
        } else { //if the player has 3 points, end game
            $this->gamestate->nextstate("prepareEndGame"); 
        }
    }

    function stPrepareEndGame() {
        //prepare end game
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
                    self::nextTurnNextPlayer();
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
