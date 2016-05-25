<?php if(isset($_GET['notemplate']) && $_GET['notemplate'] == 1): ?>
	<div class="dataset" id="event_dataset_cafe939">
		<h3>Cafe 939 full</h3>
		<ul>
			<?php
				foreach($view->result as $vitem):
					$item = $vitem->_field_data['nid']['entity'];

					$timezone	  = $item->field_dates['und'][0]['timezone'];
					date_default_timezone_set($timezone);
					$timestamp = strtotime($item->field_dates['und'][0]['value']);
					$start_date   = date('l, F j, Y, g:i a',$timestamp + date('Z',$timestamp));
					$teaser       = $item->field_teaser['und'][0]['value'];
					$location     = $item->field_location['und'][0]['value'];
					$description  = $item->field_description['und'][0]['value'];
					$address      = $item->field_event_address['und'][0]['value'];
					$city         = $item->field_city['und'][0]['value'];
					$state        = $item->field_state['und'][0]['value'];
					$zip          = $item->field_zip['und'][0]['value'];
					$admission    = $item->field_admission['und'][0]['value'];
					$ticket_url   = $item->field_ticket_url['und'][0]['url'];
					$caption      = $item->field_caption['und'][0]['value'];
					$path         = url('node/'.$item->nid,array('absolute'=>true));
					$image_uri    = $item->field_image['und'][0]['uri'];
					$image        = image_style_url('image-very-big',$image_uri);
			?>
					<li>
						<div class="event">
							<h4><?php echo $item->title?></h4>
							<div class="datetime"><?php echo $start_date; ?></div>
							<div class="venue"><?php echo $location; ?></div>
							<div class="venue_address">
								<?php echo $address; ?><br />
								<?php echo "$city $state $zip"; ?> <a class="venueMapLink" href="http://maps.google.com/maps?q=<?php echo "$address, $city $state $zip"; ?>">[Map]</a>
							</div>
							<?php if($image_uri): ?>
								<div class="eventImage image_R">
									<img src="<?php echo $image; ?>" alt="<?php echo $item->title; ?>" />
									<div class="imageCaption"><?php echo $caption; ?></div>
								</div>
							<?php endif; ?>
							<div class="teaser">
								<?php echo $teaser; ?>
								<span class="detail_link"><a href="<?php echo $base_url,$path; ?>">[details]</a></span>
							</div>
							<div class="description">
								<?php echo $description; ?>
							</div>
							<?php if($admission): ?>
								<div class="ticket_price"><label for="ticket_price">Admission: </label><?php echo $admission; ?></div>
							<?php endif; ?>
							<?php if($ticket_url): ?>
							<div class="ticket_url"><a href="<?php echo $ticket_url; ?>">Buy Tickets</a></div>
							<?php endif; ?>
						</div>
					</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif;