	<!----------header------------->
	<?php $this->load->view(ADMIN_HEADER);?>
	<!----------header------------->
	<script type="text/javascript" src="<?php echo ADMIN_MODULES?>admin_college_event_module_js.js"></script>
		<div class="pageheader">
		  <h2><i class="fa fa-home"></i>College Events List<!--span>All elements to manage your School...</span--></h2>
		  <!--div class="breadcrumb-wrapper">
			<span class="label">You are here:</span>
			<ol class="breadcrumb">
			  <li><a href="index.php">GetMyUni</a></li>
			  <li class="active">Manage College</li>
			</ol>
		  </div-->
		</div>
                
		<div class="contentpanel"><!-- Content Panel -->
		
			<div class="row mb10"><!-- Exhibitor Row -->
				
				<div class="col-sm-12 col-md-12">
					<a class="btn btn-primary btn-block" href="<?php echo ADMIN_SITE_URL?>school_events/school_event_add_edit_view">Add Event</a>
				</div>
				
			</div>
			
			<div class="row mb10"><!-- Add Exhibitor Row -->
				
				<div class="col-sm-12 col-md-12">
				<!--ul class="filemanager-options">
					<li>
					  <div class="ckbox ckbox-default">
						<input type="checkbox" id="selectall" value="1" />
						<label for="selectall">Select All</label>
					  </div>
					</li>
					
					<li>
					  <a href="#" class="itemopt disabled"><i class="fa fa-trash-o"></i> Delete</a>
					</li>
					<li>
					  <div class="ckbox ckbox-default">
						<select type="select" class="form-control">
							<option value="selected">Associate to Event</option>
							<option value="Event 1">Event 1</option>
							<option value="Event 2">Event 2</option>
						</select>
					  </div>
					</li>
					
					<li>
						<div class="ckbox ckbox-default">
							<input type="text" placeholder="search" class="form-control">
						</div>
					</li>
					<li>
					  <div class="ckbox ckbox-default">
						<select type="select" class="form-control">
							<option value="selected">A to Z</option>
							<option value="A">A</option>
							<option value="B">BZ</option>
						</select>
					  </div>
					</li>
				</ul-->	
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<div class="table-responsive">
          <table class="table" id="table1">
              <thead>
                 <tr>
                    <th class="text-center" style="background-image:none"></th>
                    <th>School Name</th>
                    <th>Event Name</th>
                    <th style="background-image:none">Actions</th>
                   
                 </tr>
              </thead>
              <tbody>
			  <?php 
                
                    if($get_school_event != "no")
                    {
                        foreach($get_school_event as $event_name)
                        {
                        
                 ?>
			
                            <tr class="odd gradeX" id="main-event_<?php echo $event_name['id'];?>">
                                   <td class="text-center"><!--input type="checkbox"--></td>
                                   <td><?php echo $event_name["school_name"];?></td>
                                   <td><?php echo $event_name["event_name"];?></td>
                                   <td class="center">
                                           <!--a href="#"><i class="fa fa-bar-chart-o"></i></a-->
                                           <a href="<?php echo ADMIN_SITE_URL?>school_events/school_event_add_edit_view/<?php echo $event_name['id'];?>"><i class="fa fa-pencil"></i></a>
                                           <a class="delete-event" id="<?php echo $event_name['id'];?>"><i class="fa fa-trash-o"></i></a>
                                   </td>
                            </tr>

                            <?php
                                }
                    }
                            
                            ?>
              </tbody>
           </table>
          </div><!-- table-responsive -->
				</div>
			</div>
		
		</div><!-- contentpanel -->
    
	</div><!-- mainpanel -->

</section>

<?php $this->load->view(ADMIN_FOOTER)?>


