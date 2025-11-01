@extends('layouts.app')
@section('content')
<div class="row">
    <div class="mmp" style="">
        <div class="">
            <style>
                .mmp {
                    width: 100%;
                }
                .alert {
                    border: none;
                    border-left: 5px solid transparent;
                }
                .alert-success { border-left-color: #28a745; }
                .alert-danger { border-left-color: #dc3545; }
                .alert-warning { border-left-color: #ffc107; }
                .alert-info { border-left-color: #17a2b8; }
                .alert-secondary { border-left-color: #6c757d; }
            </style>

            {{-- 🧩 ALERTAS DE ESTADO DE VERIFICACIÓN METAMAP --}}
            @php
                $status = auth()->user()->metamap_status;
                $verified = auth()->user()->metamap_verified;
            @endphp

            @if($verified)
                <div class="alert alert-success shadow-sm rounded-3 py-3 px-4">
                    <h5 class="mb-1">👤 Bienvenido, {{ auth()->user()->name }} ✅ Verificación completada</h5>
                    <p class="mb-0">Tu identidad fue verificada exitosamente por MetaMap. ¡Gracias por completar el proceso!</p>
                </div>
            @elseif($status === 'verification_blocked')
                <div class="alert alert-danger shadow-sm rounded-3 py-3 px-4">
                    <h5 class="mb-1">👤 Bienvenido, {{ auth()->user()->name }} 🚫 Verificación bloqueada</h5>
                    <p class="mb-0">Tu verificación fue bloqueada por MetaMap. Esto puede deberse a intentos repetidos o a una revisión manual.  
                    Por favor, <strong>contáctanos para revisarlo</strong> o inténtalo nuevamente más tarde.</p>
                </div>
            @elseif($status === 'verification_expired')
                <div class="alert alert-warning shadow-sm rounded-3 py-3 px-4">
                    <h5 class="mb-1">👤 Bienvenido, {{ auth()->user()->name }} ⏰ Verificación expirada</h5>
                    <p class="mb-0">Tu sesión de verificación expiró.  
                    Por favor, vuelve a realizar el proceso de verificación para continuar.</p>
                </div>
            @elseif($status === 'verification_started')
                <div class="alert alert-info shadow-sm rounded-3 py-3 px-4">
                    <h5 class="mb-1">👤 Bienvenido, {{ auth()->user()->name }} 🔄 Verificación en progreso</h5>
                    <p class="mb-0">Estamos procesando tu verificación. Por favor espera unos minutos.</p>
                </div>
            @else
                <div class="alert alert-secondary shadow-sm rounded-3 py-3 px-4">
                    <h5 class="mb-1">👤 Bienvenido, {{ auth()->user()->name }} 🕓 Verificación pendiente</h5>
                    <p class="mb-2">Aún no has completado tu verificación de identidad. Haz clic en el botón de abajo para comenzar.</p>

                    {{-- BOTÓN DE METAMAP --}}
                    <script src="https://web-button.metamap.com/button.js"></script>
                    <metamap-button
                        clientid="68fd81a62298f1f3b99b5a12"
                        flowId="68fd81a62298f1f3b99b5a10"
                        metadata='{"userId": "{{ auth()->user()->id }}", "email": "{{ auth()->user()->email }}"}'>
                    </metamap-button>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
	<div class="col-xl-3 col-md-6">
		<div class="card mb-4 primary-card dashboard-card">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<h5>{{ _lang('Total Members') }}</h5>
						<h4 class="pt-1 mb-0"><b>{{ $total_customer }}</b></h4>
					</div>
					<div>
						<a href="{{ route('members.index') }}"><i class="ti-arrow-right"></i>&nbsp;{{ _lang('View') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-3 col-md-6">
		<div class="card mb-4 success-card dashboard-card">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<h5>{{ _lang('Deposit Requests') }}</h5>
						<h4 class="pt-1 mb-0"><b>{{ request_count('deposit_requests') }}</b></h4>
					</div>
					<div>
						<a href="{{ route('deposit_requests.index') }}"><i class="ti-arrow-right"></i>&nbsp;{{ _lang('View') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-3 col-md-6">
		<div class="card mb-4 warning-card dashboard-card">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<h5>{{ _lang('Withdraw Requests') }}</h5>
						<h4 class="pt-1 mb-0"><b>{{ request_count('withdraw_requests') }}</b></h4>
					</div>
					<div>
						<a href="{{ route('withdraw_requests.index') }}"><i class="ti-arrow-right"></i>&nbsp;{{ _lang('View') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-3 col-md-6">
		<div class="card mb-4 danger-card dashboard-card">
			<div class="card-body">
				<div class="d-flex">
					<div class="flex-grow-1">
						<h5>{{ _lang('Pending Loans') }}</h5>
						<h4 class="pt-1 mb-0"><b>{{ request_count('pending_loans') }}</b></h4>
					</div>
					<div>
						<a href="{{ route('loans.index') }}"><i class="ti-arrow-right"></i>&nbsp;{{ _lang('View') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4 col-sm-5 mb-4">
		<div class="card h-100">
			<div class="card-header d-flex align-items-center">
				<span>{{ _lang('Expense Overview').' - '.date('M Y') }}</span>
			</div>
			<div class="card-body">
				<canvas id="expenseOverview"></canvas>
			</div>
		</div>
	</div>

	<div class="col-md-8 col-sm-7 mb-4">
		<div class="card h-100">
			<div class="card-header d-flex align-items-center">
				<span>{{ _lang('Deposit & Withdraw Analytics').' - '.date('Y')  }}</span>
				<select class="filter-select ml-auto py-0 auto-select" data-selected="{{ base_currency_id() }}">
					@foreach(\App\Models\Currency::where('status',1)->get() as $currency)
					<option value="{{ $currency->id }}" data-symbol="{{ currency($currency->name) }}">{{ $currency->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="card-body">
				<canvas id="transactionAnalysis"></canvas>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 mb-4">
		<div class="card mb-4">
			<div class="card-header">
				{{ _lang('Active Loan Balances') }}
			</div>
			<div class="card-body px-0 pt-0">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="text-nowrap pl-4">{{ _lang('Currency') }}</th>
								<th class="text-nowrap">{{ _lang('Applied Amount') }}</th>
								<th class="text-nowrap">{{ _lang('Paid Amount') }}</th>
								<th class="text-nowrap">{{ _lang('Due Amount') }}</th>
							</tr>
						</thead>
						<tbody>
							@if(count($loan_balances) == 0)
								<tr>
									<td colspan="4"><p class="text-center">{{ _lang('No Data Available') }}</p></td>
								</tr>
							@endif
							@foreach($loan_balances as $loan_balance)
							<tr>
								<td class="pl-4">{{ $loan_balance->currency->name }}</td>
								<td>{{ decimalPlace($loan_balance->total_amount, currency($loan_balance->currency->name)) }}</td>
								<td>{{ decimalPlace($loan_balance->total_paid, currency($loan_balance->currency->name)) }}</td>
								<td>{{ decimalPlace($loan_balance->total_amount - $loan_balance->total_paid, currency($loan_balance->currency->name)) }}</td>
							</tr>
							@endforeach	
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 mb-4">
		<div class="card mb-4">
			<div class="card-header">
				{{ _lang('Due Loan Payments') }}
			</div>
			<div class="card-body px-0 pt-0">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="text-nowrap pl-4">{{ _lang('Loan ID') }}</th>
								<th class="text-nowrap">{{ _lang('Member No') }}</th>
								<th class="text-nowrap">{{ _lang('Member') }}</th>
								<th class="text-nowrap">{{ _lang('Last Payment Date') }}</th>
								<th class="text-nowrap">{{ _lang('Due Repayments') }}</th>
								<th class="text-nowrap text-right pr-4">{{ _lang('Total Due') }}</th>
							</tr>
						</thead>
						<tbody>
							@if(count($due_repayments) == 0)
								<tr>
									<td colspan="6"><p class="text-center">{{ _lang('No Data Available') }}</p></td>
								</tr>
							@endif

							@foreach($due_repayments as $repayment)
							<tr>
								<td class="pl-4">{{ $repayment->loan->loan_id }}</td>
								<td>{{ $repayment->loan->borrower->member_no }}</td>
								<td>{{ $repayment->loan->borrower->name }}</td>
								<td class="text-nowrap">{{ $repayment->repayment_date }}</td>
								<td class="text-nowrap">{{ $repayment->total_due_repayment }}</td>
								<td class="text-nowrap text-right pr-4">{{ decimalPlace($repayment->total_due, currency($repayment->loan->currency->name)) }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="card mb-4">
			<div class="card-header">
				{{ _lang('Recent Transactions') }}
			</div>
			<div class="card-body px-0 pt-0">
				<div class="table-responsive">
					<table class="table table-bordered">
					<thead>
					    <tr>
						    <th class="pl-4">{{ _lang('Date') }}</th>
							<th>{{ _lang('Member') }}</th>
							<th class="text-nowrap">{{ _lang('Account Number') }}</th>
							<th>{{ _lang('Amount') }}</th>
							<th class="text-nowrap">{{ _lang('Debit/Credit') }}</th>
							<th>{{ _lang('Type') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					@if(count($recent_transactions) == 0)
						<tr>
							<td colspan="8"><p class="text-center">{{ _lang('No Data Available') }}</p></td>
						</tr>
					@endif
					@foreach($recent_transactions as $transaction)
						@php
						$symbol = $transaction->dr_cr == 'dr' ? '-' : '+';
						$class  = $transaction->dr_cr == 'dr' ? 'text-danger' : 'text-success';
						@endphp
						<tr>
							<td class="text-nowrap pl-4">{{ $transaction->trans_date }}</td>
							<td>{{ $transaction->member->name }}</td>
							<td>{{ $transaction->account->account_number }}</td>
							<td><span class="text-nowrap {{ $class }}">{{ $symbol.' '.decimalPlace($transaction->amount, currency($transaction->account->savings_type->currency->name)) }}</span></td>
							<td>{{ strtoupper($transaction->dr_cr) }}</td>
							<td>{{ ucwords(str_replace('_',' ',$transaction->type)) }}</td>
							<td>{!! xss_clean(transaction_status($transaction->status)) !!}</td>
							<td class="text-center"><a href="{{ route('transactions.show', $transaction->id) }}" target="_blank" class="btn btn-outline-primary btn-xs"><i class="ti-arrow-right"></i>&nbsp;{{ _lang('View') }}</a></td>
						</tr>
					@endforeach
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js-script')
<script src="{{ asset('public/backend/plugins/chartJs/chart.min.js') }}"></script>
<script src="{{ asset('public/backend/assets/js/dashboard.js?v=1.1') }}"></script>
@endsection
