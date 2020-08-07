<?php
namespace DanceStudioManager;
?>
<?php if (trim($student['name']) || !App::GetClient()->GetController('auth')->isLogged()) : ?>

	<?php if ($student['STUDENT_ID']): ?>
	<h3>
		<span>Select classes for</span>&nbsp;
		<b><?=$student['name']?></b>
		<?php if (DSM_OC_SHOW_MEMBER_LABELS == "1"): ?>
			<?php if ($student['IS_STUDENT'] == "1"): ?>
				<span class="badge btn-primary"><?= ($student['PARENT_ID'] == "0") ? 'adult' : '' ?> student</span>
			<?php endif; ?>
			<?= ($student['IS_GUARDIAN'] == "1") ? ' <span class="badge btn-primary">guardian</span> ' : ' ' ?>
		<?php endif; ?>
		<span class="badge btn-primary"><?=$student['RELATION']?></span>
	</h3>
	<?php endif; ?>
	<?php foreach ($groupclasses as $class) : ?>
		<h4>
			<?= (DSM_OC_CLASS_LIST_CLASS_ID == "1") ? '<div class="label label-default">'.$class['ID'].'</div>' : '' ?>
			<?= (DSM_OC_CLASS_LIST_CLASS_CODE == "1") ? ''.$class['CODE'].' ' : '' ?>
			<?= (DSM_OC_CLASS_LIST_CLASS_NAME == "1") ? ''.$class['NAME'].' ' : '' ?>
			<?= (DSM_OC_CLASS_LIST_CLASS_LEVEL == "1") ? ''.$class['LEVEL'].' ' : '' ?>
			<small><?=$class['CLASS_START']?> - <?=$class['CLASS_END']?></small>
			<?php /* if ($class['INWAITLIST'] == "1"): ?>
				<div class="label label-warning">Waiting</div>
			<?php elseif ($class['INCLASS'] == "1"): ?>
				<div class="label label-success">Enrolled</div>
			<?php endif;*/ ?>
		</h4>
		
		<?php if ($student['prerequisites_complete'] && DSM_OC_ALLOW_WAIT_LIST == "1" && $class['SCHEDULE']['WAIT_LIST'] == "1" && $class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY']) && false) : ?>
			<div class="alert alert-warning"> Class is full. Student will be added to Wait List.</div>
		<?php endif; ?>
		
		<?php if (DSM_OC_CLASS_LIST_AVAILABLE_SLOTS == '1' && false): ?>
			<p><?= (($class['MAX_STUDENTS'] - $class['STUDENTS_QUANTITY']) > 0) ? ($class['MAX_STUDENTS'] - $class['STUDENTS_QUANTITY']) :'0'?> out of <?=$class['MAX_STUDENTS']?>  slots available</p>
		<? endif ;?>
		
		<?= ($class['PAGES']) ? '<div>'.str_replace('[:pg:]','<br><br>',$class['PAGES']).'</div>' : '' ?>
		
		<?php if (($class['MIN_AGE'] > $student['AGE'] || $class['MAX_AGE'] < $student['AGE']) && DSM_ENROLL_CHECK_AGE == "1") : ?>
			<div class="label label-warning" style="margin-left:20px;">Age ineligible</div>
		<?php elseif (!$student['member_category_allowed']) : ?>
			<div class="label label-warning" style="margin-left:20px;">Member Category ineligible</div>
		<?php else: ?>
			<span style="display:none;" id="ap_options">
			<select class="form-control" name="purchase_id" >
					<?php foreach($active_purchases as $purchase):  ?>
					<?php if ($purchase['value'] == 'unpaid') continue; ?>
					
					<option value="<?=$purchase['value']?>" data-class_registration_method="<?=$purchase['class_registration_method']?>" data-price="<?=$purchase['price']?>" data-period="<?=$purchase['period']?>" data-lessons_available="<?=$purchase['lessons_available']?>" data-type="<?=$purchase['type']?>"><?=$purchase['label']?></option>
					<?php endforeach; ?>
			</select>
			</span>
			<ul class="gc list-unstyled">
				<?php if ($student['prerequisites_complete']) : ?>
					<?php if (DSM_OC_ALLOW_CLASS_REG_PURCH_ITEMS == "1" && $student['STUDENT_ID'] > 0 && $class['ID'] > 0 && ($class['PAYMENT_METHOD'] == 'sales_packages' || DSM_OC_ALLOW_DROP_IN_REGULAR == "1") && $student['classes'][$class['ID']]['sales_items'] && !empty($active_purchases)) : ?>
						<li><span class="active_purchases" id="ap_<?=$student['STUDENT_ID']?>_<?=$class['ID']?>" data-student_id="<?=$student['STUDENT_ID']?>" data-season_status="<?=$class['SCHEDULE']['SEASON_STATUS']?>" data-class_id="<?=$class['ID']?>" data-schedule_id="<?=($schedule_id) ? $schedule_id : $class['SCHEDULE_ID']?>"></span></li>
					<?php endif; ?>
					<?php if (($class['PAYMENT_METHOD'] == 'sales_packages' || DSM_OC_ALLOW_DROP_IN_REGULAR == "1") && $student['classes'][$class['ID']]['sales_items']) : ?>
						<?php foreach($student['classes'][$class['ID']]['sales_items'] as $sales_item) : ?>
						<?php if (($class['SCHEDULE'] != false || ($class['SELL_SEASON_STATUS_1'] == '1' && $sales_item['SELL_SEASON_STATUS_1'] == '1') || ($class['SELL_SEASON_STATUS_1'] == '2' && $sales_item['SELL_SEASON_STATUS_2'] == '1')) &&
									  ($sales_item['SELL_AS_PRODUCT'] == '0' || DSM_OC_SHOW_CLASS_ASSIGNED_PRODUCTS == '1') &&
									  ($sales_item['SELL_INDIVIDUALLY'] == '1' || $sales_item['TYPE'] == 'package')) : ?>
								<li>
									<div class="row">
										<div class="col-xs-3 col-sm-3 col-md-2">
								<?php if ($student['STUDENT_ID'] > 0) :  ?>
									<?php if (DSM_OC_SHOPPING_CART_ENABLED == '1') : ?>
										<?php if ($class['REGISTRATION'] == 'invitation' && $class['ELIGIBLE']) :  ?>
											<div class="label label-warning">Invitation Only</div>
										<?php else: ?>
											<?php if ($sales_item['SALE_STARTED']) : ?>
											
												<a class="btn <?= (!$sales_item['INCART']) ? 'btn-success' : 'btn-primary' ?> btn-sm select-class dsm_ajax_tab"
													<?= ($class['SCHEDULE']['WAIT_LIST'] == '0' && $class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY'])) ? 'disabled="disabled"' : '' ?>
													href = "#tab-class-registration-<?=$class['ID']?>";
													<?php if ($sales_item['INCART']) : ?>
														dsm_obj="checkout"
														dsm_method="DeleteCartItem"
														dsm_item_key = "<?=$sales_item['INCART']?>"
													<?php else: ?>
														dsm_obj="checkout"
														dsm_method="SubmitCartItem"
													<?php endif; ?>
													dsm_class_id="<?=$class['ID']?>"
													dsm_student_id="<?=$student['STUDENT_ID']?>"
													dsm_schedule_id="<?=($schedule_id) ? $schedule_id : $class['SCHEDULE_ID']?>"
													dsm_sales_item_type="<?=$sales_item['TYPE']?>"
													dsm_related_item_id="<?=$sales_item['RELATED_ITEM_ID']?>"
													dsm_sales_item_id="<?=$sales_item['ID']?>"
													>
													<span><?= (!$sales_item['INCART']) ? '<i class="fa fa-plus-circle"></i> Select' : '<i class="fa fa-minus-circle"></i> Remove' ?></span>
												</a>
											<?php else: ?>
												<div class="label label-warning">Sale starts <?=$sales_item['SALE_START_DATE']?></div>
											<?php endif; ?>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
										</div>
										<div class="col-xs-9 col-sm-9 col-md-10 ctitle">
											<?=DSM_CURRENCY_SIGN?><?=$sales_item['PRICE']?> <strong><?=$sales_item['NAME']?></strong>
											<?= ($sales_item['PRICE_DESCRIPTION']) ? '<br><span class="label label-warning">'.$sales_item['PRICE_DESCRIPTION'].'</span>' : '' ?>
											<?= ($sales_item['DESCRIPTION']) ? '<p><small>'.$sales_item['DESCRIPTION'].'</small></p>' : '' ?>
										</div>
									</div>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if ($class['PAYMENT_METHOD'] == 'billing_schedule') : ?>
						<?php foreach($class['CLASS_PRICING'] as $gcp) : ?>
								<li>
									<div class="row">
										<div class="col-xs-3 col-sm-3 col-md-2">
											
										<?php if ($student['STUDENT_ID'] > 0) : ?>
											<?php if (DSM_OC_CLASS_REG_FEE_ENABLED == '1' && $class['REG_FEE'] == "0" && DSM_OC_ZERO_CLASS_REG_FEE_ENABLED != "1") : ?>
												<div id="rcl_<?=$class['ID']?>_<?=$student['STUDENT_ID']?>">
													<?php if ($class['INCLASS'] == "1") : ?>
														<div class="label label-success">Enrolled</div>
													<?php elseif ($class['OC_REGISTRATION'] == 'invitation' && $class != '1') : ?>
														<div class="label label-warning">Invitation Only</div>
													<?php else: ?>
														<?php if (DSM_OC_SHOW_REGISTER_AND_SELECT_BUTTONS) : ?>
															<a class="btn btn-primary btn-sm register-for-class dsm_ajax_tab"
																href = "#tab-class-registration-<?=$class['ID']?>";
																dsm_obj="checkout"
																dsm_method="SubmitCartItem"
																<?= ($class['MAX_STUDENTS'] <= $class['STUDENTS_QUANTITY']) ? 'disabled="disabled"' : '' ?>
																dsm_class_id="<?=$class['ID']?>"
																dsm_billing_schedule="<?=$gcp['BILLING_SCHEDULE']?>"		                                	
																dsm_student_id="<?=$student['STUDENT_ID']?>"
																dsm_schedule_id="<?=$class['SCHEDULE_ID']?>"
																>
																<span><i class="fa fa-plus-circle"></i> Register</span>
															</a>
														<?php endif; ?>
													<?php endif; ?>
												</div>
											<?php else: ?>
												<?php if (DSM_OC_SHOPPING_CART_ENABLED == "1") : ?>
													<?php if ($class['INWAITLIST'] == "1") : ?>
														<div class="label label-success">Waiting</div>
													<?php elseif ($class['INCLASS'] == "1") : ?>
														<div class="label label-success">Enrolled</div>
													<?php else: ?>
														<?php if ($class['OC_REGISTRATION'] == 'invitation' && $class['ELIGIBLE'] != '1') : ?>
															<div class="label label-warning">Invitation Only</div>
														<?php else: ?>
													
															<?php if (DSM_OC_SHOW_REGISTER_AND_SELECT_BUTTONS == "1") : ?>
																<?php if ($class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY']) && DSM_OC_ALLOW_WAIT_LIST == "1") : ?>
																	<div id="atwl_<?=$class['ID']?>_<?=$student['STUDENT_ID']?>">
																		<a class="btn btn-primary btn-sm add-to-wait-list dsm_ajax_tab"
																				href = "#tab-class-registration-<?=$class['ID']?>";
																				dsm_obj="checkout"
																				dsm_method="SubmitCartItem"
																				dsm_class_id="<?=$class['ID']?>"
																				dsm_student_id="<?=$student['STUDENT_ID']?>"
																				dsm_schedule_id="<?=$class['SCHEDULE_ID']?>"
																				<span><i class="fa fa-plus-circle"></i> Add to Wait List</span>
																		</a>
																	</div>
																<?php else: ?>
																	<a class="btn <?= (!$class['INCART']) ? 'btn-success' : 'btn-primary' ?> btn-sm select-class dsm_ajax_tab"
																			<?= ($class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY'])) ? 'disabled="disabled"' : '' ?>
																			href = "#tab-class-registration-<?=$class['ID']?>";
																			<?php if ($class['INCART']) : ?>
																				dsm_obj="checkout"
																				dsm_method="DeleteCartItem"
																			<?php else: ?>
																				dsm_obj="checkout"
																				dsm_method="SubmitCartItem"
																			<?php endif; ?>
																			dsm_item_key="<?=$class['INCART']?>"
																			dsm_class_id="<?=$class['ID']?>"
																			dsm_student_id="<?=$student['STUDENT_ID']?>"
																			dsm_billing_schedule="<?=$gcp['BILLING_SCHEDULE']?>"	
																			dsm_schedule_id="<?=$class['SCHEDULE_ID']?>">
																		<span><?= (!$class['INCART']) ? '<i class="fa fa-plus-circle"></i> Select' : '<i class="fa fa-minus-circle"></i> Remove' ?></span>
																	</a>
																<?php endif; ?>
															<?php endif; ?>
														<?php endif; ?>
													<?php endif; ?>
												<? else: ?>
													<?php if ($class['INWAITLIST'] == '1') : ?>
														<div class="label label-success">Waiting</div>
													<?php elseif ($class['INCLASS'] == '1') : ?>
														<div class="label label-success">Enrolled</div>
													<?php else: ?>
														<?php if ($class['OC_REGISTRATION'] == 'invitation' && $class['ELIGIBLE'] != '1') : ?>
															<div class="label label-warning">Invitation Only</div>
														<?php else: ?>
															<?php if (DSM_OC_SHOW_REGISTER_AND_SELECT_BUTTONS == "1") : ?>
																<?php if ($class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY']) && DSM_OC_ALLOW_WAIT_LIST == '1') : ?>
																	<div id="atwl_<?=$class['ID']?>_<?=$student['STUDENT_ID']?>">
																		<a class="btn btn-primary btn-sm add-to-wait-list dsm_ajax_tab"
																		    href = "#tab-class-registration-<?=$class['ID']?>";
																			dsm_obj="checkout"
																			dsm_method="SubmitCartItem"
																			dsm_class_id="<?=$class['ID']?>"
																			dsm_student_id="<?=$student['STUDENT_ID']?>"
																			dsm_schedule_id="<?=$class['SCHEDULE_ID']?>">
																			<span><i class="fa fa-plus-circle"></i> Add to Wait List</span>
																		</a>
																	</div>
																<?php else: ?>
																	<div id="rcl_<?=$class['ID']?>_<?=$student['STUDENT_ID']?>">
																		<a class="btn btn-primary btn-sm register-for-class dsm_ajax_tab"
																			<?= ($class['SCHEDULE']['MAX_STUDENTS'] <= ($class['SCHEDULE']['STUDENTS_QUANTITY'])) ? 'disabled="disabled"' : '' ?>
																		    href = "#tab-class-registration-<?=$class['ID']?>";
																			dsm_obj="checkout"
																			dsm_method="SubmitCartItem"
																			dsm_class_id="<?=$class['ID']?>"
																			dsm_billing_schedule="<?=$gcp['BILLING_SCHEDULE']?>"	
																			dsm_student_id="<?=$student['STUDENT_ID']?>"
																			dsm_schedule_id="<?=$class['SCHEDULE_ID']?>">
																			<span><i class="fa fa-plus-circle"></i> Register</span>
																		</a>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
														<?php endif; ?>
													<?php endif; ?>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
										<?php if (!App::GetClient()->GetController('auth')->isLogged()) : ?>
											<button class="btn btn-success btn-sm btn-login-alert" >
												<span><i class="fa fa-plus-circle"></i> Select</span>
											</button>                            	
										<?php endif; ?>
									</div>
									<div class="col-xs-9 col-sm-9 col-md-10 ctitle">
									<?php if (DSM_OC_CLASS_LIST_CLASS_PRICE == '1') : ?>
									<?=$class['PRICING']?>
									<?php endif; ?>
									<?php if (DSM_OC_CLASS_REG_FEE_ENABLED == '1' && $class['REG_FEE'] > 0) : ?>
										(Registration fee <?=DSM_CURRENCY_SIGN?><?=$class['REG_FEE']?>)
									<?php endif; ?>
									</div>
								</div>								
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php else: ?>
                		<div class="alert alert-warning"><b>Registration Unavailable!</b> Student doesn't meet pre-requisite requirements for this class. </div>
				<?php endif; ?>
			</ul>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>