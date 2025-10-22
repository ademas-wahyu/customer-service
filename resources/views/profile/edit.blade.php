<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-2xl bg-white px-6 py-8 shadow-md sm:p-10">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="rounded-2xl bg-white px-6 py-8 shadow-md sm:p-10">
                @include('profile.partials.update-password-form')
            </div>

            <div class="rounded-2xl bg-white px-6 py-8 shadow-md sm:p-10">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
