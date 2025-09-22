@extends('layouts.app')

@section('content')

<div class="container container-normal py-4">
	<div class="row align-items-center g-4">
		<div class="col-lg">
			<div class="container-tight">
				<div class="text-center mb-4">
					<a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.svg" height="36"
							alt=""></a>
				</div>
				<div class="card card-md">
					<div class="card-body">
						<h2 class="h2 text-center mb-4">Login to your account</h2>
						<form method="POST" action="{{ route('login') }}">
                            @csrf
							<div class="mb-3">
								<label class="form-label">Email address</label>
								<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
							<div class="mb-2">
								<label class="form-label">
									Password
									<span class="form-label-description">
										{{-- <a href="./forgot-password.html">I forgot password</a> --}}
									</span>
								</label>
								<div class="input-group input-group-flat">
									<input ref="password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
									<span class="input-group-text">
										<a href="#" @click="alert('asdasd')" class="link-secondary" title="Show password"
											data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
											<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
												viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
												stroke-linecap="round" stroke-linejoin="round">
												<path stroke="none" d="M0 0h24v24H0z" fill="none" />
												<path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
												<path
													d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
											</svg>
										</a>
									</span>
								</div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
							<div class="mb-2">
								<label class="form-check">
									<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
									<span class="form-check-label">Remember me on this device</span>
								</label>
							</div>
							<div class="form-footer">
								<button type="submit" class="btn btn-primary w-100">Sign in</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg d-none d-lg-block">
			<img src="/svg/img-login.svg" />
		</div>
	</div>
</div>
@endsection
