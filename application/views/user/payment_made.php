<?php $this->load->view('user/inc/sidebar');?>
	<div style="min-height:100vh;">
        <div class="section" style="padding-left:10px;padding-right:10px;">
            <div class="row">
                <div class="col-12 col-md-12">

                    <?php $this->load->view('msg_view'); ?>
                    <div class="col-sm-12">
                        <div class="alert alert-info">
                            <h3><b>Please be informed that your account will be blocked if you submit false payment.</b></h3>
                        </div>

                        <?= form_open_multipart('dashboard/payment_made_process/')?>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="bank_name">Bank Name</label>
                                <select name="bank_name" class="form-control">
                                    <option value="">--Select--</option>
                                    <?php
                                    $banks = explode(',', lang('banks'));
                                    foreach ( $banks as $bank ) : ?>
                                        <option value="<?= trim($bank); ?>"><?= trim($bank); ?></option>
                                    <?php endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="tid" value="<?= $row->trans_id; ?>">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="wallet address">Amount Paid</label>
                                <input type="text" name="amount_paid" id="amount_paid" value="<?= $row->amount; ?>" class="form-control" placeholder="<?= $row->amount;?>">
                            </div>
                        </div>


                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="deposit_type">Deposit Type</label>
                                <select name="deposit_type" required class="form-control">
                                    <option value="Cash Deposit" selected>Cash Deposit</option>
                                    <option value="Mobile Banking">Mobile Banking</option>
                                    <option value="Internet Banking Transfer">Internet Banking Transfer</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="depositor">Remark</label>
                                <textarea name="remark" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="date paid">Date You Paid</label>
                                <input type="date" name="date_paid" required class="form-control"  placeholder="Date you paid">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="pop">Upload Proof of Payment</label>
                                <input type="file" name="pop" required class="form-control" >
                            </div>
                        </div>


                        <button type="submit" class="btn btn-cta btn-cta-primary btn-sm col-sm-4">Submit</button>&nbsp;&nbsp;
                        <button type="reset" class="btn btn-cta btn-cta-secondary btn-sm col-sm-3">Clear Form</button>&nbsp;&nbsp;

                        <?= form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>		
<?php $this->load->view('user/inc/footer');?>