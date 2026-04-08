<x-layout.layout>
    <x-form.layout title="Edit Your Account" subtitle="Start tracking your ideas today.">
        <form action="{{ route('profile.update') }}" method="post" class="mt-10 space-y-5">
            @csrf
            @method('PATCH')

            <x-form.field label="Name" name="name" type="text" :value="$user->name" />
            <x-form.field label="Email" name="email" type="email" :value="$user->email" />
            <x-form.field label="Old password" name="old-password" type="password" />
            <x-form.field label="New password" name="password" type="password" />

            <button type="submit" class="btn mt-2 h-10 w-full" data-test="register-button">Update Account</button>
        </form>
    </x-form.layout>
</x-layout.layout>
