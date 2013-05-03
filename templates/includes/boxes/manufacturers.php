<?php
  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
  if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {
?>
<div class="module">
	<div>
		<div>
			<div>
				<h3>SHOP BY BRAND</h3>
				<ul>
					<!-- manufacturers //-->
					<?php
					$info_box_contents = array();
					$info_box_contents[] = array('text' => BOX_HEADING_MANUFACTURERS);
					if ($number_of_rows <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {
						$manufacturers_list = '';
						while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
							$manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
							if (isset($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] == $manufacturers['manufacturers_id']))
								$manufacturers_name = '<b>' . $manufacturers_name . '</b>';
							$manufacturers_list .= '<li><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id']) . '" class="manufactures"><span>' . $manufacturers_name . '</span></a> </li> ';
						}//while ($manufacturers = tep_db_fetch_array($manufacturers_query))
						$manufacturers_list = substr($manufacturers_list, 0, -4);
						$info_box_contents = array();
						$info_box_contents[] = array('text' => $manufacturers_list);
					}//if ($number_of_rows <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST)
					else {
						$manufacturers_array = array();
						if (MAX_MANUFACTURERS_LIST < 2) {
							$manufacturers_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
						}//if (MAX_MANUFACTURERS_LIST < 2)
						while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
							$manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
							$manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers_name);
						}//while ($manufacturers = tep_db_fetch_array($manufacturers_query))
						$info_box_contents = array();
						$info_box_contents[] = array('form' => tep_draw_form('manufacturers', tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get'), 'text' => tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($_GET['manufacturers_id']) ? $_GET['manufacturers_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '"') . tep_hide_session_id());
					}//else
					new infoBox($info_box_contents);
				?>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php
} //if ($number_of_rows = tep_db_num_rows($manufacturers_query))
?>