<?php $this->load->view('admin/inc/sidebar');?>
	<div style="min-height:100vh;">
        <div class="section">
			<div class="container">
				<div class="row col-spacing-50">
                    <div class="col-12 col-md-2"></div>
					<div class="col-12 col-md-8">
                        <h4 class="h5 text-center">Create Service</h4>
                        <?php $this->load->view('msg_view');?>
                        <form method="POST" action="<?= base_url('admin/services/')?>">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label" for="Service Type">Service Category</label>
                                        <select class="form-control custom-select w-100 custom-select-lg" name="product_id" required>
                                            <option value="" selected>-- Select Service Category--</option>
                                            <?php foreach($products as $product) :?>
                                                <option value="<?= $product->id ?>"><?= ucwords($product->title); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label" for="Starting From">Service Title</label>
                                        <input type="text" class="form-control" required name="title" placeholder="Eg : Glo airtime">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label" for="Discount">Service Discount</label>
                                        <select class="form-control custom-select w-100 custom-select-lg" name="discount" required>
                                            <option value="0" selected> 0% Discount </option>
                                            <?php for( $x = 1; $x <= 10;  $x++ ) : ?>
                                                <option value="<?= $x;?>"><?= $x; ?>% Discount</option>
                                            <?php endfor; ?>
                                        </select>
                                        <span class="text-danger">Leave as 0 if you're not giving discount</span>
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label" for="Discount">Discount should work for?</label>
                                        <select class="form-control custom-select w-100 custom-select-lg" name="discount_type" required>
                                            <option value="all" selected>All Buyer</option>
                                            <option value="reseller"> Reseller </option>
                                            <option value="premium"> Premium </option>
                                        </select>
                                        <span class="text-danger">Leave as general, if no exception</span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label" for="Network Name">Network Name</label>
                                        <select class="form-control custom-select w-100 custom-select-lg" name="network_name">
                                            <option value="">-- Please select the appropriate --</option>
                                            <option value="glo">Glo</option>
                                            <option value="9mobile">9mobile</option>
                                            <option value="mtn">MTN</option>
                                            <option value="airtel">Airtel</option>
                                            <option value="gotv">GoTV</option>
                                            <option value="dstv">DSTV</option>
                                            <option value="startimes">STARTIMES</option>
                                            <option value="electricity">Electricity</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="label" for="Starting From">Availability</label>
                                        <select class="form-control custom-select w-100 custom-select-lg" name="availability">
                                            <option value="1" selected>Make Available</option>
                                            <option value="0">Not Available</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="message">Service Message</label> <span class="text-danger">Give a detailed information about this service, if available</span>
                                        <textarea class="form-control text-area" rows="3" name="message"></textarea>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="message">SEO (Search ENgine Optimization)</label> <span class="text-danger">The keywords you'll like the user to search for in getting this item</span>
                                        <textarea class="form-control text-area" rows="3" name="seo" placeholder="Search Engine Optimization"></textarea>
                                    </div>
                                </div>


                                <div class="col-sm-12 col-md-4 offset-4">
                                    <button class="btn btn-outline-danger btn-sm col-sm-12" type="submit" >Create</button>
                                </div>

                            </div>
                        </form>
					</div>
                </div><div style="margin-top:20px;">
                <h4 class="h4 text-center">All Services</h4>
                <hr style="border:1px solid grey;"/>
					<div class="col-12 col-md-12 table-responsive" style="margin-top:10px;">
                      <table class="table table-bordered js-table" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">ID</th>
                          <th scope="col">Service Name</th>
                          <th scope="col">Category</th>
                          <th scope="col">Discount</th>
                          <th scope="col">Availability</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                          <tbody>
                          <?php foreach( $services as $service) : ?>
                              <tr id="<?= $service->id; ?>">
                                  <td class="text-center"><?= $service->id; ?></td>
                                  <td class="text-center"><?= ucwords($service->title); ?></td>
                                  <td class="text-center"><?= ucwords($service->product_name); ?></td>
                                  <td class="text-center"><?= $service->discount .'% / For ' .$service->discount_type .' buyers'; ?></td>
                                  <td class="text-center"><?= ( $service->availability == 1 ) ? 'Yes' : 'No'; ?></td>
                                  <td>
                                      <a href="<?= base_url('admin/plans/?id=' . $service->id .'#plan-table' ); ?>" class="btn btn-outline-success btn-sm">View</a> |
                                      <button class="btn btn-danger btn-sm delete-service" data-id="<?= $service->id ; ?>">Delete</button>
                                  </td>
                              </tr>
                          <?php endforeach;?>
                          </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>		
<?php $this->load->view('admin/inc/footer');?>