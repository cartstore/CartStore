<?php
/*
  $Id: message_stack.php,v 1.6 2003/06/20 16:23:08 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA

  GNU General Public License Compatible

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('Error: Error 1', 'error');
  $messageStack->add('Error: Error 2', 'warning');
  if ($messageStack->size > 0) echo $messageStack->output();
*/

  class messageStack extends tableBlock {
    var $size = 0;

    function messageStack() {
      global $messageToStack;

      $this->errors = array();

      if (tep_session_is_registered('messageToStack')) {
        for ($i = 0, $n = sizeof($messageToStack); $i < $n; $i++) {
          $this->add($messageToStack[$i]['text'], $messageToStack[$i]['type']);
        }
        tep_session_unregister('messageToStack');
      }
    }

    function add($message, $type = 'error') {
      if ($type == 'error') {
        $this->errors[] = array('params' => '<div class="ui-widget"><div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all"> 
	<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>
', 'text' => $message.'</p>
				</div>
			</div>');
      } elseif ($type == 'warning') {
        $this->errors[] = array('params' => '<div class="ui-widget"><div style="padding: 0pt 0.7em;" class="ui-state-highlight ui-corner-all"> 
	<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>', 'text' =>  $message.'</p>
				</div>
			</div>');
      } elseif ($type == 'success') {
        $this->errors[] = array('params' => '<div class="ui-widget"><div style="padding: 0pt 0.7em;" class="ui-state-highlight ui-corner-all"> 
	<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>', 'text' =>  $message.'</p>
				</div>
			</div>');
      } else {
        $this->errors[] = array('params' => '<div class="ui-widget"><div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all"> 
	<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>', 'text' => $message.'</p>
				</div>
			</div>');
      }

      $this->size++;
    }

    function add_session($message, $type = 'error') {
      global $messageToStack;

      if (!tep_session_is_registered('messageToStack')) {
        tep_session_register('messageToStack');
        $messageToStack = array();
      }

      $messageToStack[] = array('text' => $message, 'type' => $type);
    }

    function reset() {
      $this->errors = array();
      $this->size = 0;
    }

    function output() {
      $this->table_data_parameters = 'class="messageBox"';
      return $this->tableBlock($this->errors);
    }
  }
?>
