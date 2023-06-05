<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'Front/header')?>
      <section class="section-header pb-11 pb-lg-13 bg-primary text-white">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-12 col-md-10 text-center">
              <h3 class="display-3 mb-4">Start Text-to-Speech immediately</h3>
              <p class="lead mb-5 px-lg-5">65 Languages, Over 400 voices</p>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-12 col-md-7">
              <form method="get" action="<?=base_url('auth/signup')?>" class="form-group mb-4">
                <div class="d-flex flex-row justify-content-center">
                  <div class="input-group">
                    <input class="form-control form-control-xl border-light" name="email_address" id="email_address" placeholder="Your email address" type="email" required>
                    <div class="input-group-prepend">
                      <button type="submit" class="btn btn-secondary rounded-right">Create you account</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="pattern bottom"></div>
      </section>
      <div class="section pt-0">
        <div class="container mt-n9 mt-lg-n12 z-2">
          <div class="row justify-content-center">
            <div class="col-12 col-md-10">
              <div class="position-relative">
                <img class="shadow-box rounded" src="<?=base_url('assets/themes/default/front/img/preview.png')?>" alt="CyberBukit TTS preview">
              </div>
            </div>
          </div>
        </div>
      </div>
	  
	  <section class="section section-lg line-bottom-light">
	    <div class="container">
          <div class="row justify-content-center mb-4 mb-lg-6">
            <div class="col-12 text-center">
              <h1 class="display-3 mb-3 mb-lg-4">Why use our TTS service</h1>
              <p class="lead">65 Languages, Over 400 voices available</p>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <nav>
                <div class="nav nav-tabs flex-column flex-md-row bg-white shadow-soft border border-light justify-content-around rounded mb-lg-3 py-3" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link rounded mr-md-3 active" id="nav-content-research-tab" data-toggle="tab" href="#nav-content-research" role="tab" aria-controls="nav-content-research" aria-selected="true"><span class="d-block"><span class="fas fa-file-alt mr-2"></span><span class="font-weight-normal">Multiple Languages and Voices</span></span></a>
                  <a class="nav-item nav-link rounded mr-md-3" id="nav-rank-track-tab" data-toggle="tab" href="#nav-rank-track" role="tab" aria-controls="nav-rank-track" aria-selected="false"><span class="fas fa-chart-line mr-2"></span><span class="font-weight-normal">Standard Voice and AI Voice</span></a>
                  <a class="nav-item nav-link rounded mr-md-3" id="nav-web-monitor-tab" data-toggle="tab" href="#nav-web-monitor" role="tab" aria-controls="nav-web-monitor" aria-selected="false"><span class="far fa-bell mr-2"></span><span class="font-weight-normal">Flexible Pricing Model</span></a>
                </div>
              </nav>
              <div class="tab-content mt-5 mt-lg-6" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-content-research" role="tabpanel" aria-labelledby="nav-content-research-tab">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-md-5">
                      <h3 class="mb-4">Multiple Languages and Voices</h3>
                      <p class="lead">Our TTS service covers 65 languages and over 400 kinds of voices and is increasing continuously. We believe it meets most of your needs.</p>
                      <a href="<?=base_url('auth/signup')?>" class="my-4 mb-5 d-block font-weight-bold"><i class="fas fa-external-link-alt mr-2"></i>Create you account</a>
                    </div>
                    <div class="col-12 col-md-6">
                      <img class="shadow rounded" src="<?=base_url('assets/themes/default/front/img/illustrations/feature-illustration.svg')?>" alt="image">
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="nav-rank-track" role="tabpanel" aria-labelledby="nav-rank-track-tab">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-md-5">
                      <h3 class="mb-4">Standard Voice and AI Voice</h3>
                      <p class="lead">We support standard voice and AI voice(Known as Neural Voice). With standard voice, you got lower cost. With AI voice, you got fluent voices.</p>
                      <a href="<?=base_url('auth/signup')?>" class="my-4 mb-5 d-block font-weight-bold"><i class="fas fa-external-link-alt mr-2"></i>Create you account</a>
                    </div>
                    <div class="col-12 col-md-6">
                      <img class="shadow rounded" src="<?=base_url('assets/themes/default/front/img/illustrations/feature-illustration-2.svg')?>" alt="image">
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="nav-web-monitor" role="tabpanel" aria-labelledby="nav-web-monitor-tab">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-md-5">
                      <h3 class="mb-4">Flexible Pricing Model</h3>
                      <p class="lead">We offer Pay-As-You-Go, Package, Subscription pricing models. You can pay through Paypal or Credit Card and start with a meagre cost.</p>
                      <a href="<?=base_url('auth/signup')?>" class="my-4 mb-5 d-block font-weight-bold"><i class="fas fa-external-link-alt mr-2"></i>Create you account</a>
                    </div>
                    <div class="col-12 col-md-6">
                      <img class="shadow rounded" src="<?=base_url('assets/themes/default/front/img/illustrations/hero-illustration.svg')?>" alt="image">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="section section-lg bg-primary text-white">
        <div class="container">
          <div class="row justify-content-center mb-5 mb-lg-6">
            <div class="col-12 text-center">
              <h2 class="h1 px-lg-5">What can you do with TTS?</h2>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fas fa-users"></i></span>
                  <h5 class="font-weight-normal text-primary">Content Creation</h5>
                  <p>Text-to-Speech makes your content more accessible.</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fab fa-servicestack"></i></span>
                  <h5 class="font-weight-normal text-primary">E-learning</h5>
                  <p>Enhance visual experience such as speech-synchronized facial animation</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fas fa-money-check-alt"></i></span>
                  <h5 class="font-weight-normal text-primary">Telephony</h5>
                  <p>Your contact centers can engage customers with natural sounding voices</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fas fa-users"></i></span>
                  <h5 class="font-weight-normal text-primary">Content Creation</h5>
                  <p>Text-to-Speech makes your content more accessible.</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fab fa-servicestack"></i></span>
                  <h5 class="font-weight-normal text-primary">E-learning</h5>
                  <p>Enhance visual experience such as speech-synchronized facial animation</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fas fa-money-check-alt"></i></span>
                  <h5 class="font-weight-normal text-primary">Telephony</h5>
                  <p>Your contact centers can engage customers with natural sounding voices</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      
      <section class="section section-lg line-bottom-soft">
        <div class="container">
          <div class="row justify-content-center mb-5 mb-lg-7">
            <div class="col-12 col-md-8 text-center">
              <h1 class="h1 font-weight-bolder mb-4">Client Testimonial</h1>
              <p class="lead">Our products are loved by users worldwide</p>
            </div>
          </div>
          <div class="row mb-lg-5">
            <div class="col-12 col-lg-6">
              <div class="customer-testimonial d-flex mb-5">
                <img src="<?=base_url('assets/themes/default/front/img/team/profile-picture-1.jpg')?>" class="image image-sm mr-3 rounded-circle shadow" alt="">
                <div class="content bg-soft shadow-soft border border-light rounded position-relative p-4">
                  <div class="d-flex mb-4">
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                  </div>
                  <p class="mt-2">"This Text-to-Speech tool save my day and boost my business a lot..."</p>
                  <span class="h6">- James Curran <small class="ml-0 ml-md-2">General Manager</small></span>
                </div>
              </div>
              <div class="customer-testimonial d-flex mb-5">
                <img src="<?=base_url('assets/themes/default/front/img/team/profile-picture-2.jpg')?>" class="image image-sm mr-3 rounded-circle shadow" alt="">
                <div class="content bg-soft shadow-soft border border-light rounded position-relative p-4">
                  <div class="d-flex mb-4">
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                  </div>
                  <p class="mt-2">"This Text-to-Speech tool save my day and boost my business a lot..."</p>
                  <span class="h6">- James Curran <small class="ml-0 ml-md-2">General Manager</small></span>
                </div>
              </div>
            </div>
			<div class="col-12 col-lg-6 pt-lg-6">
			  <div class="customer-testimonial d-flex mb-5">
			    <img src="<?=base_url('assets/themes/default/front/img/team/profile-picture-4.jpg')?>" class="image image-sm mr-3 rounded-circle shadow" alt="">
                <div class="content bg-soft shadow-soft border border-light rounded position-relative p-4">
                  <div class="d-flex mb-4">
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                  </div>
                  <p class="mt-2">"This Text-to-Speech tool save my day and boost my business a lot..."</p>
                  <span class="h6">- James Curran <small class="ml-0 ml-md-2">General Manager</small></span>
                </div>
              </div>
			  <div class="customer-testimonial d-flex mb-5">
			    <img src="<?=base_url('assets/themes/default/front/img/team/profile-picture-6.jpg')?>" class="image image-sm mr-3 rounded-circle shadow" alt="">
                <div class="content bg-soft shadow-soft border border-light rounded position-relative p-4">
                  <div class="d-flex mb-4">
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                  </div>
                  <p class="mt-2">"This Text-to-Speech tool save my day and boost my business a lot..."</p>
                  <span class="h6">- James Curran <small class="ml-0 ml-md-2">General Manager</small></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
<?php my_load_view($this->setting->theme, 'Front/social_part')?>
<?php my_load_view($this->setting->theme, 'Front/footer')?>