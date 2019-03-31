<?php $this->load->view('admin/inc/sidebar');?>
	<div style="min-height:100vh;">
        <div class="section" style="padding-left:10px;padding-right:10px;">
            <div class="row">

                <div class="col-12 col-md-12 table-responsive">
                    <h4>Airtime To Cash Request</h4>
                    <div style="margin-top: 20px" class="table-responsive">
                        <table class="table table-striped" id="table">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Detail</th>
                                <th>Amount Initiated</th>
                                <th>Amount to pay</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $airtime_to_cash_pin as $cash ): ?>
                                <tr>
                                    <td><?= neatDate($cash->datetime) .' '. neatTime($cash->datetime)?></td>
                                    <td><?= $cash->details; ?></td>
                                    <td><?= ngn($cash->incoming)?></td>
                                    <td><?= ngn($cash->outgoing)?></td>
                                    <td>
                                        <form class="form-inline" method="post" action="<?= base_url('admin/tocashprocess/')?>" id="<?= $cash->id?>">
                                            <div class="form-group mx-sm-3 mb-2">
                                                <label for="action" class="sr-only">Action</label>
                                                <select class="form-control-sm" name="action" required>
                                                    <option value=""> -- Select action --</option>
                                                    <option value="approve"> Approve </option>
                                                    <option value="decline"> Decline </option>
                                                </select>
                                                <input type="hidden" name="transaction_type" value="<?= $cash->type;?>">
                                                <input type="hidden" name="txn_id" value="<?= $cash->tid; ?>" />
                                                <input type="hidden" name="user_id" value="<?= $cash->uid; ?>" />
                                                <input type="hidden" name="amount" value="<?= $cash->outgoing; ?>" />
                                                <button type="submit" class="btn btn-sm btn-outline-success mb-2 btn-sm">Submit</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>		
<?php $this->load->view('admin/inc/footer');?>