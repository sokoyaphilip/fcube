<?php $this->load->view('user/inc/sidebar');?>
	<div style="min-height:100vh;">
        <div class="section">
			<div class="container">
				<div class="row col-spacing-50">
					<div class="col-12 col-md-12">
                        <form>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="label" for="Network">Select Network</label>
                                        <select class="form-control custom-select w-100 custom-select-lg" id="airtime_network" name="network">
                                            <option value="" selected>-- Select Network --</option>
                                            <?php foreach ($networks as $network ): ?>
                                                <option data-discount="<?= $network->discount; ?>" data-network-name="<?= $network->network_name; ?>"
                                                        value="<?= $network->id; ?>"><?= ucwords($network->title); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="label" for="plan">Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control number" autocomplete="off" />
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="recipent">Enter Phone Number(s)</label>
                                        <textarea name="recipents" id="recipents" class="form-control" rows="3" placeholder="For multiple recipents, separate it with comma(,)"></textarea>
                                        <span class="text-success you-pay"></span>
                                    </div>
                                </div>

                            </div>
                            <input type="hidden" id="product_id" value="2">
                            <button type="button" class="btn btn-outline-danger btn-sm col-sm-4 airtime-purchase" data-balance="<?= $user->wallet;?>">Buy Now</button>&nbsp;&nbsp;
                            <button type="reset" class="btn btn-default btn-sm col-sm-4">Clear Form</button>&nbsp;&nbsp;
                        </form>

                    </div>
					<div class="col-12 col-md-12 table-responsive">
                        <h4>10 latest transactions on Airtime</h4><hr />
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