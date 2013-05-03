<?php
/*
  $Id: message_stack.php,v 1.1 2003/05/19 19:45:42 hpdl Exp $

  CartStore eCommerce Software, for The Next Generation
  http://www.cartstore.com

  Copyright (c) 2008 Adoovo Inc. USA
  GNU General Public License Compatible

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('general', 'Error: Error 1', 'error');
  $messageStack->add('general', 'Error: Error 2', 'warning');
  if ($messageStack->size('general') > 0) echo $messageStack->output('general');
*/

  class messageStack extends tableBox {

// class constructor
    function messageStack() {
      global $messageToStack;

      $this->messages = array();

      if (tep_session_is_registered('messageToStack')) {
        for ($i=0, $n=sizeof($messageToStack); $i<$n; $i++) {
          $this->add($messageToStack[$i]['class'], $messageToStack[$i]['text'], $messageToStack[$i]['type']);
        }
        tep_session_unregister('messageToStack');
      }
    }

// class methods
    function add($class, $message, $type = 'error') {
      if ($type == 'error') {
        $this->messages[] = array('params' => '', 'class' => $class, 'text' =>  '<div class="ui-widget"><div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">
	<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>' . $message .'</p>
				</div>
			</div>');
      } elseif ($type == 'warning') {
        $this->messages[] = array('params' => '', 'class' => $class, 'text' => '<div class="ui-widget"><div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">
	<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>' . $message .'</p>
				</div>
              </div>
			 ');
      } elseif ($type == 'success') {
        $this->messages[] = array('params' => '', 'class' => $class, 'text' => '<div class="ui-widget">
				<div style="margin-top: 20px; padding: 0pt 0.7em;" class="ui-state-highlight ui-corner-all">
					<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>' . $message . '</p>
				</div>
			</div>');
      } else {
        $this->messages[] = array('params' => '', 'class' => $class, 'text' => $message);
      }
    }

    function add_session($class, $message, $type = 'error') {
      global $messageToStack;

      if (!tep_session_is_registered('messageToStack')) {
        tep_session_register('messageToStack');
        $messageToStack = array();
      }

      $messageToStack[] = array('class' => $class, 'text' => $message, 'type' => $type);
    }

    function reset() {
      $this->messages = array();
    }

    function output($class) {
      $this->table_data_parameters = '';

      $output = array();
      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          $output[] = $this->messages[$i];
        }
      }

      return $this->tableBox($output);
    }

    function size($class) {
      $count = 0;

      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          $count++;
        }
      }

      return $count;
    }
  }
?>
