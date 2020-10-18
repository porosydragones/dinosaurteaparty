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
            this.clickableitem_class = 'clickableitem';
            this.traitnormal_class = 'trait_normal';

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

            var node = this.format_block("jstpl_trait_item_normal", {
                TRAIT_ID: trait_id,
                TRAIT_PLAYER_ID: player_id
            });
            dojo.place(node, player_board_div);             
        }, 
        
        putTraitCorrect: function(trait_id, player_id, player_board_div) {
            var node = this.format_block("jstpl_trait_item_correct", {
                TRAIT_ID: trait_id,
                TRAIT_PLAYER_ID: player_id
            });
            dojo.place(node, player_board_div);             
        }, 

        putTraitIncorrect: function(trait_id, player_id, player_board_div) {
            var node = this.format_block("jstpl_trait_item_incorrect", {
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

                
       putMyDinosaur: function(my_dinosaur_id) {
        var node = this.format_block("jstpl_my_dinosaur_item", {
            MY_DINOSAUR_ID: my_dinosaur_id
        });
        dojo.place(node, "my_dinosaur");             
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
            console.log( "Starting game setup" );
                     
            console.log('inactive_dinosaurs->' + JSON.stringify(gamedatas.inactive_dinosaurs));
            console.log('player_traits->' + JSON.stringify(gamedatas.player_traits));   
            console.log('players -> ' + JSON.stringify(gamedatas.players)); 
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                console.log('PLAYER ID->' + player_id);     
                player_str = player_id.toString();     
                console.log('PLAYER ID STR->' + player_str);                      

                player_traits = gamedatas.player_traits[player_str];
                console.log('player_traits.PLAYER ID->' + JSON.stringify(player_traits));                  

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
                    this.putGuessDinosaur(player['id'],player_board_div);                 
                } 

            }

            // remove clickable class to currect player
            this.removeClickableClassForPlayerTraits(this.player_id);

            // put dinosaurs in order
            console.log('dinosaur_order->' + JSON.stringify(gamedatas.dinosaur_order));
            for( var $i = 0; $i < gamedatas.dinosaur_order.length; $i++) {
                this.putDinosaur(gamedatas.dinosaur_order[$i ]);
            }
            
            console.log('mydinosaur->' + JSON.stringify(gamedatas.my_dinosaur));
            console.log('mydinosaur id->' + gamedatas.my_dinosaur['id']);
            this.putMyDinosaur(gamedatas.my_dinosaur['id']);
          
            
            // TODO: Set up your game interface here, according to "gamedatas"
            dojo.query(".trait_normal").connect("onclick", this, "onTraitClick");
            dojo.query(".guess_dinosaur").connect("onclick", this, "onGuessClick"); 


            //inactive dinosaurs, add inactivedinosaur or activedinosaur class
            console.log('inactive_dinosaurs->' + JSON.stringify(gamedatas.inactive_dinosaurs));

            console.log('quirk_1_dinosaur_id ->' + gamedatas.quirk_1_dinosaur_id);
            console.log('quirk_2_dinosaur_id ->' + gamedatas.quirk_2_dinosaur_id);
            console.log('quirk_3_dinosaur_id ->' + gamedatas.quirk_3_dinosaur_id);                        
            for($i=1; $i<=20; $i++) {
                var dinosaur_id_to_change = ".dinosaur" + $i;
                if(gamedatas.inactive_dinosaurs.includes($i)) {
                    //console.log( "Dinosaur is INACTIVE "+$i );
                    dojo.query(dinosaur_id_to_change).addClass("dinosaur" + $i + "_inactive");                    
                } else {
                    //console.log( "Dinosaur is ACTIVE "+$i );
                    dojo.query(dinosaur_id_to_change).addClass("dinosaur_active");                
                }

                //add quirk
                // if dinosaur has quirk1
                if( gamedatas.quirk_1_dinosaur_id == $i ) {
                    dojo.query(dinosaur_id_to_change).addClass("quirk1");   
                    console.log('add quirk1 to id' + $i);                    
                } else if ( gamedatas.quirk_2_dinosaur_id == $i ) { // if dinosaur has quirk2
                    dojo.query(dinosaur_id_to_change).addClass("quirk2");   
                    console.log('add quirk2 to id' + $i);                     
                } else if ( gamedatas.quirk_3_dinosaur_id == $i ) { // if dinosaur has quirk3
                    dojo.query(dinosaur_id_to_change).addClass("quirk3");   
                    console.log('add quirk3 to id' + $i);                     
                }
            }

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
            
            // Check if trait is normal and not for current player
            if(this.player_id == evt.currentTarget.dataset.traitplayerid) {
                console.log('cannot click your own traits');
                return;
            }

            // Check if trait is normal, not correct or incorrect
            if(!evt.currentTarget.className.includes(this.traitnormal_class)) {
                console.log('cannot click correct or incorrect traits');
                return;
            }

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
            this.dinosaurHandle = dojo.query(".dinosaur_active").connect("onclick", this, "onDinosaurClick"); 
            dojo.query(".dinosaur_active").addClass(this.clickableitem_class);
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
            dojo.query(".dinosaur_active").removeClass(this.clickableitem_class);
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
            
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 

            dojo.subscribe('traitAsked',this,"notif_traitAsked");

            dojo.subscribe('dinosaurTryGuessed',this,"notif_dinosaurTryGuessed");

            dojo.subscribe('newDinosaurAssigned',this,"notif_newDinosaurAssigned");
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

       notif_traitAsked: function( notif )
       {
           console.log( 'notif_traitAsked' );
           console.log( notif );

           var correctTrait = notif.args.correctAsk;
           var target_player_id = notif.args.target_player_id;
           var player_board_div = $('player_board_'+target_player_id);
           var trait_id = notif.args.trait_id;

           console.log( 'notif_traitAsked.correctTrait= ' +correctTrait  );
           console.log( 'notif_traitAsked.target_player_id= ' +target_player_id  );    
           console.log( 'notif_traitAsked.player_board_div= ' +player_board_div  );  
           console.log( 'notif_traitAsked.trait_id= ' +trait_id  );        
           var player_trait_to_change = "#player_" + target_player_id + "_trait_" + trait_id;
           console.log( 'notif_traitAsked.player_trait_to_change= ' +player_trait_to_change  );  

           // remove from trait: clickable item so cursos is not pointer
           // and trait_normal to not link function to trait
           if(correctTrait) {
            console.log( 'notif_traitAsked.correctAsk' );
            var correct_class_to_add = 'trait' + trait_id + '_correct';
            console.log( 'notif_traitAsked.correct_class_to_add= ' +correct_class_to_add  );   
            dojo.query(player_trait_to_change).addClass(correct_class_to_add);  
           } else {
            console.log( 'notif_traitAsked.INcorrectAsk' );
            var incorrect_class_to_add = 'trait' + trait_id + '_incorrect';
            console.log( 'notif_traitAsked.incorrect_class_to_add= ' +incorrect_class_to_add  );   
            dojo.query(player_trait_to_change).addClass(incorrect_class_to_add);                   
           }
           // in both correct or incorrect: remove clickable from trait
           dojo.query(player_trait_to_change).removeClass(this.traitnormal_class);  
           dojo.query(player_trait_to_change).removeClass(this.clickableitem_class);  


           // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call
       }, 
       
       notif_dinosaurTryGuessed: function (notif) 
       {
        console.log( 'notif_dinosaurTryGuessed' );
        console.log( notif );

        var correctGuess = notif.args.correctGuess;
        var dinosaur_id = notif.args.dinosaur_id;
        var target_player_id = notif.args.target_player_id;

        // only if correct guess, change dinosaur to inactive
        if(correctGuess) {
            console.log( 'notif_dinosaurTryGuessed.correctGuess' );
            var dinosaur_to_change = "#table_cards_line_" +  dinosaur_id;
            var inactive_class_to_add = 'dinosaur' + dinosaur_id + '_inactive';
            console.log( 'notif_dinosaurTryGuessed.dinosaur_to_change=' + dinosaur_to_change );
            console.log( 'notif_dinosaurTryGuessed.inactive_class_to_add=' + inactive_class_to_add );
            dojo.query(dinosaur_to_change).addClass(inactive_class_to_add);
            this.revertPlayerTraitsToNormalClickable(target_player_id);
        } else {
            console.log( 'notif_dinosaurTryGuessed.INcorrectGuess' );
        }
       },

       notif_newDinosaurAssigned: function (notif) 
       {

        console.log( 'notif_newDinosaurAssigned' );
        console.log( notif );
        var target_player_id = notif.args.target_player_id;
            // give player its new dinosaur
            if(target_player_id == this.player_id) {
                var old_dinosaur_id = notif.args.old_dinosaur_id;                
                var new_dinosaur_id = notif.args.new_dinosaur_id;
                var my_dino_to_remove = '#my_dino_' +  old_dinosaur_id;
                console.log( 'notif_newDinosaurAssigned.my_dino_to_remove= ' + my_dino_to_remove );
                dojo.query(my_dino_to_remove).remove();
                console.log( 'notif_newDinosaurAssigned.putMyDinosaur= ' + new_dinosaur_id);
                this.putMyDinosaur(new_dinosaur_id);
            }
       },
   });             
});
