<?php

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Modules\Core\Facades\Settings;

new class extends Component {
    use WithFileUploads;

    public $name;
    public $url;
    public $timezone;
    public $language;
    public $logo;

    public function updateSettings()
    {
        $this->validate([
            'name' => 'required',
            'url' => 'required|url',
            'timezone' => 'required|timezone',
            'language' => 'required',
            'logo' => 'nullable|image:allow_svg'
        ]);

        $settings = [
            'app.name' => $this->name,
            'app.url' => $this->url,
            'app.timezone' => $this->timezone,
            'app.locale' => $this->language,
        ];

        if ($this->logo) {
            Storage::disk('public')->delete('img/' . str_replace('/storage/img/', '', settings('app.logo')));

            $this->logo->storeAs('img', 'Logo.' . $this->logo->getClientOriginalExtension(), 'public');

            $settings['app.logo'] = '/storage/img/Logo.' . $this->logo->getClientOriginalExtension();
        }

        foreach ($settings as $key => $value) {
            Settings::update($key, $value);
        }

        Toaster::success(__('admin::settings.notifications.updated'));

        $this->redirect(url()->previous(), true);
    }

    public function resetLogo()
    {
        Storage::disk('public')->delete('img/' . str_replace('/storage/img/', '', settings('app.logo')));

        Settings::update('app.logo', '/img/Logo.svg');

        activity()
            ->causedBy(auth()->user())
            ->log('admin.settings.logo.reset');

        Toaster::success(__('admin::settings.general.notifications.logo_reset'));

        $this->redirect(url()->previous(), true);
    }

    public function mount()
    {
        $this->name = settings('app.name');
        $this->url = settings('app.url');
        $this->timezone = settings('app.timezone');
        $this->language = settings('app.locale');
    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')],
                ['label' => __('admin::settings.tab_title'), 'url' => route('admin.settings')], ['label' => __('admin::settings.general.tab_title'), 'last' => true]]])
            ->title(__('admin::settings.general.tab_title'));
    }
};
