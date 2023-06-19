@extends('layouts.master2')
@section('title', 'تسجيل الدخول')
@section('css')
<!-- Sidemenu-respoansive-tabs css -->
<link href="{{URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css')}}" rel="stylesheet">
@endsection
@section('content')
		<div class="container-fluid">
			<div class="row no-gutter">
				<!-- The image half -->
				<div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
					<div class="row wd-100p mx-auto text-center">
						<div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
							<img src="{{URL::asset('assets/img/media/login.jpg')}}" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
						</div>
					</div>
				</div>
				<!-- The content half -->
				<div class="col-md-6 col-lg-6 col-xl-5 bg-white">
					<div class="login d-flex align-items-center py-2">
						<!-- Demo content-->
						<div class="container p-0">
							<div class="row">
								<div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
									<div class="card-sigin">
										<div class="mb-5 d-flex"> <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="sign-favicon ht-40" alt="logo"></a><h1 class="main-logo1 ml-1 mr-0 my-auto tx-28">Va<span>le</span>x</h1></div>
										<div class="card-sigin">
											<div class="main-signup-header">
												<h2>مرحبا بعودتك</h2>
												<h5 class="font-weight-semibold mb-4">من فضلك قم بتسجيل الدخول </h5>


												<form method="POST" action="{{ route('login') }}">
                                                    @csrf
													<div class="form-group">

                                                        <label>البريد الالكترني</label>
                                                        <input class="form-control" placeholder="ادخل بريدك الالكتروني" value="{{old('email')}}" type="email" name="email">                                                         
                                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

													</div>
													<div class="form-group">
														<label>كلمة المرور</label> <input class="form-control" placeholder="ادخل كلمة المرور" type="password" id="password" class="block mt-1 w-full" name="password" required autocomplete="current-password" > 
                                            
                                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                    </div>
                                            
                                                    <div class="form-group">
                                                        <label for="remember_me" class="inline-flex items-center">
                                                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                                            <span class="ml-2 text-sm text-gray-600">{{ __('تذكرني') }}</span>
                                                        </label>
                                                        </div>
                                                        <div class="flex items-center justify-end mt-4">

                                                    
                                                            <x-primary-button class="w-50">
                                                                {{ __('تسجيل الدخول') }}
                                                            </x-primary-button>
                                                        </div>

												</form>

											</div>
										</div>
									</div>
								</div>
							</div>
							<br>
							<div class="text-center">
								<p>email : admin@gmail.com</p>
								<p>password: 12345678</p>
							</div>
		
						</div><!-- End -->
					
					</div>
					
				</div><!-- End -->
			</div>
		</div>
@endsection
@section('js')
@endsection

