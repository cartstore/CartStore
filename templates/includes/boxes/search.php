<?php

//  require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
?>

<button class="btn btn-info" data-toggle="modal" data-target="#findPnameModul">
	<i class="fa fa-search"></i>
</button>

<!-- Advanced Modal -->

<div class="modal fade" id="findPnameModul" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>

				<ul class="nav nav-pills pull-right">
					<li class="active">
						<a href="#findName" data-toggle="pill">Name</a>
					</li>
					<li>
						<a href="#quickFinder" data-toggle="pill">QuickFinder</a>
					</li>

					<li>
						<a href="#firstLetter" data-toggle="pill">First Letter</a>
					</li>
				</ul>

				<h4 class="modal-title" id="myModalLabel">Search all Categories</h4>
			</div>

			<div class="modal-body">
				<div class="row-fluid">
					<div class="tab-content">

						<div class="tab-pane active" id="findName">
							<div class="controls controls-row">
								<form method="get" action="advanced_search_result.php" name="quick_find">

									 

										<input type="text" onfocus="if(this.value=='Enter Keywords here') this.value='';" onblur="if(this.value=='') this.value='Enter Keywords here';" value="Enter Keywords here" name="keywords" class="form-control">

								 <br>
 
									<?php
  echo tep_draw_pull_down_menu('categories_id', tep_get_categories(array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES))));
?>
 
									 <br>									 <br>


									<input type="submit" value="SEARCH" class="btn btn-primary">

								</form>
							</div>
						</div>

						<div class="tab-pane" id="quickFinder">
							<div class="controls controls-row">

								<?php
	include 'quickfinder.php';
 ?>

							</div>
						</div>

						<div class="tab-pane" id="firstLetter">

							<div class="controls controls-row">

								<table class="table table-bordered">
									<tr>
										<td class="table-hover"><a href="allprods.php?fl=a">A</a></td>
																				<td class="table-hover"><a href="allprods.php?fl=b">B</a></td>

										<td class="table-hover"><a href="allprods.php?fl=c">C</a></td>
										<td class="table-hover"><a href="allprods.php?fl=d">D</a></td>
										<td class="table-hover"><a href="allprods.php?fl=e">E</a></td>
										<td class="table-hover"><a href="allprods.php?fl=f">F</a></td>
										<td class="table-hover"><a href="allprods.php?fl=g">G</a></td>
										<td class="table-hover"><a href="allprods.php?fl=h">H</a></td>
										<td class="table-hover"><a href="allprods.php?fl=i">I</a></td>
									</tr>
									<tr>
										<td class="table-hover"><a href="allprods.php?fl=j">J</a></td>
										<td class="table-hover"><a href="allprods.php?fl=k">K</a></td>
										<td class="table-hover"><a href="allprods.php?fl=l">L</a></td>
										<td class="table-hover"><a href="allprods.php?fl=m">M</a></td>
										<td class="table-hover"><a href="allprods.php?fl=n">N</a></td>
										<td class="table-hover"><a href="allprods.php?fl=o">O</a></td>
										<td class="table-hover"><a href="allprods.php?fl=p">P</a></td>
										<td class="table-hover"><a href="allprods.php?fl=q">Q</a></td>
										<td class="table-hover"><a href="allprods.php?fl=r">R</a></td>
									</tr>
									<tr>
										<td class="table-hover"><a href="allprods.php?fl=s">S</a></td>
										<td class="table-hover"><a href="allprods.php?fl=t">T</a></td>
										<td class="table-hover"><a href="allprods.php?fl=u">U</a></td>
										<td class="table-hover"><a href="allprods.php?fl=v">V</a></td>
										<td class="table-hover"><a href="allprods.php?fl=w">W</a></td>
										<td class="table-hover"><a href="allprods.php?fl=x">X</a></td>
										<td class="table-hover"><a href="allprods.php?fl=y">Y</a></td>
										<td class="table-hover"><a href="allprods.php?fl=z">Z</a></td>
									</tr>
								</table>
								
								<center><a href="allprods.php">All Products</a></center>
								
							</div>
						</div>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>

				</div>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

