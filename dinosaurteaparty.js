/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * dinosaurteaparty implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * dinosaurteaparty.js
 *
 * dinosaurteaparty user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter"
],
function (dojo, declare) {
    return declare("bgagame.dinosaurteaparty", ebg.core.gamegui, {
        constructor: function(){
            console.log('dinosaurteaparty constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;
            this.guessPlayerClicked = null;
            this.dinosaurHandle = null;

        },
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
       putDinosaur: function(dinosaur_id) {
        var node = this.format_block("jstpl_dinosaur_item", {
            DINOSAUR_ID: dinosaur_id
        });
        dojo.place(node, "dinosaur_cards");             
        }, 
        
        putTrait: function(trait_id, player_id, player_board_div) {
            var node = this.format_block("jstpl_trait_item", {
                TRAIT_ID: trait_id,
                TRAIT_PLAYER_ID: player_id
            });
            dojo.place(node, player_board_div);             
        }, 

        putGuessDinosaur: function(player_id, player_board_div) {
            var node = this.format_block("jstpl_guess_item", {
                TRAIT_PLAYER_ID: player_id
            });
            dojo.place(node, player_board_div);    
        },

        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );
            
            console.log('inactive_dinosaurs->' + JSON.stringify(gamedatas.inactive_dinosaurs));
            console.log('player_traits->' + JSON.stringify(gamedatas.player_traits));    
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                         
                // TODO: Setting up players boards if needed
                var player_board_div = $('player_board_'+player_id);
                for( var $j = 1; $j <=15; $j++ ) {
                    this.putTrait($j, player['id'],player_board_div);
                } 
                if(this.player_id != player_id) {
                    this.putGuessDinosaur(player['id'],player_board_div);                 
                }

                console.log('mydinosaur->' + JSON.stringify(gamedatas.my_dinosaur));
            }

            for( var $i = 1; $i <=20; $i++ ) {
                this.putDinosaur($i);
            }
          
            
            // TODO: Set up your game interface here, according to "gamedatas"
            dojo.query(".trait").connect("onclick", this, "onTraitClick");
            dojo.query(".guess_dinosaur").connect("onclick", this, "onGuessClick"); 
 
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            console.log( "Ending game setup" );
        },
        

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Show some HTML block at this game state
                dojo.style( 'my_html_block_id', 'display', 'block' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
/*               
                 Example:
 
                 case 'myGameState':
                    
                    // Add 3 action buttons in the action status bar:
                    
                    this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' ); 
                    this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' ); 
                    this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' ); 
                    break;
*/
                }
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        
        /*
        
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
        
        */

        callDinosaurGuess: function( dinosaur_id, target_player_id ) {
            console.log('callDinosaurGuess.dinosaur_id: ' + dinosaur_id + '.target_player_id:' + target_player_id);
            this.ajaxcall( "/dinosaurteaparty/dinosaurteaparty/guessDinosaur.html", { 
                lock: true, 
                dinosaur_id:  dinosaur_id,
                target_player_id: this.guessPlayerClicked
            }, 
            this, function( result ) {
            }, function( is_error) {
            } ); 
        },

        ///////////////////////////////////////////////////
        //// Player's action
        
        /*
        
            Here, you are defining methods to handle player's action (ex: results of mouse click on 
            game objects).
            
            Most of the time, these methods:
            _ check the action is possible at this game state.
            _ make a call to the game server
        
        */
        
        /* Example:
        
        onMyMethodToCall1: function( evt )
        {
            console.log( 'onMyMethodToCall1' );
            
            // Preventing default browser reaction
            dojo.stopEvent( evt );

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'myAction' ) )
            {   return; }

            this.ajaxcall( "/dinosaurteaparty/dinosaurteaparty/myAction.html", { 
                                                                    lock: true, 
                                                                    myArgument1: arg1, 
                                                                    myArgument2: arg2,
                                                                    ...
                                                                 }, 
                         this, function( result ) {
                            
                            // What to do after the server call if it succeeded
                            // (most of the time: nothing)
                            
                         }, function( is_error) {

                            // What to do after the server call in anyway (success or failure)
                            // (most of the time: nothing)

                         } );        
        },        
        
        */
 
        // Current player click a trait of another player
        onTraitClick: function( evt ) {
            console.log( 'onTraitClick' );
            // Preventing default browser reaction
            dojo.stopEvent( evt );  

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'askTrait' ) ){    return;  }                      

            this.ajaxcall( "/dinosaurteaparty/dinosaurteaparty/askTrait.html", { 
                    lock: true, 
                    trait_id: evt.currentTarget.dataset.traitid,
                    target_player_id: evt.currentTarget.dataset.traitplayerid
                }, 
                this, function( result ) {
                }, function( is_error) {
                } );               
            
        },


        // Current player clicks the guess button in a player board, then enable click on dinosaur
        onGuessClick: function( evt) {
            console.log( 'onGuessClick' );
            // Preventing default browser reaction
            dojo.stopEvent( evt );   
            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'guessDinosaur' ) ){   return;  } 
            
            this.guessPlayerClicked = evt.currentTarget.dataset.playerid;
            // enable dinosaur click
            this.dinosaurHandle = dojo.query(".dinosaur").connect("onclick", this, "onDinosaurClick"); 
            dojo.query(".dinosaur").addClass("clickableitem");
        },         

        // Current player click a dinosaur to guess a player
        onDinosaurClick: function( evt ) {
            // Preventing default browser reaction
            dojo.stopEvent( evt );   
            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'guessDinosaur' ) ){   return;  } 
            // return if the player has not clicked first on a dinosaur
            if( this.guessPlayerClicked == null) {return ;}

            // disable dinosaur click 
            dojo.query(".dinosaur").removeClass("clickableitem");
            //dojo.disconnect(this.dinosaurHandle);

            this.callDinosaurGuess(evt.currentTarget.dataset.dinosaurid,this.guessPlayerClicked);
            this.guessPlayerClicked = null;  
        },

        
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your dinosaurteaparty.game.php file.
        
        */
        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );
            
            // TODO: here, associate your game notifications with local methods
            
            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            
           // dojo.subscribe( 'traitAsked', this, "notif_traitAsked" );
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 
        },  
        
        // TODO: from this point and below, you can write your game notifications handling methods
        
        /*
        Example:
        
        notif_cardPlayed: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            console.log( notif );
            
            // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call
            
            // TODO: play the card in the user interface.
        },    
        
        */
/* 
       notif_traitAsked: function( notif )
       {
           console.log( 'notif_traitAsked' );
           console.log( notif );
           
           // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call
           
           // TODO: play the card in the user interface.
       }, */        
   });             
});
