<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * dinosaurteaparty implementation : © Maria Munoz <spukyta@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * dinosaurteaparty.action.php
 *
 * dinosaurteaparty main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/dinosaurteaparty/dinosaurteaparty/myAction.html", ...)
 * 
 */
  
  
  class action_dinosaurteaparty extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "dinosaurteaparty_dinosaurteaparty";
      }
  	} 
 
    public function askTrait() {
      self::setAjaxMode();  

      // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
      $trait_id = self::getArg( "trait_id", AT_posint, true );
      $target_player_id = self::getArg( "target_player_id", AT_posint, true );
      $this->game->askTrait($trait_id, $target_player_id);
      self::ajaxResponse( );
    }

    public function guessDinosaur() {
      self::setAjaxMode();  

      // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
      $dinosaur_id = self::getArg( "dinosaur_id", AT_posint, true );
      $target_player_id = self::getArg( "target_player_id", AT_posint, true );
      $this->game->guessDinosaur($dinosaur_id, $target_player_id);
      self::ajaxResponse( );
    }

  }
  

