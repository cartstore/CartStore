<?php
/*
$Id: qbi_classes.php,v 2.10 2005/05/08 al Exp $

Quickbooks Import QBI
contribution for CartStore
ver 2.10 May 8, 2005
(c) 2005 Adam Liberman
www.libermansound.com
info@libermansound.com
Please use the osC forum for support.
GNU General Public License Compatible

    This file is part of Quickbooks Import QBI.

    Quickbooks Import QBI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Quickbooks Import QBI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Quickbooks Import QBI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class iif_form {
	var $form;
	var $where;

	function iif_form() {
	}
	
	function where_clause($string) {
		(!empty($this->where)) ? $this->where.=" AND ".$string : $this->where=" WHERE ".$string;
	}
	
	function form_make($engine,$id_field,$date_field,$i) {
		$html='<div class="createhead">'.constant("CREATE_".strtoupper($engine)."_".$i).'</div>';
		($engine!="customers") ? $dateclause=", MIN(UNIX_TIMESTAMP($date_field)) as date_min, MAX(UNIX_TIMESTAMP($date_field)) as date_max" : $dateclause="";
		$sql="SELECT COUNT(*), MIN($id_field), MAX($id_field)".$dateclause." FROM ".constant("TABLE_".strtoupper($engine)).$this->where;
		$result=tep_db_query($sql);
		if ($myrow=tep_db_fetch_array($result)) {
    		$date_min=date('M j, Y g:i a',$myrow["date_min"]);
    		$date_max=date('M j, Y g:i a',$myrow["date_max"]);
    		$html.='<span class="createlabel">'.CREATE_TOTAL.":</span> ".$myrow["COUNT(*)"]."<br />";
			if ($myrow["COUNT(*)"]>=1) {
				$html.='<span class="createlabel">'.CREATE_NUM_S.":</span> ".$myrow["MIN($id_field)"];
				($myrow["COUNT(*)"]>1) ? $html.=" ".CREATE_TO." ".$myrow["MAX($id_field)"]."<br />" : $html.="<br />";
				if ($engine!="customers") {
					$html.='<span class="createlabel">'.CREATE_DATE_S.":</span> ".$date_min;
					($myrow["COUNT(*)"]>1) ? $html.=" ".CREATE_TO." ".$date_max."<br />" : $html.="<br />";	
				}
				$html.='<form action='.$_SERVER[PHP_SELF].' method="post" name="'.$engine.$i.'">';
				$html.='<input name="engine" type="hidden" value="'.$engine.'" />';
				$html.='<input name="whereclause" type="hidden" value="'.$this->where.'" />';
				$html.='<input name="qbimported" type="hidden" value="'.$i.'" />';
				$html.='<input name="stage" type="hidden" value="process" />';
				$html.='<input name="submit" type="submit" value="'.CREATE_BUTTON.'" />';
				$html.='</form>';
				$html.='<br /><br />';
			} else {
				$html.='<br />';
			}
		}
		$this->form=$html;
	}
	
	function form_display() {
		return $this->form;
	}
}

class form_fields {
	function checkbox($field_id) {
		echo "<tr><td><label for \"".$field_id."\">".constant(strtoupper($field_id)."_L").":</label></td><td><input type=\"checkbox\" name=\"".$field_id."\" id=\"".$field_id."\" value=\"1\""; 
		if (constant(strtoupper($field_id))==1) echo " checked=\"checked\"";
		echo " /></td><td>".constant(strtoupper($field_id)."_C")."</td></tr>\n";
	}
	
	function textbox($field_id,$max_len=0) {
		echo "<tr><td><label for \"".$field_id."\">".constant(strtoupper($field_id)."_L").":</label></td><td><input type=\"text\" name=\"".$field_id."\" id=\"".$field_id."\" value=\"".constant(strtoupper($field_id))."\"";
		if ($max_len>0) echo " maxlength=\"".$max_len."\"";
		echo " /></td><td>".constant(strtoupper($field_id)."_C")."</td></tr>\n";
	}
}

class page_class { 
        var $count = 0; //total pages 
        var $start = 0; //starting record 
        var $pages = 0; //number of pages available 
        var $page = 1; //current page 
        var $maxpages; //shows up to 2 * this number and makes a sliding scale 
        var $show; //number of results per page 
        function page_class($count=0,$show=5,$max=9){ 
                $this->counts = $count; 
                $this->show = $show; 
                $this->maxpages = $max; 
                intval($this->counts/$this->show == 0)? $this->pages = intval($this->counts/$this->show):$this->pages = intval($this->counts/$this->show) +1; 
                global $search_page;
				if(!empty($search_page)){
					$this->page = $search_page;
					$this->start = $this->show * $this->page - $this->show; 
                }
        } 
        function get_limit(){ 
                $limit = ''; 
                if($this->counts > $this->show) $limit = 'LIMIT '.$this->start.','.$this->show; 
                return $limit; 
        } 
        function make_head_string($pre){ 
                $r = $pre.' '; 
                $end = $this->start + $this->show; 
                if($end > $this->counts) $end = $this->counts; 
                $r .= ($this->start +1).' - '.$end.' of '.$this->counts; 
                return $r; 
        } 
        function make_page_string($words='',$pre=MATCH_PAGE){ 
                $r = $pre.' '; 
                if($this->page > 1){ 
                        $y = $this->page - 1; 
                        $r .= '<a href="'.$_SERVER['PHP_SELF'].'?search_page='.$y.$words.'">'.MATCH_PREV.'</a>&nbsp;';
				} else {
                        $r .= MATCH_PREV.'&nbsp;';				
                } 
                $end = $this->page + $this->maxpages-1; 
                if($end > $this->pages) $end = $this->pages; 
                $x = $this->page - $this->maxpages; 
                $anchor = $this->pages - (2*$this->maxpages) +1; 
                if($anchor < 1) $anchor = 1; 
                if($x < 1) $x = 1; 
                if($x > $anchor) $x = $anchor; 
                while($x <= $end){ 
                        if($x == $this->page){ 
                                $r .= '<span class="s">'.$x.'</span>&nbsp;'; 
                        } 
                        else{ 
                                $r.= '<a href="'.$_SERVER['PHP_SELF'].'?search_page='.$x.$words.'">'.$x.'</a>&nbsp;'; 
                        } 
                        $x++; 
                } 
                if($this->page < $this->pages){ 
                        $y = $this->page + 1; 
                        $r .= '<a href="'.$_SERVER['PHP_SELF'].'?search_page='.$y.$words.'">'.MATCH_NEXT.'</a>'; 
                } else {
                        $r .= MATCH_NEXT.'&nbsp;';
				}
                return $r; 
        } 
} 
?>