@extends('layouts.app')

@section('title')
    Single Material
@endsection
@if(auth()->user()->isAdmin(auth()->user()->id) || auth()->user()->isApprover(auth()->user()->id))
    @php
        $addUrl = route('materials.create');
    @endphp
@else
    @php
        $addUrl = '#';
    @endphp
@endif
<section class="hero is-white borderBtmLight">
    <nav class="level">
        @include('component.title_set', [
            'spTitle' => 'Single Material',
            'spSubTitle' => 'view a Material',
            'spShowTitleSet' => true
        ])

        @include('component.button_set', [
            'spShowButtonSet' => true,
            'spAddUrl' => null,
            'spAddUrl' => $addUrl,
            'spAllData' => route('materials.index'),
            'spSearchData' => route('materials.search'),
        ])

        @include('component.filter_set', [
            'spShowFilterSet' => true,
            'spAddUrl' => route('materials.create'),
            'spAllData' => route('materials.index'),
            'spSearchData' => route('materials.search'),
            'spPlaceholder' => 'Search materials...',
            'spMessage' => $message = $message ?? NULl,
            'spStatus' => $status = $status ?? NULL
        ])
    </nav>
</section>
@section('column_left')
    {{--    <article class="panel is-primary">--}}
    {{--        <div class="customContainer">--}}
    <div class="card tile is-child">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-account default"></i></span>
                Main Material Data
            </p>
        </header>
        <div class="card-content">
            <div class="card-data">
                <div class="columns">
                    <div class="column is-2">Name</div>
                    <div class="column is-1">:</div>
                    <div class="column">{{ $material->name }}</div>
                </div>
                <div class="columns">
                    <div class="column is-2">Unit</div>
                    <div class="column is-1">:</div>
                    <div class="column">{{ $material->unit }}</div>
                </div>
            </div>
        </div>
    </div>
	
	@php
		$daterange = request()->get('daterange');		
		if(!empty(request()->get('daterange'))) {
			$dates = explode(' - ', $daterange);		
			$start = $dates[0];
			$end = $dates[1];
			$materials = \Tritiyo\Task\Models\TaskMaterial::leftJoin('tasks', 'tasks.id', 'tasks_material.task_id')
					->where('tasks_material.material_id', $material->id)
					->whereBetween('tasks.task_for', [$start, $end])
					->get();
		} else {
			$materials = \Tritiyo\Task\Models\TaskMaterial::leftJoin('tasks', 'tasks.id', 'tasks_material.task_id')
					->where('tasks_material.material_id', $material->id)
					->whereBetween('tasks.task_for', [ date('Y-m-d'), date('Y-m-d') ])
					->get();
		}
		
		//dd($materials);
    @endphp
    
	<div class="card tile is-child" style="margin-top: 15px !important;">
		<header class="card-header">
			<p class="card-header-title">
				<span class="icon"><i class="fas fa-tasks default"></i></span>
				Material Used
			
				{{ Form::open(array('url' => route('materials.show', $material->id), 'method' => 'GET', 'value' => 'PATCH', 'class' => 'dateFilter',  'id' => 'tasks_advanced_search', 'autocomplete' => 'off')) }}
					<div class="columns">						
						<div class="column">
							<input class="input is-small" type="text" name="daterange" value="" />
						</div>
						<div class="column">
							<input name="search" type="submit" class="button is-small is-primary has-background-primary-dark" value="Search"/>
						</div>
					</div>
				{{ Form::close() }}
			</p>
		</header>
		<div class="card-content">
			<div class="card-data">
				@if($materials->count() > 0)
					<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
						<tr>
							<th title="Task date" width="20%">Task Name</th>
							<th title="Task date" width="10%">Task date</th>
							<th title="Task head">Site Head</th>
							<th title="Material Amount">Material Amount</th>
							<th title="Material Note">Material Note</th>
						</tr>
						@php
							$in_total = [];
						@endphp
						
						@foreach($materials as $material)
							<tr>
								<td title="Task ID">
									<a href="{{ route('tasks.show', $material->task_id) }}" target="_blank">
										{{ \Tritiyo\Task\Models\Task::where('id', $material->task_id)->first()->task_name }}                                        
									</a>
								</td>
								<td title="Task date">
									{{ \Tritiyo\Task\Models\Task::where('id', $material->task_id)->first()->task_for }}
								</td>
								<td title="Task head">
									{{ \App\Models\User::where('id', \Tritiyo\Task\Models\Task::where('id', $material->task_id)->first()->site_head)->first()->name }}                                    
								</td>
								<td title="Material Rent">
									{{ $in_total[] = $material->material_amount }}
								</td>
								<td title="Resource Used">
									{{ $material->material_note }}
								</td>
							</tr>                            
						@endforeach
						<tr>
							<td colspan="4">
								In Total for this material
							</td>
							<td>
								{{ 'BDT. ' . array_sum($in_total) }}
							</td>
						</tr>
					</table>
				@else
					
					<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
						<tr>
							<td title="Task date" width="20%">No material used based on your selected date range.</th>						
						</tr>
					</table>
		
				@endif
			</div>
		</div>
	</div>
	
	
@endsection

@section('column_right')
   
@endsection
@section('cusjs')    
    <script type="text/javascript" 
    src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" 
    src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" 
    src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    

	<script>
	$(function() {
	  $('input[name="daterange"]').daterangepicker({
		opens: 'left',
		locale: {
		  format: 'YYYY-MM-DD'
		}
	  }, function(start, end, label) {
		console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
	  });
	});
	</script>
@endsection