{include file="$template/includes/tablelist.tpl" tableName="ServicesList" filterColumn="3"}
<script type="text/javascript">
    jQuery(document).ready( function ()
    {
        var table = jQuery('#tableServicesList').removeClass('hidden').DataTable();
        {if $orderby == 'product'}
            table.order([0, '{$sort}'], [3, 'asc']);
        {elseif $orderby == 'amount' || $orderby == 'billingcycle'}
            table.order(1, '{$sort}');
        {elseif $orderby == 'nextduedate'}
            table.order(2, '{$sort}');
        {elseif $orderby == 'domainstatus'}
            table.order(3, '{$sort}');
        {/if}
        table.draw();
        jQuery('#tableLoading').addClass('hidden');
        
        
        $(".view_attorney").on("click", function(e) {
	        $("#attorney_name").html($(this).data('attorneyname'));
	        $.get("index.php?m=smd&action=get_attorney_details&id=" + ($(this).data('attorneyid')))
	        .done(function(data) {
		        $("#attorney_details").html(data);
		        $("#lawer_details").modal();
	        
		    });
	    });
	   


	    
    });
	
	function myFunction() {
		return confirm("You are about to select an attorney for free 1-hour consultation. Once selected you will not be able to change this selection. Are you sure?")
    }
	
</script>


 {if $pending_attorney}
<div class="alert alert-warning">
Your consultation request has been sent to {$pending_attorney.selected_attorney.firstname} {$pending_attorney.selected_attorney.lastname}. You will receive an email once your request has been accepted or rejected.
</div>
{/if}



