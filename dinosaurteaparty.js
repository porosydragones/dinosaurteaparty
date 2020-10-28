/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * dinosaurteaparty implementation : © Maria Munoz <spukyta@gmail.com>
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
              
            // Here, you can init the global variables of your user interface
            this.guessPlayerClicked = null;
            this.dinosaurHandle = null;
            this.clickableitem_class = 'dtp_clickableitem';
            this.traitnormal_class = 'trait_normal';
            this.dinosauractive_class='dinosaur_active';
            this.dinosauractiveglow_class='guess_glow_dinos';
            this.trait_names_texts=null;

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
        
        putTraitNormal: function(trait_id, player_id, player_board_div) {
            
            // Get a string in player's language with parameter:
            var translated_trait_title = dojo.string.substitute( _("Ask about ${trait_name}"), {
                trait_name: this.trait_names_texts[trait_id]
            } );

            var node = this.format_block("jstpl_trait_item_normal", {
                TRAIT_ID: trait_id,
                TRAIT_PLAYER_ID: player_id,
                TRAIT_TITLE: translated_trait_title
            });
            dojo.place(node, player_board_div);    
        }, 
        
        putTraitCorrect: function(trait_id, player_id, player_board_div) {
            // Get a string in player's language with parameter:
            var translated_trait_title = dojo.string.substitute( _("Yes: ${trait_name}"), {
                trait_name: this.trait_names_texts[trait_id]
            } );

            var node = this.format_block("jstpl_trait_item_correct", {
                TRAIT_ID: trait_id,
                TRAIT_PLAYER_ID: player_id,
                TRAIT_TITLE: translated_trait_title
            });
            dojo.place(node, player_board_div);             
        }, 

        putTraitIncorrect: function(trait_id, player_id, player_board_div) {
            // Get a string in player's language with parameter:
            var translated_trait_title = dojo.string.substitute( _("No: ${trait_name}"), {
                trait_name: this.trait_names_texts[trait_id]
            } );
            var node = this.format_block("jstpl_trait_item_incorrect", {
                TRAIT_ID: trait_id,
                TRAIT_PLAYER_ID: player_id,
                TRAIT_TITLE: translated_trait_title
            });
            dojo.place(node, player_board_div);             
        }, 

        putGuessDinosaur: function(player_id, player_board_div,player_name) {
            var translated_guess = _("GUESS");

            // Get a string in player's language with parameter:
            var translated_guess_title = dojo.string.substitute( _("Guess the dinosaur of the player ${player_name_tag}"), {
                player_name_tag: player_name
            } );
            var node = this.format_block("jstpl_guess_item", {
                TRAIT_PLAYER_ID: player_id,
                GUESS_TITLE: translated_guess_title,
                GUESS_TEXT: translated_guess
            });
            dojo.place(node, player_board_div);    
        },

                
       putMyDinosaur: function(my_dinosaur_id) {
           if(my_dinosaur_id != null ){
                var node = this.format_block("jstpl_my_dinosaur_item", {
                    MY_DINOSAUR_ID: my_dinosaur_id
                });
                dojo.place(node, "my_dinosaur");     
            }        
        }, 

        removeClickableClassForPlayerTraits: function (player_id) {
            // remove all the 15 trait clickable class for player 
            for( var $t = 1; $t <=15; $t++ ) {
                var player_trait_to_change = "#player_" + player_id + "_trait_" + $t;
                dojo.query(player_trait_to_change).removeClass(this.clickableitem_class);  
            }
        },

        revertPlayerTraitsToNormalClickable: function (player_id) {
            // make the 15 traits clickable and normal
            for( var $t = 1; $t <=15; $t++ ) {
                var player_trait_to_change = "#player_" + player_id + "_trait_" + $t;
                dojo.query(player_trait_to_change).addClass(this.traitnormal_class);  
                var correct_class_to_add = 'trait' + $t + '_correct';
                var incorrect_class_to_add = 'trait' + $t + '_incorrect';
                dojo.query(player_trait_to_change).removeClass(correct_class_to_add);   
                dojo.query(player_trait_to_change).removeClass(incorrect_class_to_add);  
                // add clickable only if the player is not the same
                if(player_id != this.player_id) {
                    dojo.query(player_trait_to_change).addClass(this.clickableitem_class);  
                }
            }
        },

        setup: function( gamedatas )
        {
            this.trait_names_texts = gamedatas.trait_names_texts;
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id]; 
                player_str = player_id.toString();                          
                player_traits = gamedatas.player_traits[player_str];                 

                var player_board_div = $('player_board_'+player_id);
                for( var $j = 1; $j <=15; $j++ ) {

                    if ($j in player_traits) {
                        if(player_traits[$j] == 1) {
                            this.putTraitCorrect($j, player['id'],player_board_div);                            
                        }  else {
                            this.putTraitIncorrect($j, player['id'],player_board_div);                            
                        }
                    } else {
                        this.putTraitNormal($j, player['id'],player_board_div);                        
                    }     
                } 

                // do not put guess button for player, only for other players
                if(this.player_id != player_id) {
                    this.putGuessDinosaur(player['id'],player_board_div,player['name']);                 
                } 

            }

            // remove clickable class to currect player
            this.removeClickableClassForPlayerTraits(this.player_id);

            // put dinosaurs in order
            for( var $i = 0; $i < gamedatas.dinosaur_order.length; $i++) {
                this.putDinosaur(gamedatas.dinosaur_order[$i ]);
            }
            
            //for spectator my_dinosaur_id is null
            var my_dinosaur_id = null;
            if(gamedatas.my_dinosaur != null && gamedatas.my_dinosaur['id'] != null ) {
                my_dinosaur_id = gamedatas.my_dinosaur['id'];
            }
            this.putMyDinosaur(my_dinosaur_id);
          
            
            //  Set up your game interface here, according to "gamedatas"
            dojo.query(".trait_normal").connect("onclick", this, "onTraitClick");
            dojo.query(".guess_dinosaur").connect("onclick", this, "onGuessClick"); 


            //inactive dinosaurs, add inactivedinosaur or activedinosaur class                        
            for($i=1; $i<=20; $i++) {
                var dinosaur_id_to_change = ".dinosaur" + $i;
                if(gamedatas.inactive_dinosaurs.includes($i)) {
                    dojo.query(dinosaur_id_to_change).addClass("dinosaur" + $i + "_inactive");                    
                } else {
                    dojo.query(dinosaur_id_to_change).addClass(this.dinosauractive_class);                
                }

                //add quirk
                // if dinosaur has quirk1
                if( gamedatas.quirk_1_dinosaur_id == $i ) {
                    dojo.query(dinosaur_id_to_change).addClass("quirk1");                    
                } else if ( gamedatas.quirk_2_dinosaur_id == $i ) { // if dinosaur has quirk2
                    dojo.query(dinosaur_id_to_change).addClass("quirk2");                      
                } else if ( gamedatas.quirk_3_dinosaur_id == $i ) { // if dinosaur has quirk3
                    dojo.query(dinosaur_id_to_change).addClass("quirk3");                    
                }
            }

            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();
        },
        

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            
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
            // Preventing default browser reaction
            dojo.stopEvent( evt );  

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'askTrait' ) ){    return;  }      
            
            // Check if trait is normal and not for current player
            if(this.player_id == evt.currentTarget.dataset.traitplayerid) {
                return;
            }         

            // Check if trait is normal, not correct or incorrect
            if(!evt.currentTarget.className.includes(this.traitnormal_class)) {
                return;
            }

            // disable dinosaur click and cancel guess button if clicked before trait
            this.guessPlayerClicked = null;
            dojo.query(".dinosaur_active").removeClass(this.clickableitem_class);
            dojo.query(".dinosaur_active").removeClass(this.dinosauractiveglow_class);   

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
            // Preventing default browser reaction
            dojo.stopEvent( evt );   
            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'guessDinosaur' ) ){   return;  } 
            
            this.guessPlayerClicked = evt.currentTarget.dataset.playerid;
            // enable dinosaur click
            this.dinosaurHandle = dojo.query(".dinosaur_active").connect("onclick", this, "onDinosaurClick"); 
            dojo.query(".dinosaur_active").addClass(this.clickableitem_class);
            dojo.query(".dinosaur_active").addClass(this.dinosauractiveglow_class);
        },         

        // Current player click a dinosaur to guess a player
        onDinosaurClick: function( evt ) {
            // Preventing default browser reaction
            dojo.stopEvent( evt );   
            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'guessDinosaur' ) ){   return;  } 
            // return if the player has not clicked first on a dinosaur
            if( this.guessPlayerClicked == null) {return ;}

            // return if the dinosaur is not active
            this.dinosaur_inactive_class = 'dinosaur' +  evt.currentTarget.dataset.dinosaurid + '_inactive';
            if(evt.currentTarget.className.includes(this.dinosaur_inactive_class)) {
                return;
            }

            // disable dinosaur click 
            dojo.query(".dinosaur_active").removeClass(this.clickableitem_class);
            dojo.query(".dinosaur_active").removeClass(this.dinosauractiveglow_class);
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
            
            dojo.subscribe('traitAsked',this,"notif_traitAsked");

            dojo.subscribe('dinosaurTryGuessed',this,"notif_dinosaurTryGuessed");

            dojo.subscribe('newDinosaurAssigned',this,"notif_newDinosaurAssigned");
        },  
        
        //  from this point and below, you can write your game notifications handling methods
        // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call


        
        // Players are notified about the trait asked and they will mark the trait token as correct or incorrect
        // the variable markTraitToken indicates if the token should be marked or not
        // if answer is yes, always mark token
        // if answer is no, mark only in normal mode (not in clever mode)   
       notif_traitAsked: function( notif )
       {
        var markTraitToken = notif.args.markTraitToken;

        // indicates if the token should be marked or not
        if(markTraitToken) {
            var correctTrait = notif.args.correctAsk;
            var target_player_id = notif.args.target_player_id;
            var player_board_div = $('player_board_'+target_player_id);
            var trait_id = notif.args.trait_id;
        
            var player_trait_to_change = "#player_" + target_player_id + "_trait_" + trait_id;

            // remove from trait: clickable item so cursos is not pointer
            // and trait_normal to not link function to trait
            if(correctTrait) {
                var correct_class_to_add = 'trait' + trait_id + '_correct';   
                dojo.query(player_trait_to_change).addClass(correct_class_to_add);  
            } else {
                var incorrect_class_to_add = 'trait' + trait_id + '_incorrect';
                dojo.query(player_trait_to_change).addClass(incorrect_class_to_add);                   
            }
            // in both correct or incorrect: remove clickable from trait
            dojo.query(player_trait_to_change).removeClass(this.traitnormal_class);  
            dojo.query(player_trait_to_change).removeClass(this.clickableitem_class);  
            }
       }, 
       
       notif_dinosaurTryGuessed: function (notif) 
       {

        var correctGuess = notif.args.correctGuess;
        var dinosaur_id = notif.args.dinosaur_id;
        var target_player_id = notif.args.target_player_id;

        // only if correct guess, change dinosaur to inactive
        if(correctGuess) {
            var dinosaur_to_change = "#table_cards_line_" +  dinosaur_id;
            var inactive_class_to_add = 'dinosaur' + dinosaur_id + '_inactive';
            dojo.query(dinosaur_to_change).addClass(inactive_class_to_add);
            this.revertPlayerTraitsToNormalClickable(target_player_id);
            // update player score
            var div_player_score = 'player_score_'+ notif.args.player_id; 
            document.getElementById(div_player_score).innerHTML = notif.args.player_score ;
        } 

       },

       notif_newDinosaurAssigned: function (notif) 
       {

        var target_player_id = notif.args.target_player_id;
            // give player its new dinosaur
            if(target_player_id == this.player_id) {
                var old_dinosaur_id = notif.args.old_dinosaur_id;                
                var new_dinosaur_id = notif.args.new_dinosaur_id;
                var my_dino_to_remove = '#my_dino_' +  old_dinosaur_id;
                dojo.query(my_dino_to_remove).remove();
                this.putMyDinosaur(new_dinosaur_id);
            }
       },
   });             
});
