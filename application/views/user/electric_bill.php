<?php $this->load->view('user/inc/sidebar');?>
	<div style="min-height:100vh;">
        <div class="section">
			<div class="container">
				<div class="row col-spacing-50">
					<div class="col-12 col-md-12">
                        <?= form_open(); ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="label" for="Network">Electricity Company</label>
                                    <select class="form-control custom-select w-100 custom-select-lg" id="plan" name="plan">
                                        <option value="" selected>-- Select --</option>
                                        <?php foreach ( $plans as $plan ): ?>
                                            <option
                                                    data-network-name="<?= $plan->network_name; ?>"
                                                    data-variation-name="<?= $plan->variation_name; ?>"
                                                    data-service-id="<?= $plan->service_id; ?>"
                                                    data-service-discount="<?= $plan->discount; ?>"
                                                    value="<?= $plan->id?>">
                                                <?= ucwords($plan->name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="iuc-number">Meter Type</label>
                                    <div class="form-group g-brd-gray-light-v7 g-rounded-25 mb-0">
                                        <select class="form-control custom-select w-100 custom-select-lg" id="meter_type" name="meter_type">
                                            <option value="" selected>-- Select Meter Type --</option>
                                            <option value="prepaid">Prepaid</option>
                                            <option value="postpaid">Postpaid</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="iuc-number">Meter Number</label>
                                    <input type="text" name="meter_number" id="meter_number" class="form-control number" required autocomplete="off" placeholder="Meter Number">
                                    <span class="text-danger" id="meter-info"></span>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="registered-number">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control number" required placeholder="Phone Number">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="registered_name">Amount</label>
                                    <input type="text" name="amount" id="amount" class="form-control number" required placeholder="How much will you like to pay?">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="product_id" value="4">
                        <input type="hidden" id="user_meter_name" value="" />
                        <button class="btn btn-outline-danger btn-sm col-sm-4 electricity-bill" data-balance="<?= $user->wallet;?>">Pay Now</button>&nbsp;&nbsp;
                        <button type="reset" class="btn btn-default btn-sm col-sm-3">Clear Form</button>&nbsp;&nbsp;

                        <?= form_close(); ?>
                        <div id="processing"
                             style="display:none;position: center;top: 0;left: 0;width: auto;height: auto%;background: #f4f4f4;z-index: 99;">
                            <div class="text"
                                 style="position: absolute;top: 35%;left: 0;height: 100%;width: 100%;font-size: 18px;text-align: center;">
                                <img src="<?= base_url('assets/images/load.gif'); ?>"
                                     alt="Processing...">
                                Checking your meter number! <br><b
                                        style="color: rgba(2.399780888618386%,61.74193548387097%,46.81068368248487%,0.843);">Please wait...</b>
                            </div>
                        </div>
                    </div>

					<div class="col-12 col-md-12 table-responsive">
                        <h4>10 latest transactions on Electricity Bills</h4><hr />
                        <div style="margin-top: 20px" class="table-responsive">
                            <table class="table table-striped" id="table">
                                <thead>
                                <tr>
                                    <th style="display: none;"></th>
                                    <th>Transaction ID</th>
                                    <th>Date & Time</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach( $transactions as $transaction ): ?>
                                    <tr>
                                        <td style="display: none"><?= $transaction->id; ?></td>
                                        <td><?= $transaction->trans_id; ?></td>
                                        <td><?= neatDate( $transaction->date_initiated) . ' ' . neatTime( $transaction->date_initiated); ?></td>
                                        <td><?= product_name($transaction->product_id); ?></td>
                                        <td><?= payment_id_replacer($transaction->description); ?></td>
                                        <td><?= ngn($transaction->amount)?></td>
                                        <td><?= statusLabel( $transaction->status);?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>		
<?php $this->load->view('user/inc/footer');?>