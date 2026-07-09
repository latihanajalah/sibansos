<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Profil Akun</h1>
                        <p class="mt-2 max-w-2xl text-sm text-slate-500">
                            Perbarui informasi profil Anda, kelola keamanan akun, dan lakukan tindakan penting dengan nyaman.
                        </p>
                    </div>

                    <div class="inline-flex items-center gap-4 rounded-full border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-200 text-lg font-semibold text-slate-800">
                            {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->email, 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-slate-900">{{ auth()->user()->nama }}</p>
                            <p class="truncate text-sm text-slate-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
                <div class="space-y-6">
                    <div class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <div class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="rounded-[1.75rem] border border-rose-200/80 bg-rose-50 p-6 shadow-sm">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
