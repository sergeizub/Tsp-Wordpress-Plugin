<?php
namespace DanceStudioManager;
?>
            <table class="table table-borderless table_mobile_block" style="table-layout: auto;">
                <thead>
                    <tr>
                        <th>Class</th>
                        <?php if (DSM_OC_CLASS_LIST_CLASS_AGE == '1') { ?><th class="text-center">Age</th><?php } ?>
                        <?php if (DSM_OC_CLASS_LIST_CLASS_LEVEL == '1') { ?><th class="text-center">Level</th><?php } ?>
                        <?php if (DSM_OC_CLASS_LIST_CLASS_LOCATION == '1') { ?><th class="text-center">Location</th><?php } ?>
						<?php if (DSM_OC_CLASS_LIST_CLASS_INSTRUCTOR == '1') { ?><th class="text-center">Instructor</th><?php } ?>                    
						<?php if (DSM_OC_CLASS_LIST_CLASS_DATES == '1') { ?><th>Dates</th><?php } ?>
						<?php if (DSM_OC_CLASS_LIST_CLASS_PRICE == '1') { ?><th style="min-width:90px;" class="text-left">Pricing</th><?php } ?>
						<?php if (DSM_OC_CLASS_LIST_TYPE != 'list_by_program' && DSM_OC_CLASS_LIST_TYPE != 'list_by_program_table') { ?><th>Schedules</th><?php } else { ?><th></th><?php } ?>
                       
                    </tr>
                </thead>
                <tbody>
               <?php foreach ($item as $class): ?>
			    <?php //if ($class->PAYMENT_METHOD != "sales_packages") : ?>
				<?php  //continue ; ?>
				<?php //endif; ?>
                    <tr>
                        <td>
							<h4>
							<?php if (DSM_OC_CLASS_LIST_CLASS_ID == '1') { ?><div class="label label-default"><?=$class->ID?></div><?php } ?>
                            <?= (DSM_OC_CLASS_LIST_CLASS_CODE == '1') ? $class->CODE.' ' : '' ?>
                            <?= (DSM_OC_CLASS_LIST_CLASS_NAME == '1') ? $class->NAME.' ' : '' ?>
                            <?= (DSM_OC_CLASS_LIST_CLASS_LEVEL == '1') ? $class->LEVEL : '' ?>
							</h4>
						</td>
                        <?php if (DSM_OC_CLASS_LIST_CLASS_AGE == '1') { ?><td class="text-center"><?=$class->MIN_AGE?> - <?=$class->MAX_AGE?></td><?php } ?>
                        <?php if (DSM_OC_CLASS_LIST_CLASS_LEVEL == '1') { ?><td class="text-center"><?=$class->LEVEL?></td><?php } ?>
                        <?php if (DSM_OC_CLASS_LIST_CLASS_LOCATION == '1') { ?><td class="text-center"><h4><?=$class->LOCATION?></td><?php } ?></h4>  
                        <?php if (DSM_OC_CLASS_LIST_CLASS_INSTRUCTOR == '1') { ?>
                        <td class="text-center">
                            <?=$class->INSTRUCTOR?>
                        </td>
                        <?php } ?>
                        <?php if (DSM_OC_CLASS_LIST_CLASS_DATES == '1') { ?>
                        <td>
							<span style="white-space: nowrap;"><?=$class->CLASS_START?></span> - <span style="white-space: nowrap;"><?=$class->CLASS_END?></span>
						</td>
                        <?php } ?>
                        <?php if (DSM_OC_CLASS_LIST_CLASS_PRICE == '1') { ?>
                        <td>
                            <?php if ($class->PAYMENT_METHOD == "sales_packages") : ?>
                            <ul class="gc list-unstyled">
			                	<li><b><?=DSM_CURRENCY_SIGN?><?=$class->SALES_ITEM_PRICE?></b> <?=$class->SALES_ITEM?></li>
                        	</ul>
                            <?php else: ?>
                                <?=$class->PRICING?>
                                <?php if (DSM_OC_CLASS_REG_FEE_ENABLED == '1' && $class->REG_FEE > 0 ) { ?>
									<br /><small>(Reg. fee <?=DSM_CURRENCY_SIGN?><?=$class->REG_FEE?>)</small>
								<?php } ?>
                            <?php endif; ?>
	                    </td>
                        <?php } ?>

						<td>
							<?php if ( (DSM_OC_CLASS_LIST_TYPE == 'list_by_program' || DSM_OC_CLASS_LIST_TYPE == 'list_by_program_table' || $_SESSION['dsm_client_attrs']['view'] == "List") && $class->PAYMENT_METHOD == "sales_packages") :?>
							<table class="table table-borderless" style="table-layout: auto;">
								<thead>
									<tr>
										<th>Day</th>
										<th>Time</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($class->SCHEDULES as $schedule): ?>
									<tr>
										<td><span style="white-space: nowrap;"><?=$schedule->DAY?></span></td>
										<td><span style="white-space: nowrap;"><?=$schedule->START_TIME?></span> - <span style="white-space: nowrap;padding: 12px;"><?=$schedule->END_TIME?></span></td>
										<td>
										<?php if ($schedule->M_STATUS == 'Full'): ?>
											<button class="btn btn-warning " type="button" style="<?=((!empty($schedule->M_STATUS_COLOR)) ? 'background-color:'.$schedule->M_STATUS_COLOR.';' : '')?><?=((!empty($schedule->M_STATUS_TEXT_COLOR)) ? 'color:'.$schedule->M_STATUS_TEXT_COLOR.';': '')?><?=((!empty($schedule->M_STATUS_BORDER_COLOR)) ? 'border-color:'.$schedule->M_STATUS_BORDER_COLOR.';' : '')?>">
												<span><?=end(explode(",",$schedule->title))?> Full</span>
											</button>
										<?php elseif (App::GetClient()->GetController('auth')->isLogged()): ?>
											<?php if ($schedule->MAX_STUDENTS <= ($schedule->STUDENTS_QUANTITY) && DSM_OC_ALLOW_WAIT_LIST == "1"): ?>
												<a href="#tab-class-registration-<?=$class->ID?>"  dsm_class_id="<?=$class->ID?>" dsm_schedule_id="<?=$schedule->ID?>" title="Add to Wait List" class="btn btn-success dsm_ajax_tab">
																<i class="fa fa-plus-circle"></i> <?=end(explode(",",$schedule->title))?> Add to Wait List  </a>
											<?php else: ?>
												<a href="#tab-class-registration-<?=$class->ID?>" dsm_class_id="<?=$class->ID?>" dsm_schedule_id="<?=$schedule->ID?>" title="Register" class="btn btn-success dsm_ajax_tab">
																<i class="fa fa-plus-circle"></i> <?=end(explode(",",$schedule->title))?> Register </a>
											<?php endif; ?>
										<?php else: ?>
											<button class="btn btn-success btn-login-alert" type="button" dsm_class_id="<?=$class->ID?>" dsm_schedule_id="<?=$schedule->ID?>">
												<span><i class="fa fa-plus-circle"></i> <?=end(explode(",",$schedule->title))?> Register</span>
											</button>		
										<?php endif; ?>
										</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
							<?php else: ?>
								<?php if (App::GetClient()->GetController('auth')->isLogged()): ?>
									<?php if ($class->MAX_STUDENTS <= ($class->STUDENTS_QUANTITY) && DSM_OC_ALLOW_WAIT_LIST == "1"): ?>
										<a href="#tab-class-registration-<?=$class->ID?>" title="Add to Wait List" class="btn btn-success dsm_ajax_tab">
																<i class="fa fa-plus-circle"></i> Add to Wait List</a>
									<?php else: ?>
										<a href="#tab-class-registration-<?=$class->ID?>" title="Register" class="btn btn-success dsm_ajax_tab">
																<i class="fa fa-plus-circle"></i> Register</a>
									<?php endif; ?>
								<?php else: ?>
									<button class="btn btn-success btn-login-alert" type="button">
										<span><i class="fa fa-plus-circle"></i> Register</span>
									</button>							
								<?php endif; ?>
							<?php endif; ?>
						</td>
                    </tr>
				<?php endforeach; ?>               
	            </tbody>
            </table>