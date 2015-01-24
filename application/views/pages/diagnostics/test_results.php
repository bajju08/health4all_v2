<link rel="stylesheet" href="<?php echo base_url();?>assets/css/metallic.css" >
<script type="text/javascript" src="<?php echo base_url();?>assets/js/zebra_datepicker.js"></script>
<script>
	$(function(){	
		$(".date").Zebra_DatePicker();
	})
</script>
<script>

<!-- Scripts for printing output table -->
function printDiv(i)
{
var content = document.getElementById(i);
var pri = document.getElementById("ifmcontentstoprint").contentWindow;
pri.document.open();
pri.document.write(content.innerHTML);
pri.document.close();
pri.focus();
pri.print();
}
</script>
<iframe id="ifmcontentstoprint" style="height: 0px; width: 0px; position: absolute;display:none"></iframe>

<div class="col-md-10 col-md-offset-2">
<?php 	echo validation_errors(); ?>
<?php if(isset($msg)){ ?> 
	<div class="alert alert-info"> <?php echo $msg;?>
	</div>
	<?php  }?>
<br>
<!--display the age if not 0-->
<?php if(isset($order)){ 
	$age="";
	if($order[0]->age_years!=0) $age.=$order[0]->age_years."Y ";
	if($order[0]->age_months!=0) $age.=$order[0]->age_months."M ";
	if($order[0]->age_days!=0) $age.=$order[0]->age_days."D ";
	?>
	<!--displaying the order date-->	
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Order #<?php echo $order[0]->order_id;?>
			<small>
					<b>Order placed at : </b>
					<?php echo date("g:ia, d-M-Y",strtotime($order[0]->order_date_time));?>
			</small>
			</h4>
		</div>
		<!--displaying the patient visit details-->
		<div class="panel-body">
			<div class="row col-md-12">
				<div class="col-md-4">
					<b>Patient : </b>
					<?php echo $order[0]->first_name." ".$order[0]->last_name." | ".$age." | ".$order[0]->gender; ?>
				</div>
				<div class="col-md-4">
					<b>Type : </b>
					<?php echo $order[0]->visit_type; ?>
				</div>
				<div class="col-md-4">
					<b><?php echo $order[0]->visit_type;?> Number : </b>
					<?php echo $order[0]->hosp_file_no;?>
				</div>
			</div>
			<div class="row col-md-12">
				<div class="col-md-4">
					<b>Department : </b>
					<?php echo $order[0]->department;?>
				</div>
				<div class="col-md-4">
					<b>Unit/Area : </b>
					<?php echo $order[0]->unit_name." / ".$order[0]->area_name;?>
				</div>
				<div class="col-md-4">
					<b>Reported On : </b>
					<?php echo date("g:ia, d-M-Y",strtotime($order[0]->reported_date_time));?>
				</div>
			</div>
			<br />
			<br />
			<br />
			<table class="table table-bordered">
			<!-- patient test results-->
				<th>Test</th
				<th>Value</th>
				<th colspan="4">Report</th>
				<!-- if the test_status value is not equal to 2 then no test is done i.e the test is not completed,else print the result-->
			<?php foreach($order as $test){ 
					$positive="";$negative="";
				 if($test->test_status==2){ $readonly = "disabled"; }else $readonly="";
			?>
			<tr>
					<td>
						<?php echo $test->test_name;?>
					</td>
					<td>
					<?php if($test->numeric_result==1){ 

							if($test->test_status == 2) { 
								$result=$test->test_result." ".$test->lab_unit; 
							} 
							else{	
								$result="Test not done.";
							}
							echo $result;
						}
								else echo "-";
					 ?>
					</td>
					<td>
					<?php if($test->binary_result==1){ ?>
						<?php 
							if($test->test_status == 2) { 
								if($test->test_result_binary == 1 ) $result=$test->binary_positive ; 
								else $result=$test->binary_negative ; 
							} 
							else{	
								$result="Test not done.";
							}
						echo $result;
						?>
					<?php 
					}
						else echo "-";
					?>
						
						<?php if($test->test_result_binary==1 && preg_match("^Culture*^",$test->test_method)) {   //this function matches all occurrences of pattern in string 
						$micro_organism_test_ids = array();
					//	echo $test->micro_organism_test;
						$res = explode("^",trim($test->micro_organism_test,"^"));
						$k=0;
						//grouping all sensitive and resistive antibiotics separately
						//creating arrays to store sensitive and resistant values separately
							$sensitive=array();
							$resistivetive=array();
					
						foreach($res as $r) {
							$temp=explode(",",trim($r," ,")); // Break the strings and store them into an array: using explode() function
								if($temp[3]==1){
								$sensitive[]=array(
									'micro_organism_test_id'=>$temp[0], //storing the type of tests in micro_organism_test_id 
									'antibiotic'=>$temp[2]//store anitbiotic type in variable antibiotic
								);
								}
							if($temp[3]==0){
								$resistive[]=array(
								'micro_organism_test_id'=>$temp[0],//storing resistives in same variables as in sensitive array
								'antibiotic'=>$temp[2]
							);}
							}	
							
						foreach($res as $r) {
							$temp=explode(",",trim($r," ,"));//Break tne strings and store them into an array: using explode() function
							$temp[3]==1?$temp[3]="Sensitive":$temp[3]="Resistant";
						
							if(!in_array($temp[0],$micro_organism_test_ids)){ //in_array Searches for a value in an array
								
								if(count($micro_organism_test_ids)>0) echo "</table></div></div>";
								?>
							
								
								<div class="well well-sm" style="background:white;font-size:0.5em;">
								
								
									<div style="font-size:1.5em;">
										<h5><b><?php echo $temp[1];?></b></h5>
								<!--displaying the antibiotic types in a table-->	
								<table class="table">
								<tr><th style="width:400px;">Sensitive</th><th>Resistant</th></tr>	
								<div class="col-md-2">
							<?php 
								$micro_organism_test_ids[]=$temp[0];
							?>
										
									<tr>
										<td>
											 <!--display all the sensitive antibiotics in series if they belong to their corresponding test-->
										<ol type=1> <?php foreach($sensitive as $se){      
											if($se['micro_organism_test_id']==$temp[0]){
												echo "<li>$se[antibiotic]</li>";
											}
										}
										?></ol>
										</td>
		
										<td>
											<!--display all the resistive antibiotics in series if they belong to their crresponding test-->
										 <ol type=1> <?php foreach($resistive as $re){		
											if($re['micro_organism_test_id']==$temp[0]){       
											echo "<li>$re[antibiotic]</li>";
											}
										}
										?> </ol>
										</td>
									</tr>
							<?php }
							$k++;
							if($k==count($res))
								echo "</table></div></div>";
							}
							
						} ?>
					 </td>
					 <td>
					<?php if($test->text_result==1){ 

						if($test->test_status == 2) { 
							$result = $test->test_result_text;
						} 
						else{	
							$result="Test not done.";
						}
						echo $result;
					 }
								else echo "-"; ?>
					 </td>
				</tr>
			<?php } ?>
			
		</table>
		</div>
		<div class="panel-footer">
		<input type="text" class="sr-only" value="<?php echo $this->input->post('test_area');?>"  name="test_area" readonly /> 
		<input type="text" class="sr-only" value="<?php echo $this->input->post('patient_type_search');?>"  name="patient_type_search" readonly /> 
		<input type="text" class="sr-only" value="<?php echo $this->input->post('hosp_file_no_search');?>"  name="hosp_file_no_search" readonly /> 
		<input type="text" class="sr-only" value="<?php echo $this->input->post('test_method_search');?>"  name="test_method_search" readonly /> 
		<input type="text" class="sr-only" value="<?php echo $this->input->post('from_date');?>"  name="from_date" readonly /> 
		<input type="text" class="sr-only" value="<?php echo $this->input->post('to_date');?>"  name="to_date" readonly /> 	
			<input type="text" value="<?php echo $test->order_id;?>" name="order_id" class="sr-only hidden" />
			<input type="button" value="Print" class="btn btn-primary btn-md col-md-offset-5" name="print_results" onclick="printDiv('print_div')" />
		</div>
	</div>
	
	<div id="print_div" hidden class="sr-only">

					<style media="print">
						html{
							padding:5px;
							width:95%;
							font-size:14px;
						}
						td{
							padding:5px;
						}
						th{
							padding:10px;
						}
						.inner td,.inner th,.inner tr{
							border:1px solid #000;
						}
					</style>
	<img style="position:absolute;top:3%;left:3%;" src="<?php echo base_url();?>assets/images/<?php echo $order[0]->logo;?>" alt="" width="60px" />
	<img style="position:absolute;top:3%;right:5%;" src="<?php echo base_url();?>assets/images/<?php echo $order[0]->accredition_logo;?>" alt="" width="60px" />
	<table border="0">
		<thead>
			<tr>
			<th style="text-align:center" colspan="10">Department of <?php echo $order[0]->test_area;?></th>
			</tr>
			<tr>
			<th style="text-align:center" colspan="10"><?php echo $order[0]->hospital;?>, <?php echo $order[0]->place;?>, <?php echo $order[0]->district;?>, <?php echo $order[0]->state;?><br /></th>
			</tr>
			<tr>
			<th style="text-align:center" colspan="10"><u><?php echo $order[0]->test_method;?> Results</u><br /></th>
			</tr>
		</thead>
		<tbody>
				<tr>
					<td colspan="2">
						<b>Patient : </b>
						<?php echo $order[0]->first_name." ".$order[0]->last_name." | ".$age." | ".$order[0]->gender." :: ".$order[0]->visit_type; ?>
					</td>
					<td>
						<b><?php echo $order[0]->visit_type;?> Number : </b>
						<?php echo $order[0]->hosp_file_no;?>
					</td>
				</tr>
				<tr>
				<td>
					<b>Department : </b>
					<?php echo $order[0]->department;?>
				</td>
				<td>
					<b>Unit/Area: </b>
					<?php echo $order[0]->unit_name." / ".$order[0]->area_name;?>
				</td>
				<td>
					<b>Ordered On : </b>
					<?php echo date("g:ia, d-M-Y",strtotime($order[0]->order_date_time));?>
				</td>
				<td>
					<b>Reported On : </b>
					<?php echo date("g:ia, d-M-Y",strtotime($order[0]->reported_date_time));?>
				</td>
				</tr>
			<tr>
				<td colspan="10" align="center">
					<?php if(preg_match("^Culture*^",$order[0]->test_method)){ ?>  <!-- this function matches all occurrences of pattern in string-->
						
					<table class="inner" style="boder:1px solid #ccc; border-collapse:collapse;">
					<thead>
						<th>Test</th>
						<th colspan="2">Report</th>
					<?php foreach($order as $test){
						$positive="";$negative="";
						 if($test->test_status==2){ $readonly = "disabled"; }else $readonly="";
					?>
							<tr>
								<td>
								<?php echo $test->test_name;?>
								</td>
								<td>
							<?php 
							if($test->text_result==1){ 

								if($test->test_status == 2) { 
									$result = $test->test_result_text;
								} 
								else{	
									$result="Test not done.";
								}
								echo $result;
							 }
								else echo "-"; ?>
							</td>
								<td>
							<?php if($test->binary_result==1){ ?>
								<?php 
									if($test->test_status == 2) { 
										if($test->test_result_binary == 1 ) $result=$test->binary_positive ; 
										else $result=$test->binary_negative ; 
									} 
									else{	
										$result="Test not done.";
									}
								echo $result;
								?>
							<?php } ?>
							
						<?php if($test->test_result_binary==1) { 
						$micro_organism_test_ids = array();
						$res = explode("^",trim($test->micro_organism_test,"^")); //
						$k=0;
						//grouping all sensitive and resistive antibiotics separately
							//creating arrays to store sensitive and resistive values  separately
							$sensitive=array();
							$resistive=array();
						 
						foreach($res as $r) {
							$temp=explode(",",trim($r," ,")); //expolde will Break a string into an array:
								if($temp[3]==1){
								$sensitive[]=array(
									'micro_organism_test_id'=>$temp[0],//storing the type of tests in micro_organism_test_id 
							//store antibiotic type in other varible antibiotic
									'antibiotic'=>$temp[2]
								);
								}
							if($temp[3]==0){ 
								$resistive[]=array(
								'micro_organism_test_id'=>$temp[0], //storing reisitives in same variables as in sensitive array
								'antibiotic'=>$temp[2]
							);
							}
							}	
						foreach($res as $r) {
							$temp=explode(",",trim($r," ,"));
							$temp[3]==1?$temp[3]="<b>Sensitive</b>":$temp[3]="Resistant";
							if(!in_array($temp[0],$micro_organism_test_ids)){  //in_array Searches for a value in an array
								if(count($micro_organism_test_ids)>0) echo "</tbody></table>"
								?>
								<table style="border:1px solid #ccc; border-collapse:collapse;margin:5px;"><thead><th colspan="2" style="text-align:center"><?php echo $temp[1];?></th></thead>
								<tbody>
								<!--displaying the antibiotic types in a table-->
								<tr><th style="width:300px;">Sensitive</th><th>Resistant</th></tr>	
								
							<?php 
								$micro_organism_test_ids[]=$temp[0];
							?>
										
									<tr>
										<td>
											 		<!--display all the sensitive antibiotics in series if they belong to their corresponding test-->
										<ol type=1>
										 <?php foreach($sensitive as $se){
											if($se['micro_organism_test_id']==$temp[0]){        
												echo "<li>$se[antibiotic]</li>"."<br />" ;
											}
										}
										?> </ol>
										</td>
		
										<td>
											<!--display all the resistive antibiotics in series if they belong to their corresponding test-->
										<ol type=1>
										 <?php foreach($resistive as $re){		
											if($re['micro_organism_test_id']==$temp[0]){ 
											echo "<li>$re[antibiotic]</li>"."<br />" ;
											}
										}
										?></ol>
										</td>
									</tr>
								
								
							<?php 
								foreach($temp as $t){?>
							<?php 
								}
								$micro_organism_test_ids[]=$temp[0];
							}
							
							if($k%2==1) echo "</tr>";
							$k++;
							if($k==count($res))
								echo "</tbody></table>";													
							}
						} ?>
						</td>
						</tr>
					<?php } ?>
					</table>
					<?php } 
					else { ?>
					<table class="inner" style="boder:1px solid #ccc; border-collapse:collapse;">
					<thead>
						<th>Test</th>
						<th>Value</th>
						<th colspan="2">Report</th>
					<?php foreach($order as $test){
						$positive="";$negative="";
						 if($test->test_status==2){ $readonly = "disabled"; }else $readonly="";
					?>
							<tr>
								<td>
								<?php echo $test->test_name;?>
								</td>
								<td>
							<?php if($test->numeric_result==1){ 

									if($test->test_status == 2) { 
										$test->test_result?$result=$test->test_result." ".$test->lab_unit : $result=""; 
									} 
									else{
										$result="Test not done.";
									}
									echo $result;
								}
								else echo "-";
							 ?>
								</td>
								<td>
							<?php 
							if($test->text_result==1){ 

								if($test->test_status == 2) { 
									$result = $test->test_result_text;
								} 
								else{	
									$result="Test not done.";
								}
								echo $result;
							 }
								else echo "-"; ?>
							</td>
								<td>
							<?php if($test->binary_result==1){ ?>
								<?php 
									if($test->test_status == 2) { 
										if($test->test_result_binary == 1 ) $result=$test->binary_positive ; 
										else $result=$test->binary_negative ; 
									} 
									else{	
										$result="Test not done.";
									}
								echo $result;
								?>
							<?php } ?>
						</td>
						</tr>
					<?php } ?>
					</table>
					<?php } ?>
				<tr></tr>
				<tr>
					<th colspan="3" align="left"> <br /> <br />Technician</th>
					<th colspan="10"> <br /> <br />Doctor  </th>
				</tr>
		</tbody>
	</table>
	</div>
		
<?php	
	}
	else{
?>
<?php echo form_open('diagnostics/view_results',array('role'=>'form','class'=>'form-custom'));
if(isset($orders)){ ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			Search
		</div>
		<div class="panel-body">
			<input type="text" class="sr-only" value="<?php echo $this->input->post('test_area');?>"  name="test_area" /> 
			<label>Order Dates</label> 
			<input type="text" class="date form-control" placeholder="From Date" value="<?php if($this->input->post('from_date')) $from_date=$this->input->post('from_date'); else $from_date = date("d-M-Y"); echo $from_date;?>" name="from_date" /> 
			<input type="text" class="date form-control" placeholder="To Date" value="<?php if($this->input->post('to_date')) $to_date=$this->input->post('to_date'); else $to_date = date("d-M-Y"); echo $to_date?>"  name="to_date" /> 
			<br />
			<br />
			<label>Test Method</label>
			<select name="test_method_search" class="form-control">
			<option value="" selected>Select</option>
			<?php foreach($test_methods as $test_method){ ?>
				<option value="<?php echo $test_method->test_method_id;?>" <?php if($this->input->post('test_method_search')==$test_method->test_method_id) echo " selected ";?>><?php echo $test_method->test_method;?></option>
			<?php } ?>
			</select>
			<label>Patient Type : </label>
			<select name="patient_type_search" class="form-control">
			<option value="" selected>Select</option>
			<option value="OP" <?php if($this->input->post('patient_type_search')=="OP") echo " selected ";?>>OP</option>
			<option value="IP" <?php if($this->input->post('patient_type_search')=="IP") echo " selected ";?>>IP</option>
			</select>
			<label>Patient #</label>
			<input type="text" class="form-control" name="hosp_file_no_search" value="<?php echo $this->input->post('hosp_file_no_search');?>" />			
		</div>
		<div class="panel-footer">
			<input type="submit" value="Search" name="submit" class="btn btn-primary btn-md" /> 
		</div>
	</div>
	</form>
<?php 
if(count($orders)>0){ ?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4>Test Orders</h4>
	</div>
	<div class="panel-body">
		<table class="table table-bordered table-striped">
		<thead>
			<th>#</th>
			<th>Order ID</th>
			<th>Order By</th>
			<th>Sample Code</th>
			<th>Specimen</th>
			<th>Patient ID</th>
			<th>Patient Name</th>
			<th>Department</th>
			<th>Tests</th>
		</thead>
		<tbody>
			<?php 
			$o=array();
			foreach($orders as $order){
				$o[]=$order->order_id;
			}
			$o=array_unique($o);
			$i=1;
			foreach($o as $ord){	?>
				<tr>
				<?php
				foreach($orders as $order) { 
					if($order->order_id==$ord){ ?>
						<td><?php echo $i++;?></td>
						<td>
							<?php echo form_open('diagnostics/view_results',array('role'=>'form','class'=>'form-custom')); ?>
							<?php echo $order->order_id;?>
							<input type="hidden" class="sr-only" name="order_id" value="<?php echo $order->order_id;?>" />
						</td>
						<td><?php echo $order->staff_name;?></td>
						<td><?php echo $order->sample_code;?></td>
						
						<td><?php echo $order->specimen_type; if($order->specimen_source!="") echo " - ".$order->specimen_source;?> </td><!--printing the specimen source in the test results beside the specimen type if the specimen type is not null-->
						<td><?php echo $order->hosp_file_no;?></td>
						<td><?php echo $order->first_name." ".$order->last_name;?></td>
						<td><?php echo $order->department;?></td>
						<td>
							<?php foreach($orders as $order){
										if($order->order_id == $ord) {
											if($order->test_status==1) 
												$label="label-warning";
											else if($order->test_status == 3){ $label = "label-danger";}
											else if($order->test_status == 2){ $label = "label-success";}
											echo "<div class='label $label'>".$order->test_name."</div><br />";
										}
									} 
							?>
						</td>
						<td>
						<input type="text" class="sr-only" value="<?php echo $this->input->post('test_area');?>"  name="test_area" readonly /> 
						<input type="text" class="sr-only" value="<?php echo $this->input->post('patient_type_search');?>"  name="patient_type_search" readonly /> 
						<input type="text" class="sr-only" value="<?php echo $this->input->post('hosp_file_no_search');?>"  name="hosp_file_no_search" readonly /> 
						<input type="text" class="sr-only" value="<?php echo $this->input->post('test_method_search');?>"  name="test_method_search" readonly /> 
						<input type="text" class="sr-only" value="<?php echo $this->input->post('from_date');?>"  name="from_date" readonly /> 
						<input type="text" class="sr-only" value="<?php echo $this->input->post('to_date');?>"  name="to_date" readonly /> 	
						<button class="btn btn-sm btn-primary" type="submit" value="submit">Select</button></form></td>
				<?php break;
					}
				} ?>
				</tr>
			<?php } ?>
		</tbody>
		</table>
	</div>
	<div class="panel-footer">
		<div class="col-md-offset-4">
		</br>
		
		</div>
	</div>
</div>
<?php 
	}
	else if(count($orders)==0){
		echo "No orders to update";
		}
	}
	else if(count($test_areas)>1){ ?> 
	<?php echo form_open('diagnostics/view_results',array('role'=>'form','class'=>'form-custom')); ?>
		<div class="form-group">
			<label for="test_area">Test Area<font color='red'>*</font></label>
			<select name="test_area" class="form-control"  id="test_area">
				<option value="" selected disabled>Select Test Area</option>
				<?php
					foreach($test_areas as $test_area){ ?>
						<option value="<?php echo $test_area->test_area_id;?>" <?php if($this->input->post('test_area')==$test_area->test_area_id) echo " selected ";?>><?php echo $test_area->test_area;?></option>
				<?php } ?>
			</select>
			<input type="submit" class="btn btn-primary btn-md" name="submit_test_area" value="Select" />
		</div>
	</form>
<?php 
	}
} 
?>
</div>
