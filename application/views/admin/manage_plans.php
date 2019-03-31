<?php $this->load->view('admin/inc/sidebar');?>
	<div style="min-height:100vh;">
        <div class="section">
			<div class="container">
				<div class="row col-spacing-50">
                    <div class="col-12 col-md-3"></div>
					<div class="col-12 col-md-6">
                        <h4 class="h5 text-center">Create Plan</h4>
                        <?php $this->load->view('msg_view');?>
                        <form method="POST" action="<?= base_url('admin/plans/')?>">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="label" for="Service Type">Service A Service</label>
                                        <select class="form-control custom-select w-100 custom-select-lg" name="service" id="service" required>
                                            <option value="">-- Select a service --</option>
                                            <?php foreach( $services as $service ) : ?>
                                                <option value="<?= $service->id; ?>"><?= ucwords($service->title. '('. ucwords($service->product_name).') - Discount for ' . $service->discount_type);?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="label" for="Starting From">Plan - Amount</label>
                                        <textarea name="plans" required class="form-control" placeholder="Eg : 1GB - 3000, 2GB - 2500 e.t.c."></textarea>
                                    </div>
                                    <span class="text-danger"><b>The format should be plan - amount separated with comma(,) if many</b></span>
                                </div>
                            </div>
                            <br />
                            <div class="col-sm-12 col-md-4 offset-4">
                                <button class="btn btn-outline-danger btn-sm" type="submit" >Create</button>
                            </div>
                        </form>
					</div>
                </div><div style="margin-top:20px;">
                <h4 class="h4 text-center">All Plans</h4>
                <hr style="border:1px solid grey;"/>
					<div class="col-12 col-md-12 table-responsive" style="margin-top:10px;">
                      <table class="table table-bordered js-table" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">ID</th>
                          <th scope="col">Service Name</th>
                          <th scope="col">Plans Range From</th>
                          <th scope="col">Price Range From</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                          <?php $x = 1; foreach( $plans as $plan) : ?>
                              <tr id="<?= $plan->id; ?>">
                                  <td><?= $x; ?></td>
                                  <td class="text-center">
                                      <?= ucwords($plan->service_name) ?><?= ($plan->discount_type =='all') ? '' : '('.$plan->discount_type.')' ?>
                                  </td>
                                  <td class="text-center"><?= $plan->name; ?></td>
                                  <td class="text-center"><?= ngn($plan->amount) ?></td>
                                  <td>
                                      <?php if(!$id_set) : ?>
                                          <button type="button"
                                                  data-id="<?= $plan->sid; ?>" data-name="<?= $plan->service_name . ' - ', $plan->discount_type; ?>" class="btn btn-outline-success btn-sm open-plan-modal" data-toggle="modal" data-target="#planModal">
                                              See All
                                          </button>
                                      <?php else : ?>
                                          <button type="button"
                                                  data-id="<?= $plan->id; ?>" data-name="<?= $plan->name; ?>" data-amount="<?= $plan->amount;?>" class="btn btn-outline-success btn-sm open-plan-update" data-toggle="modal" data-target="#editModal">
                                              Edit Plan
                                          </button>
                                          <button class="btn btn-danger btn-sm delete-plan" data-id="<?= $plan->id ; ?>">Delete</button>
                                      <?php endif; ?>
                                  </td>
                              </tr>
                              <?php $x++; endforeach;?>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal fade" id="planModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title plan-name"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <table class="table table-striped" id="table">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th class="text-center">Plan Name</th>
                            <th class="text-center">Plan Amount</th>
                        </tr>
                        </thead>
                        <tbody id="plan-body">

                        </tbody>
                    </table>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit This Plan</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form id="edit-plan">
                        <div class="form-group">
                            <label for="plan name">Plan name</label>
                            <input class="form-control" type="text" name="name" id="plan_name" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="plan name">Plan Amount</label>
                            <input class="form-control number" type="text" name="amount" id="plan_value" value="" required>
                        </div>
                        <input type="hidden" name="plan_id" id="edit_plan_id" value="" />
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success update-plan">Update</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
<?php $this->load->view('admin/inc/footer');?>