<div class="wrapper"> 
	<div class="container">
		<div class="main-content">
			<div class="tab-option-left">
				<form id="attorney_filter" name="attorney_filter" method="post" action="index.php?m=smd&action=view_network_attorneys">
					<input type="hidden" name="action" value="network_attorney_filter">
					<h2>
						<span>filter by</span>/&nbsp;  <a href="" class="clear_all">Clear all</a>		
					</h2>
					<h6>Enter zip code</h6> 
					<input type="text" name="attorney_location" id="attorney_location" maxlength="5" value="{$postvar['attorney_location']}" placeholder="5 digits only" style="width:200px;">
					
					<h6>Distance</h6>
					<!-- <input type="number" name="attorney_miles" min="0" step="5" style="width:200px;"> -->
							<select name="attorney_distance">
								<option value="WITHIN 5 MILES">WITHIN 5 MILES</option>
								<option value="WITHIN 10 MILES">WITHIN 10 MILES </option>
								<option value="WITHIN 25 MILES">WITHIN 25 MILES </option>
								<option value="WITHIN 50 MILES">WITHIN 50 MILES </option>
								<option value="WITHIN 75 MILES">WITHIN 75 MILES </option>
								<option value="WITHIN 100 MILES">WITHIN 100 MILES </option>
								
							</select>
					<div class="ather-lag">
							<h6>Preferred Language</h6>
							<select name="attorney_language">
								<option value="" {if $postvar['attorney_language'] == ""} selected {/if}>Select Language</option>
								<option value="English"{if $postvar['attorney_language'] == "English"} selected {/if}>English</option>
								<option value="French" {if $postvar['attorney_language'] == "French"} selected {/if}>French </option>
								<option value="Spanish" {if $postvar['attorney_language'] == "Spanish"} selected {/if}>Spanish </option>
								
							</select>
						</div>	
							
					<h6>average hourly rate </h6>
						{$var_avg_hourly_rate= $postvar['attorney_avg_hourly_rate']|replace:' ':''}
						<select class="" name="attorney_avg_hourly_rate">
							<option value="" {if $var_avg_hourly_rate == ""} selected {/if}>Select Rate</option>
			            	<option value="100 - 150"{if $var_avg_hourly_rate == "100-150"} selected {/if}>$100 - $150</option>
			            	<option value="150 - 200"{if $var_avg_hourly_rate == "150-200"} selected {/if}>$150 - $200</option>
			            	<option value="200 - 250"{if $var_avg_hourly_rate == "200-250"} selected {/if}>$200 - $250</option>
			            	<option value="250 - 300"{if $var_avg_hourly_rate == "250-300"} selected {/if}>$250 - $300</option>
			            	<option value="300 - 350"{if $var_avg_hourly_rate == "300-350"} selected {/if}>$300 - $350</option>
			            	<option value="350 - 400"{if $var_avg_hourly_rate == "350-400"} selected {/if}>$350 - $400</option>
			            	<option value="400 - 450"{if $var_avg_hourly_rate == "400-450"} selected {/if}>$400 - $450</option>
			            	<option value="450 - 500"{if $var_avg_hourly_rate == "450-500"} selected {/if}>$450 - $500</option>
			            	<option value="500"{if $var_avg_hourly_rate == "500"} selected {/if}>$500+</option>
						</select>
							
					<h6>minimum retainer </h6>
						{$var_minimum_retainer = $postvar['attorney_minimum_retainer']|replace:' ':''}
						<select class="" name="attorney_minimum_retainer">
							<option value="" {if $var_minimum_retainer == ""} selected {/if}>Select Rate</option>
			            	<option value="1000 - 1500" {if $var_minimum_retainer =="1000-1500"} selected {/if}>$1000 - $1500</option>
			            	<option value="1500 - 2000"{if $var_minimum_retainer == "1500-2000"} selected {/if}>$1500 - $2000</option>
			            	<option value="2000 - 2500"{if $var_minimum_retainer == "2000-2500"} selected {/if}>$2000 - $2500</option>
			            	<option value="2500 - 3000"{if $var_minimum_retainer == "2500-3000"} selected {/if}>$2500 - $3000</option>
			            	<option value="3000 - 3500"{if $var_minimum_retainer == "3000-3500"} selected {/if}>$3000 - $3500</option>
			            	<option value="3500 - 4000"{if $var_minimum_retainer == "3500-4000"} selected {/if}>$3500 - $4000</option>
			            	<option value="4000 - 4500"{if $var_minimum_retainer == "4000-4500"} selected {/if}>$4000 - $4500</option>
			            	<option value="5000" {if $var_minimum_retainer == "5000"} selected {/if}>$5000+</option>
			            	
						</select>	
					<h6>Hourly Rate Discount % </h6>
					{$var_hourly_rate_discount= $postvar['hourly_rate_discount']|replace:' ':''}
					<select class="attorney_hourly_rate_discount" name="hourly_rate_discount">
						<option value="" {if $var_hourly_rate_discount == ""} selected {/if}>Select Rate</option>
		            	<option value="10"{if $var_hourly_rate_discount == "10"} selected {/if}>10</option>
		            	<option value="15"{if $var_hourly_rate_discount == "15"} selected {/if}>15</option>
		            	<option value="20"{if $var_hourly_rate_discount == "20"} selected {/if}>20</option>
		            	<option value="25"{if $var_hourly_rate_discount == "25"} selected {/if}>25</option>
		            	<option value="30"{if $var_hourly_rate_discount == "30"} selected {/if}>30</option>
		            	<option value="35"{if $var_hourly_rate_discount == "35"} selected {/if}>35</option>
		            	<option value="40"{if $var_hourly_rate_discount == "40"} selected {/if}>40</option>
		            	<option value="45"{if $var_hourly_rate_discount == "45"} selected {/if}>45</option>
		            	<option value="50"{if $var_hourly_rate_discount == "50"} selected {/if}>50</option>
					</select>

					<div class="search_btn">
						<input type="submit" name="submit" value="Search">
					</div>
				</form>
			</div>
			<div class="tab-option-right">
			{if not $attorneys}NO RECORDS FOUND{/if} 
				{foreach $attorneys as $attorney} 
				{if !$attorney.spouse_selected}
					<div class="description sec">
						<div class="description-img">
							<img src="https://enourady.sirv.com/SMD/profile_pictures/{$attorney.userid}.jpg?scale.width=145">
						</div>
						<div class="des-info">
							<h2>{$attorney.firstname} {$attorney.lastname}</h2>
							{*<ul class="srar-view">
								<li>
									<ul>
										<li><i class="fa fa-star" aria-hidden="true"></i></li>
										<li><i class="fa fa-star" aria-hidden="true"></i></li>
										<li><i class="fa fa-star" aria-hidden="true"></i></li>
										<li><i class="fa fa-star" aria-hidden="true"></i></li>
										<li><i class="fa fa-star" aria-hidden="true"></i></li>
										17 Reviews
									</ul>
								</li>
								<li>Avvo Rating:{$attorney.avvo_rating}</li>
								<li>Year Licensed {$attorney.YearLicensed}</li>
							</ul>*}
							<p></p>
							<p></p>
							<div class="info-contact left-create">
								<ul>
									<li><i class="fa fa-phone" aria-hidden="true"></i>{$attorney.phoneNumber}</li>
									<li><i class="fa fa-envelope" aria-hidden="true"></i>{$attorney.email}</li>
									<li><i class="fa fa-globe" aria-hidden="true"></i>{$attorney.website}</li>
								</ul>
							</div>
							<div class="info-contact left-create">
								<ul>
									<li>Hourly rate</li>
									<li>Minimum Retainer</li>
				   				    <li>Member Discount</li>
								</ul>
							</div>
							<div class="info-contact left-create">
								<ul>
									<li>${$attorney.HourlyRate|replace:'$':''}</li>
									<li>${$attorney.MinimumRetainer}</li>
									<li>{$attorney.HourlyRateDiscount}%</li>
								</ul>
							</div>
							<div class="info-contact left-create"> 
								{if $pending_attorney}
										<a href="javascript:void(0)" class="btn btn-primary gray-btn">Request Consultation</a>
								{else if $selected_attorney}
										{if $attorney.consultancy_complete == NULL}
											{if $first_na_consultancy == "complete"}
												<a href="index.php?m=smd&action=grant_access_request&id={$attorney.userid}" onclick="return myFunction();" class="btn btn-primary">Grant Access</a>
											{else}
												<a href="javascript:void(0)" class="btn btn-primary gray-btn">Request Consultation</a>
											{/if}
											
										{else}
											<a href="javascript:void(0)" class="btn btn-primary gray-btn">Access Granted</a>
										{/if}
								{else}
									<a href="index.php?m=smd&action=send_consultation_request&id={$attorney.userid}" onclick="return myFunction();" class="btn btn-primary">Request Consultation</a>
								{/if}
							</div>
						</div>
					</div>
					{/if}
					{/foreach}

				
			</div>	
		</div>					
	</div>
</div>


<div class="modal fade" id="lawer_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="attorney_name"></h4>
      </div>
      <div class="modal-body" id='attorney_details'>
      </div>
      You are about to select an attorney for free 1-hour consultation. Once selected you will not be able to change this selection. Are you sure?
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Request Consultation</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
	 $(".clear_all").click(function(){ 
	    	$('form.attorney_filter input[type="text"],texatrea, select').val('');
	    	$('#attorney_location').val('');
	    });
</script>