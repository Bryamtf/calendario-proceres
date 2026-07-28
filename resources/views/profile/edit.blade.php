@extends('layouts.app')

@section('titulo', 'Mi perfil')

@section('contenido')
    <div class="max-w-2xl mx-auto p-5 md:p-8 space-y-6">

        <div class="bg-primary text-white rounded-xl p-6 flex items-center gap-4">
            <div
                class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center font-display font-semibold text-xl shrink-0">
                {{ collect(explode(' ', auth()->user()->name))->map(fn($p) => mb_substr($p, 0, 1))->take(2)->implode('') }}
            </div>
            <div>
                <p class="font-display font-semibold text-lg leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-sm text-white/60 mt-0.5">
                    {{ auth()->user()->role->nombre }}
                    @if(auth()->user()->organizacion)
                        · {{ auth()->user()->organizacion->nombre }}
                    @endif
                </p>
            </div>
        </div>

        <div class="bg-white border border-line rounded-xl p-6 md:p-7">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white border border-line rounded-xl p-6 md:p-7">
            @include('profile.partials.update-password-form')
        </div>
    </div>
@endsection

@push('head')
    <style>
        .form-input {
            width: 100%;
            border: 1px solid #E4DFD3;
            border-radius: 0.5rem;
            padding: 0.6rem 0.75rem;
            font-size: 0.875rem;
            background: #fff;
        }

        .form-input:focus {
            border-color: #C08A3E;
            outline: none;
        }

        .form-input:disabled {
            background: #FAF8F4;
            color: #26282B73;
            cursor: not-allowed;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #26282B;
            margin-bottom: 0.35rem;
        }

        .form-hint {
            font-size: 0.75rem;
            color: #26282B73;
            margin-top: 0.3rem;
        }
    </style>
@endpush
