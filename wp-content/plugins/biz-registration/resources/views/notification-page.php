<?php
    /**
     * Name: Notification Content
     * Description: This plugin allow user push notification to user on mobile app.
     * Versiziz Solution Co., Ltd.
     * Author URI: https://bizsolution.com.kh
     */
?>

<div class="biz-notification">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="pill" href="#lucky-draw">Lucky Draw</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#promotion">Promotion</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#message">Message</a>
        </li>
    </ul>
    
    <div class="tab-content">
        <div class="tab-pane fade show active" id="lucky-draw">
            <section>
                <div class="row">
                    <div class="col-lg-3 col-md-7 col-sm-7 col-xs-7">
                        <form method="" id="form-card-number">
                            <div class="form-group">
                                <label for="member_card_number" class="label">Enter Card Number</label>
                                <input type="text" name="member_card_number" class="form-control" id="member_card_number" placeholder="Card Number" minlength="10" value="2802233923">
                            </div>
                            
                            <button type="button" id="filter-member-card" class="btn btn-primary" nonce="<?php echo rand(1, 999999); ?>">Submit</button>
                        </form>
                    </div>
                </div> <!-- .row -->
            </section> <!-- section -->

            <section class="mb-0">
                <div class="row">
                    <div class="col-10">
                        <div class="card-content">
                            <section>
                                <div class="form-row">
                                    <div class="col-auto">
                                        <label class="label" for="prize">Select Prize</label>
                                        <select class="custom-select" id="prize">
                                            <option selected>Choose...</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                    </div>
                                </div>
                            </section> <!-- .prize -->

                            <section class="mb-0">
                                <h3 class="headline">Members Information</h3>
                                <div id="block-card" class="block-card">
                                    <div class="row">
                                        <!-- Receive append data form Ajax -->
                                    </div>
                                    <span class="no-data">No members selected.</span>
                                </div>
                            </section> <!-- .block-card -->
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="button" id="filter-member-card" class="btn btn-primary" nonce="<?php echo rand(1, 999999); ?>">Send</button>
                    </div>
                </div> <!-- .row -->
            </section> <!-- section -->
        </div> <!-- .tab-pane -->
        
        <div class="tab-pane fade" id="promotion">2</div>
        <div class="tab-pane fade" id="message">3</div>
    </div>
</div>