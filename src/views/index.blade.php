@extends('layouts.app')

@section('title')
    Materials
@endsection

<section class="hero is-white borderBtmLight">
    <nav class="level">
        @include('component.title_set', [
            'spTitle' => 'Materials',
            'spSubTitle' => 'all materials here',
            'spShowTitleSet' => true
        ])

        @include('component.button_set', [
            'spShowButtonSet' => true,
            'spAddUrl' => null,
            'spAddUrl' => route('materials.create'),
            'spAllData' => route('materials.index'),
            'spSearchData' => route('materials.search'),
        ])

        @include('component.filter_set', [
            'spShowFilterSet' => true,
            'spPlaceholder' => 'Search materials...',
            'spMessage' => $message = $message ?? NULl,
            'spStatus' => $status = $status ?? NULL
        ])
    </nav>
</section>

@section('column_left')
    <div class="columns is-multiline">
        @if(!empty($materials))
            @foreach($materials as $material)
                <div class="column is-4">
                    <div class="borderedCol">
                        <article class="media">
                            <div class="media-content">
                                <div class="content">
                                    <p>
                                        <strong>
                                            <a href="{{ route('materials.show', $material->id) }}"
                                               title="View route">
                                               <strong>{{ $material->name }} </strong>
                                            </a>
                                        </strong>
                                        <br/>
                                        <small>
                                            <strong>Unit: </strong> {{ $material->unit }},
                                        </small>
                                        <br/>
                                    </p>
                                </div>
                                <nav class="level is-mobile">
                                    <div class="level-left">
                                        <a href="{{ route('materials.show', $material->id) }}"
                                           class="level-item"
                                           title="View user data">
                                            <span class="icon is-small"><i class="fas fa-eye"></i></span>
                                        </a>
                                        <a href="{{ route('materials.edit', $material->id) }}"
                                           class="level-item"
                                           title="View all transaction">
                                            <span class="icon is-info is-small"><i class="fas fa-edit"></i></span>
                                        </a>                                        

                                        {!! delete_data('materials.destroy',  $material->id) !!}
                                    </div>
                                </nav>
                            </div>
                        </article>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
