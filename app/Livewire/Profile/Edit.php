<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Edit extends Component
{
    public User $user;

    public string $name = '';

    public string $email = '';

    /**
     * @var array<string, string>
     */
    public array $passwordForm = [
        'current_password' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    public string $deletePassword = '';

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    #[Computed]
    public function roles(): string
    {
        return $this->user->getRoleNames()->implode(', ') ?: '-';
    }

    #[Computed]
    public function isEmailVerified(): bool
    {
        return (bool) $this->user->email_verified_at;
    }

    public function updateProfile(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user->id),
            ],
        ]);

        $this->user->fill($validated);

        if ($this->user->isDirty('email')) {
            $this->user->email_verified_at = null;
        }

        $this->user->save();

        $this->name = $this->user->name;
        $this->email = $this->user->email;

        session()->flash('status', 'profile-updated');
    }

    public function updatePassword(): void
    {
        $validated = Validator::make($this->passwordForm, [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ])->validate();

        $this->user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->passwordForm = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        session()->flash('status', 'password-updated');
    }

    public function deleteAccount(): mixed
    {
        Validator::make(
            ['deletePassword' => $this->deletePassword],
            ['deletePassword' => ['required', 'current_password']],
            [],
            ['deletePassword' => __('Password')]
        )->validate();

        $user = $this->user;

        Auth::logout();

        $user->delete();

        session()->invalidate();
        session()->regenerateToken();

        $this->deletePassword = '';

        return $this->redirect('/', navigate: true);
    }

    public function sendVerification(): void
    {
        if ($this->user instanceof MustVerifyEmail && ! $this->user->hasVerifiedEmail()) {
            $this->user->sendEmailVerificationNotification();

            session()->flash('status', 'verification-link-sent');
        }
    }

    public function render()
    {
        return view('livewire.profile.edit')
            ->layout('layouts.app');
    }
}
